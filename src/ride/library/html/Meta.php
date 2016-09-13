<?php

namespace ride\library\html;

/**
 * Meta HTML element
 */
class Meta extends AbstractElement {

    /**
     * Name of the name attribute
     * @var string
     */
    const ATTRIBUTE_NAME = 'name';

    /**
     * Name of the property attribute
     * @var string
     */
    const ATTRIBUTE_PROPERTY = 'property';

    /**
     * Name of the value attribute
     * @var string
     */
    const ATTRIBUTE_CONTENT = 'content';

    /**
     * Construct a image tag
     * @param string $source Source of the image
     * @return null
     */
    public function __construct() {
        parent::__construct('meta', false);
    }

    /**
     * Sets the name of this meta
     * @param string $name
     * @return null
     */
    public function setName($name) {
        $this->setAttribute(self::ATTRIBUTE_NAME, $name);
        $this->setAttribute(self::ATTRIBUTE_PROPERTY, null);
    }

    /**
     * Gets the name of this meta
     * @return string
     */
    public function getName() {
        return $this->getAttribute(self::ATTRIBUTE_NAME);
    }

    /**
     * Sets the property of this meta
     * @param string $property
     * @return null
     */
    public function setProperty($property) {
        $this->setAttribute(self::ATTRIBUTE_PROPERTY, $property);
        $this->setAttribute(self::ATTRIBUTE_NAME, null);
    }

    /**
     * Gets the property of this meta
     * @return string
     */
    public function getProperty() {
        return $this->getAttribute(self::ATTRIBUTE_PROPERTY);
    }

    /**
     * Sets the content of this meta
     * @param string $content
     * @return null
     */
    public function setContent($content) {
        $this->setAttribute(self::ATTRIBUTE_CONTENT, $content);
    }

    /**
     * Gets the content of this meta
     * @return string
     */
    public function getContent() {
        return $this->getAttribute(self::ATTRIBUTE_CONTENT);
    }

    /**
     * Sets the id of the meta
     * @param string $id
     * @return null
     */
    public function setId($id) {

    }

    /**
     * Sets the style class of this element
     * @param string $class
     * @return null
     */
    public function setClass($class) {

    }

    /**
     * Adds a style class to the style class of this element
     * @param string $class
     * @return null
     */
    public function addToClass($class) {

    }

    /**
     * Removes a style class from the style class of this element
     * @param string $class
     * @return null
     */
    public function removeFromClass($class) {

    }

}
