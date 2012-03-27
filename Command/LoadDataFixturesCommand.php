<?php

/*
 * This file is part of the Widop package.
 *
 * (c) Widop <contact@widop.com>
 *
 * For the full copyright and license information, please read the LICENSE
 * file that was distributed with this source code.
 */

namespace Widop\FixturesBundle\Command;

use Symfony\Component\Console\Command\Command,
    Symfony\Component\Console\Input\InputOption,
    Symfony\Component\Console\Input\InputInterface,
    Symfony\Component\Console\Input\ArgvInput,
    Symfony\Component\Console\Output\OutputInterface,
    Symfony\Component\Console\Output\NullOutput;

/**
 * Load data fixtures.
 *
 * @author GeLo <geloen.eric@gmail.com>
 */
class LoadDataFixturesCommand extends Command
{
    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('widop:fixtures:load')
            ->setDescription('Load data fixtures to your database.')
            ->addOption('append', null, InputOption::VALUE_NONE, 'Append the data fixtures instead of deleting all data from the database first.')
            ->addOption('em', null, InputOption::VALUE_REQUIRED, 'The entity manager to use for this command.')
            ->addOption('purge-with-truncate', null, InputOption::VALUE_NONE, 'Purge data by using a database-level TRUNCATE statement')
            ->setHelp(<<<EOT
The <info>doctrine:fixtures:load</info> command loads data fixtures from your bundles:

  <info>./app/console doctrine:fixtures:load</info>

You can also optionally specify the path to fixtures with the <info>--fixtures</info> option:

  <info>./app/console doctrine:fixtures:load --fixtures=/path/to/fixtures1 --fixtures=/path/to/fixtures2</info>

If you want to append the fixtures instead of flushing the database first you can use the <info>--append</info> option:

  <info>./app/console doctrine:fixtures:load --append</info>

By default Doctrine Data Fixtures uses DELETE statements to drop the existing rows from
the database. If you want to use a TRUNCATE statement instead you can use the <info>--purge-with-truncate</info> flag:

  <info>./app/console doctrine:fixtures:load --purge-with-truncate</info>
EOT
        );
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $inputParameters = array(
            'app/console',
            'doctrine:fixtures:load'
        );

        foreach ($this->getApplication()->getKernel()->getBundles() as $bundle) {
            $environmentPath = $bundle->getPath().'/DataFixtures/ORM/'.$input->getOption('env');
            $sharedPath = $bundle->getPath().'/DataFixtures/ORM/shared';

            foreach (array($environmentPath, $sharedPath) as $fixturesPath) {
                if (is_dir($fixturesPath)) {
                    $inputParameters[] = '--fixtures='.$fixturesPath;
                }
            }
        }

        if (count($inputParameters) === 2) {
            $output->writeln('<error>There are no fixtures to load.</error>');
        } else {
            if ($input->hasOption('append')) {
                $inputParameters[] = '--append';
            }

            if ($input->hasOption('em')) {
                $inputParameters[] = '--em='.$input->getOption('em');
            }

            if ($input->hasOption('purge-with-truncate')) {
                $inputParameters[] = '--purge-with-truncate';
            }

            $doctrineInput = new ArgvInput($inputParameters);
            $this->getApplication()->doRun($doctrineInput, $output);
        }
    }
}
