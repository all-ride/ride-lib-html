<?php

namespace pallo\library\html;

/**
 * Image HTML element
 */
class Image extends AbstractElement {

    /**
     * Name of the source attribute
     * @var string
     */
    const ATTRIBUTE_SRC = 'src';

    /**
     * Construct a image tag
     * @param string $source Source of the image
     * @return null
     */
    public function __construct($source) {
        parent::__construct('img', false);

        $this->setSource($source);
    }

    /**
     * Sets the source of this image
     * @param string $source
     * @return null
     */
    public function setSource($source) {
        $this->setAttribute(self::ATTRIBUTE_SRC, $source);
    }

    /**
     * Gets the source of this image
     * @return string
     */
    public function getSource() {
        return $this->getAttribute(self::ATTRIBUTE_SRC);
    }

}