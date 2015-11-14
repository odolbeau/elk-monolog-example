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
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('<info>Let\'s wear a <comment>bermuda</comment>!</info>');

        $logger = $this->getLogger();
        $logger->warning('I wear a bearmuda.');
    }

    /**
     * getLogger
     *
     * @return LoggerInterface
     */
    protected function getLogger()
    {
        $logger = new Logger('demonstration');
        $logger->pushHandler(
            new GelfHandler(
                new Publisher(
                    new UdpTransport('127.0.0.1', 12201)
                )
            )
        );

        return $logger;
    }
}
