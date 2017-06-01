<?php

namespace AppBundle\Command;

use AppBundle\Service\NewsGetter;
use Monolog\Logger;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GetNewsCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:get-news')
            ->setDescription('Get fresh news from BBC Russian twitter account.')
            ->setHelp('This command allows cron to update news feed.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            '<info>Getting fresh news</info>',
            '<info>==================</info>',
            '',
        ]);

        try {
            /** @var NewsGetter $newsGetter */
            $newsGetter = $this->getContainer()->get('app.news_getter');
            $newsGetter->updateNews();

            $output->writeln('<info>Successfully updated!</info>');

        } catch (\Exception $e) {
            /** @var Logger $logger */
            $logger = $this->getContainer()->get('logger');
            $logger->error($e->getMessage());

            $output->writeln('<error>Error!</error>');
            $output->writeln('<error>'. $e->getMessage() .'</error>');
        }
    }
}