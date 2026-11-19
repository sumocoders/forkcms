<?php

namespace Console\Sitemap;

use Symfony\Component\Console\Attribute\AsCommand;
use Common\Sitemap\SitemapGenerator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'forkcms:sitemap:generate', description: 'Generate sitemap.xml for all active languages')]
final class GenerateSitemapCommand extends Command
{
    public function __construct(private readonly SitemapGenerator $sitemapGenerator)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $count = $this->sitemapGenerator->generate();

        $io->success(sprintf('Sitemap generated with %d URLs.', $count));

        return Command::SUCCESS;
    }
}
