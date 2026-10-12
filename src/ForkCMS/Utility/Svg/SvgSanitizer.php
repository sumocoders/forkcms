<?php

namespace ForkCMS\Utility\Svg;

use Rhukster\DomSanitizer\DOMSanitizer;
use RuntimeException;

/**
 * Strips active content out of uploaded SVG files.
 *
 * An SVG is an active document: a <script> element or an on* handler inside one executes JavaScript when the file
 * is opened as a document (direct navigation, or an <iframe>/<object>/<embed>). Because uploads are stored under
 * the web root and served straight off disk, an unsanitized SVG is stored XSS in the site origin.
 *
 * Sanitizing the bytes is done on write, so it also covers the upload paths where the mime type is never checked.
 */
final class SvgSanitizer
{
    public static function isSvgExtension(string $extension): bool
    {
        return strtolower($extension) === 'svg';
    }

    /**
     * @throws RuntimeException When the content could not be parsed as an SVG document
     */
    public function sanitize(string $content): string
    {
        if (trim($content) === '') {
            throw new RuntimeException('The SVG file is empty.');
        }

        $sanitized = (new DOMSanitizer(DOMSanitizer::SVG))->sanitize($content);

        if (!is_string($sanitized) || trim($sanitized) === '') {
            throw new RuntimeException('The SVG file could not be sanitized.');
        }

        return $sanitized;
    }

    /**
     * Sanitizes a file in place.
     *
     * @throws RuntimeException When the file can not be read, sanitized or written back
     */
    public function sanitizeFile(string $path): void
    {
        $content = @file_get_contents($path);

        if ($content === false) {
            throw new RuntimeException('Could not read the SVG file "' . $path . '".');
        }

        if (@file_put_contents($path, $this->sanitize($content)) === false) {
            throw new RuntimeException('Could not write the sanitized SVG file "' . $path . '".');
        }
    }
}
