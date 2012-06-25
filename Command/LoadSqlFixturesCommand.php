<?php
/**
 * User: Michiel Missotten
 * Date: 25/06/12
 * Time: 15:31
 */
namespace Widop\FixturesBundle\Command;

use Symfony\Bundle\DoctrineBundle\Command\DoctrineCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Finder\Finder;

/**
 * Command permitting loading SQL scripts.
 *
 */
class LoadSqlFixturesCommand extends DoctrineCommand
{
    /**
     * @override
     */
    protected function configure()
    {
        $this
            ->setName('widop:fixtures:sql')
            ->setDescription('Load sql fixtures from a specific environnement to your database.')
            ->addOption('em', null, InputOption::VALUE_REQUIRED, 'The entity manager to use for this command.')
            ->setHelp(<<<EOT
The <info>widop:fixtures:sql</info> command loads sql scripts from your bundles according to a specific environnement:

    <info>./app/console widop:fixtures:sql</info>

By default, the environnement is <info>dev</info>, so, the dev sql scripts will be loaded. If you want to load fixtures
from an other environnement, you can use the <info>--env</info> tag:

    <info>./app/console widop:fixtures:sql --env=env_name</info>
EOT
        );
    }

    /**
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int|void
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln(sprintf("Loading SQL files from project... "));
        $finder = new Finder();

        // Récuperation des fichiers SQL
        $fixtures = array();
        foreach ($this->getApplication()->getKernel()->getBundles() as $bundle) {
            $environmentPath = $bundle->getPath() . '/DataFixtures/SQL/' . $input->getOption('env');
            $sharedPath = $bundle->getPath() . '/DataFixtures/SQL/shared';

            foreach (array($environmentPath, $sharedPath) as $fixturesPath) {
                if (is_dir($fixturesPath)) {
                    foreach ($finder->name('/\.sql/')->in($fixturesPath) as $fixture) {
                        $fixtures[] = $fixture->getRealpath();
                    }
                }
            }
        }

        $conn = $this->getDoctrineConnection('default');
        foreach ($fixtures as $fixture) {
            $output->writeln(sprintf("  > Processing file '<info>%s</info>'", $fixture));

            $sql = file_get_contents($fixture);
            $statements = explode(";", $sql);

            foreach ($statements as $sqlStatement) {
                $sqlStatement = trim($sqlStatement);

                if ($sqlStatement != "") {
                    $stmt = $conn->prepare($sqlStatement);
                    $stmt->execute();
                }
            }

            unset($sql);
        }
    }
}
