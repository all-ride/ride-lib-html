<?php

namespace ride\library\html;

/**
 * Span HTML element
 */
class Span extends AbstractElement {

    /**
     * Body of this element
     * @var string
     */
    protected $body;

    /**
     * Construct a span tag
     * @param string $body Body of this element
     * @return null
     */
    public function __construct($body = null) {
        parent::__construct('span');

        $this->body = $body;
    }

    /**
     * Gets the HTML of the content
     * return string
     */
    protected function getHtmlContent() {
        return $this->body;
    }

}