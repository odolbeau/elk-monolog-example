<?php

namespace Bab;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Monolog\Logger;
use Monolog\Handler\GelfHandler;
use Gelf\Publisher;
use Gelf\Transport\UdpTransport;
use Symfony\Bridge\Monolog\Handler\ConsoleHandler;
use Monolog\Processor\MemoryUsageProcessor;
use Monolog\Processor\IntrospectionProcessor;

class LogCommand extends Command
{
    /**
     * {@inheritDoc}
     */
    protected function configure()
    {
        $this
            ->setName('log')
            ->setDescription('Log something!')
            ->addOption('color', 'c', InputOption::VALUE_REQUIRED, 'Choose a color!', 'random')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Let\'s wear a <comment>bermuda</comment>!</info>');

        $color = $input->getOption('color');

        $logger = $this->getLogger($output);
        $logger->warning("I wear a $color bearmuda.", [
            'color' => $color
        ]);
    }

    /**
     * getLogger
     *
     * @param OutputInterface $output
     *
     * @return LoggerInterface
     */
    protected function getLogger(OutputInterface $output)
    {
        $logger = new Logger('demonstration');

        $logger->pushHandler(
            new GelfHandler(
                new Publisher(
                    new UdpTransport('127.0.0.1', 12201)
                )
            )
        );

        $logger->pushHandler(
            new ConsoleHandler($output)
        );

        $logger->pushProcessor(new MemoryUsageProcessor(true, false));
        $logger->pushProcessor(new IntrospectionProcessor());

        return $logger;
    }
}
