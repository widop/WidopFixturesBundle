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

/**
 *
 *
 */
class LoadSqlFixturesCommand extends DoctrineCommand
{
    /**
     *
     *
     */
    protected function configure()
    {
        $this
            ->setName('widop:fixtures:sql')
            ->setDescription('Load sql fixtures from a specific environnement to your database.')
            ->addOption('em', null, InputOption::VALUE_REQUIRED, 'The entity manager to use for this command.')
            ->setHelp(<<<EOT
The <info>widop:fixtures:load</info> command loads data fixtures from your bundles according to a specific environnement:

    <info>./app/console widop:fixtures:load</info>

By default, the environnement is <info>dev</info>, so, the dev fixtures will be loaded. If you want to load fixtures
from an other environnement, you can use the <info>--env</info> tag:

    <info>./app/console widop:fixtures:load --env=env_name</info>

If you want to append the fixtures instead of flushing the database first you can use the <info>--append</info> option:

    <info>./app/console widop:fixtures:load --append</info>

By default, delete statements are executed in order to drop the existing rows from the database.
If you want to use a truncate statement instead, you can use the <info>--purge-with-truncate</info> flag:

    <info>./app/console widop:fixtures:load --purge-with-truncate</info>

If you want to use a specific entity manager, you can use the <info>--em</info> flag:

    <info>./app/console widop:fixtures:load --em=em_name</info>
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
        $output->writeln(sprintf("Loading SQL file from project... "));

        // Récuperation des fichiers SQL
        $fixtures = array();
        foreach ($this->getApplication()->getKernel()->getBundles() as $bundle) {
            $environmentPath = $bundle->getPath() . '/DataFixtures/SQL/' . $input->getOption('env');
            $sharedPath = $bundle->getPath() . '/DataFixtures/SQL/shared';

            foreach (array($environmentPath, $sharedPath) as $fixturesPath) {
                if (is_dir($fixturesPath)) {

                    $files = glob($fixturesPath . '/' . "*.sql");
                    foreach ($files as $currentFile) {
                        $fixtures[] = $currentFile;
                    }
                }
            }
        }

        $conn = $this->getDoctrineConnection('default');
        foreach ($fixtures as $fixture) {
            $output->writeln(sprintf("    > Processing file '<info>%s</info>'", $fixture));

            $sql = file_get_contents($fixture);
            $statementst = explode(";", $sql);

            foreach ($statementst as $sqlStatement) {
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
