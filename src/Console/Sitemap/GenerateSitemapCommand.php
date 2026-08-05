<?php

namespace Console\Sitemap;

use Common\Sitemap\SitemapGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class GenerateSitemapCommand extends Command
{
    public function __construct(private SitemapGenerator $sitemapGenerator)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName('forkcms:sitemap:generate')
            ->setDescription('Generate sitemap.xml for all active languages');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $count = $this->sitemapGenerator->generate();

        $io->success(sprintf('Sitemap generated with %d URLs.', $count));

        return 0;
    }
}
