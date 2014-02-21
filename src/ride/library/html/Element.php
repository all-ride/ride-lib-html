<?php

namespace ride\library\html;

/**
 * Interface for a HTML element
 */
interface Element {

    /**
     * Part option for the open tag of the element
     * @var integer
     */
    const OPEN = '_open_';

    /**
     * Part option for the content of the element
     * @var integer
     */
    const CONTENT = '_content_';

    /**
     * Part option for the close tag of the element
     * @var integer
     */
    const CLOSE = '_close_';

    /**
     * Part option for the full element HTML
     * @var integer
     */
    const FULL = '_full_';

    /**
     * Sets the style id of this element
     * @param string $id
     * @return null
     */
    public function setId($id);

    /**
     * Gets the style id of this element
     * @return string
     */
    public function getId();

    /**
     * Sets the style class of this element
     * @param string $class
     * @return null
     */
    public function setClass($class);

    /**
     * Adds a style class to this element
     * @param string $class
     * @return null
     */
    public function addToClass($class);

    /**
     * Removes a style class from this element
     * @param string $class
     * @return null
     */
    public function removeFromClass($class);

    /**
     * Gets the style class of this element
     * @return string
     */
    public function getClass();

    /**
     * Sets a attribute to this element
     * @param string $attribute name of the attribute
     * @param mixed $value value for the attribute
     * @return null
     */
    public function setAttribute($attribute, $value);

    /**
     * Gets all the attributes of this element
     * @return array Array with the name of the attribute as key and the value of the attribute as value
     */
    public function getAttributes();

    /**
     * Gets a attribute of this element
     * @param string $attribute name of the attribute
     * @param mixed $default value to return when the attribute is not set
     * @return mixed
     */
    public function getAttribute($attribute, $default = null);

    /**
     * Gets the HTML of this element
     * @param string $part The part to get
     * @return string
     */
    public function getHtml($part = Element::FULL);

}