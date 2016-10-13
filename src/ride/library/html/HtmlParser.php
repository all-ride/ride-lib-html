<?php

namespace ride\library\html;

use \DOMDocument;

/**
 * Parser to perform some processing on HTML
 */
class HtmlParser {

    /**
     * DOMDocument object of the html
     * @var DOMDocument
     */
    private $dom;

    /**
     * Flag to see if the body should be stripped from the result
     * @var boolean
     */
    private $stripBody;

    /**
     * Construct this parser
     * @param string $html HTML which need parsing
     * @param boolean $recover Recover bad HTML
     * @param boolean $format Format the output
     * @return null
     */
    public function __construct($html, $recover = true, $format = true) {
        $this->stripBody = true;

        $this->dom = new DOMDocument();
        $this->dom->recover = $recover;
        $this->dom->formatOutput = $format;
        $this->dom->loadHTML($html);
    }

    /**
     * Sets the flag to see if the body should be stripped from the result
     * @param boolean $stripBody True to remove all html and body wrapping from
     * the DOM processor, false to keep it
     * @return null
     */
    public function setStripBody($stripBody) {
        $this->stripBody = $stripBody;
    }

    /**
     * Get the parsed html
     * @return string
     */
    public function getHtml() {
        $rendered = $this->dom->saveXML();

        if ($this->stripBody) {
            $strip = array(
                '/^<\\?xml.+\\?>/',
                '/<\\!\\[CDATA\\[/',
                '/<\\!DOCTYPE.+">/',
                '/]]>/',
            );

            $rendered = preg_replace($strip, '', $rendered);
            $rendered = str_replace(array('<html>', '</html>', '<body>', '</body>'), '', $rendered);
            $rendered = trim($rendered);
        }

        return $rendered;
    }

    /**
     * Convert the relative anchors to absolute ones
     * @param string $baseUrl base url for the relative anchors
     * @return null
     */
    public function makeAnchorsAbsolute($baseUrl) {
        $anchors = $this->dom->getElementsByTagName('a');
        foreach ($anchors as $anchor) {
            $href = $anchor->getAttribute('href');
            if (!$href || strpos($href, '#') === 0 || strpos($href, 'http://') === 0 || strpos($href, 'https://') === 0 || strpos($href, 'mailto:') === 0) {
                continue;
            }

            $anchor->setAttribute('href', $baseUrl . $href);
        }
    }

    /**
     * Convert the relative images to absolute ones
     * @param string $baseUrl base url for the relative images
     * @return null
     */
    public function makeImagesAbsolute($baseUrl) {
        $images = $this->dom->getElementsByTagName('img');
        foreach ($images as $image) {
            $src = $image->getAttribute('src');
            if (strpos($src, 'http://') === 0 || strpos($src, 'https://') === 0) {
                continue;
            }

            $image->setAttribute('src', $baseUrl . $src);
        }
    }

}
