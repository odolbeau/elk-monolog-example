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
use Monolog\Processor\PsrLogMessageProcessor;

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
            ->addArgument('clothing', InputArgument::OPTIONAL, 'What are you wearing?', 'bermuda')
            ->addOption('color', 'c', InputOption::VALUE_REQUIRED, 'Choose a color!', 'random')
        ;
    }

    /**
     * {@inheritDoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $clothing = $input->getArgument('clothing');

        $output->writeln("<info>Let's wear a <comment>$clothing</comment>!</info>");

        $logger = $this->getLogger($output, $clothing);

        $logger->warning('I wear a {color} {clothing}.', [
            'color' => $input->getOption('color'),
            'clothing' => $clothing
        ]);



    }

    /**
     * getLogger
     *
     * @param OutputInterface $output
     * @param string $clothing
     *
     * @return LoggerInterface
     */
    protected function getLogger(OutputInterface $output, $clothing)
    {
        $logger = new Logger($clothing);

        $logger->pushHandler(
            new GelfHandler(
                new Publisher(
                    new UdpTransport('127.0.0.1', 12201)
                )
            )
        );

        if ('bermuda' === $clothing) {
            $logger->pushHandler(
                new ConsoleHandler($output)
            );
        }

        $logger->pushProcessor(new MemoryUsageProcessor(true, false));
        $logger->pushProcessor(new IntrospectionProcessor());
        $logger->pushProcessor(new PsrLogMessageProcessor());

        return $logger;
    }
}
