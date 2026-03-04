<?php

namespace Frontend\Core\Engine;

use ForkCMS\App\KernelLoader;
use Symfony\Component\HttpKernel\KernelInterface;
use Frontend\Core\Engine\Navigation as FrontendNavigation;

/**
 * This class will be used to alter the footer-part of the HTML-document that will be created by the frontend.
 */
class Footer extends KernelLoader
{
    /**
     * TwigTemplate instance
     *
     * @var TwigTemplate
     */
    protected $template;

    /**
     * URL instance
     *
     * @var Url
     */
    protected $url;

    public function __construct(KernelInterface $kernel)
    {
        parent::__construct($kernel);

        $this->template = $this->getContainer()->get('templating');
        $this->url = $this->getContainer()->get('url');

        $this->getContainer()->set('footer', $this);
    }

    /**
     * Parse the footer into the template
     */
    public function parse(): void
    {
        $footerLinks = (array) Navigation::getFooterLinks();
        $this->template->assignGlobal('footerLinks', $footerLinks);

        $siteHTMLEndOfBody = (string) $this->get('fork.settings')->get('Core', 'site_html_end_of_body', $this->get('fork.settings')->get('Core', 'site_html_footer', null));

        // add Google sitelinks search box code if wanted.
        if ($this->get('fork.settings')->get('Search', 'use_sitelinks_search_box', true)) {
            $searchUrl = FrontendNavigation::getUrlForBlock('Search');
            $url404 = FrontendNavigation::getUrl(Model::ERROR_PAGE_ID);
            if ($searchUrl !== $url404) {
                $siteHTMLEndOfBody .= $this->getSiteLinksCode($searchUrl);
            }
        }

        // assign site wide html
        $this->template->assignGlobal('siteHTMLEndOfBody', $siteHTMLEndOfBody);

        // @deprecated remove this in Fork 6, use siteHTMLEndOfBody
        $this->template->assignGlobal('siteHTMLFooter', $siteHTMLEndOfBody);
    }

    /**
     * Returns the code needed to get a site links search box in Google.
     * More information can be found on the offical Google documentation:
     * https://developers.google.com/webmasters/richsnippets/sitelinkssearch
     *
     * @param string $searchUrl The url to the search page
     *
     * @return string The script needed for google
     */
    protected function getSiteLinksCode(string $searchUrl): string
    {
        $siteLinksCode = '<script type="application/ld+json">' . "\n";
        $siteLinksCode .= '{' . "\n";
        $siteLinksCode .= '    "@context": "https://schema.org",' . "\n";
        $siteLinksCode .= '    "@type": "WebSite",' . "\n";
        $siteLinksCode .= '    "url": "' . SITE_URL . '",' . "\n";
        $siteLinksCode .= '    "potentialAction": {' . "\n";
        $siteLinksCode .= '        "@type": "SearchAction",' . "\n";
        $siteLinksCode .= '        "target": "' . SITE_URL . $searchUrl . '?form=search&q_widget={q_widget}",' . "\n";
        $siteLinksCode .= '        "query-input": "name=q_widget"' . "\n";
        $siteLinksCode .= '    }' . "\n";
        $siteLinksCode .= '}' . "\n";
        $siteLinksCode .= '</script>';

        return $siteLinksCode;
    }
}
