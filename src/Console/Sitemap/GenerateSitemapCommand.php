<?php

namespace Console\Sitemap;

use Backend\Core\Language\Language;
use Common\Sitemap\SitemapUrl;
use Common\Sitemap\SitemapUrlProviderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

final class GenerateSitemapCommand extends Command
{
    /** @param iterable<SitemapUrlProviderInterface> $providers */
    public function __construct(private iterable $providers, private string $projectDir)
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
        $urls = [];

        foreach (Language::getActiveLanguages() as $locale) {
            foreach ($this->providers as $provider) {
                array_push($urls, ...$provider->getUrls($locale));
            }
        }

        file_put_contents($this->projectDir . '/sitemap.xml', $this->buildXml($urls));

        $io->success(sprintf('Sitemap generated with %d URLs.', count($urls)));

        return 0;
    }

    /** @param SitemapUrl[] $urls */
    private function buildXml(array $urls): string
    {
        $writer = new \XMLWriter();
        $writer->openMemory();
        $writer->setIndent(true);
        $writer->setIndentString('  ');
        $writer->startDocument('1.0', 'UTF-8');
        $writer->startElement('urlset');
        $writer->writeAttribute('xmlns', 'http://www.sitemaps.org/schemas/sitemap/0.9');

        foreach ($urls as $url) {
            $writer->startElement('url');
            $writer->writeElement('loc', $url->loc);
            if ($url->lastmod !== null) {
                $writer->writeElement('lastmod', $url->lastmod->format('Y-m-d'));
            }
            if ($url->changefreq !== null) {
                $writer->writeElement('changefreq', $url->changefreq);
            }
            if ($url->priority !== null) {
                $writer->writeElement('priority', number_format($url->priority, 1));
            }
            $writer->endElement();
        }

        $writer->endElement();
        $writer->endDocument();

        return $writer->outputMemory();
    }
}
