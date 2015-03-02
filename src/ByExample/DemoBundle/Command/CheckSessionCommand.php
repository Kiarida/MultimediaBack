<?php

namespace ByExample\DemoBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CheckSessionCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('sessionCheck')
            ->setDescription('Greet someone')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    	$em = $this->getContainer()->get('doctrine')->getManager();
        $sessions = $em->getRepository('ByExampleDemoBundle:Session')->getExpiredSession();
        foreach ($sessions as $session) {
            $session->setDatefin(new \Datetime());
            $em->persist($session);
        }
        $em->flush();
        $output->writeln("finish");

    }
}




?>