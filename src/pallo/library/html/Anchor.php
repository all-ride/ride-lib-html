<?php

namespace pallo\library\html;

use pallo\library\html\exception\HtmlException;

/**
 * Element for a HTML anchor
 */
class Anchor extends AbstractElement {

    /**
     * Name of the href attribute
     * @var string
     */
    const ATTRIBUTE_HREF = 'href';

    /**
     * Link of this anchor
     * @var string
     */
    private $href;

    /**
     * Label of this anchor
     * @var string
     */
    private $label;

    /**
     * Construct a new HTML anchor
     * @param string $label
     * @param string $href
     * @return null
     */
    public function __construct($label, $href = '#') {
        parent::__construct('a');

        $this->setLabel($label);
        $this->setHref($href);
    }

    /**
     * Sets the label of this anchor element
     * @param string $label
     * @return null
     * @throws zibo\ZiboException when the label is empty or not a string
     */
    public function setLabel($label) {
        if (!is_scalar($label) || $label == '') {
            throw new HtmlException('Provided label is empty or invalid');
        }

        $this->label = $label;
    }

    /**
     * Gets the label of this anchor element
     * @return string
     */
    public function getLabel() {
        return $this->label;
    }

    /**
     * Sets the link of this anchor element
     * @param string $href
     * @return null
     */
    public function setHref($href) {
        parent::setAttribute(self::ATTRIBUTE_HREF, $href);
    }

    /**
     * Gets the link of this anchor element
     * @param string $default default link value for when no link is set
     * @return string
     */
    public function getHref($default = null) {
        return parent::getAttribute(self::ATTRIBUTE_HREF, $default);
    }

    /**
     * Gets the HTML of the content
     * return string
     */
    protected function getHtmlContent() {
        return $this->getLabel();
    }

}