<?php

// src/AppBundle/Command/GreetCommand.php
namespace Bkstg\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CronCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('cron:run')
            ->setDescription('Run cron tasks for the Bkstg')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $message_manager = $this->getContainer()->get('message.manager');
        $name = $input->getArgument('name');
        if ($name) {
            $text = 'Hello '.$name;
        } else {
            $text = 'Hello';
        }

        if ($input->getOption('yell')) {
            $text = strtoupper($text);
        }

        $output->writeln($text);
    }
}
