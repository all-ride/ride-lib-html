<?php

namespace ride\library\html;

use ride\library\html\exception\HtmlException;

/**
 * Abstract implementation of a HTML element
 */
abstract class AbstractElement implements Element {

    /**
     * Name of the id attribute
     * @var string
     */
    const ATTRIBUTE_ID = 'id';

    /**
     * Name of the class attribute
     * @var string
     */
    const ATTRIBUTE_CLASS = 'class';

    /**
     * Name of the tag
     * @var string
     */
    protected $tag;

    /**
     * Flag to see if the element has a close tag
     * @var boolean
     */
    protected $hasCloseTag;

    /**
     * The style id of this element
     * @var string
     */
    protected $id;

    /**
     * The style classes of this element
     * @var array
     */
    private $class;

    /**
     * The attributes of this element
     * @var array
     */
    private $attributes;

    /**
     * Construct a new element
     * @param string $tag The name of the tag
     * @param boolean $hasCloseTag Flag to see if the tag has a close tag
     * @return null
     */
    public function __construct($tag, $hasCloseTag = true) {
        $this->setTag($tag, $hasCloseTag);

        $this->id = null;
        $this->class = array();
        $this->attributes = array();
    }

    /**
     * Sets the name of the tag
     * @param string $tag
     * @param boolean $hasCloseTag
     * @return null
     */
    protected function setTag($tag, $hasCloseTag = true) {
        $this->tag = $tag;
        $this->hasCloseTag = $hasCloseTag;
    }

    /**
     * Sets the style id of this element
     * @param string $id
     * @return null
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * Gets the style id of this element
     * @return string
     */
    public function getId() {
        return $this->id;
    }

    /**
     * Get the HTML of the style id attribute
     * @return string
     */
    protected function getIdHtml() {
        if ($this->id) {
            return $this->getAttributeHtml(self::ATTRIBUTE_ID, $this->id);
        }

        return '';
    }

    /**
     * Sets the style class of this element
     * @param string $class
     * @return null
     */
    public function setClass($class) {
        $class = trim($class);

        if ($class) {
            $this->class = array($class => $class);
        } else {
            $this->class = array();
        }
    }

    /**
     * Adds a style class to the style class of this element
     * @param string $class
     * @return null
     * @throws ride\ZiboException when the provided style class is empty
     */
    public function addToClass($class) {
        $class = trim($class);

        if (!is_string($class) || $class == '') {
            throw new HtmlException('Provided class is empty');
        }

        $classes = explode(' ', $class);
        foreach ($classes as $class) {
            $this->class[$class] = true;
        }
    }

    /**
     * Removes a style class from the style class of this element
     * @param string $class
     * @return null
     * @throws ride\ZiboException when the provided style class is empty or not a string
     */
    public function removeFromClass($class) {
        if (!is_string($class) || $class == '') {
            throw new HtmlException('Provided class is empty');
        }

        if (isset($this->class[$class])) {
            unset($this->class[$class]);
        }
    }

    /**
     * Get the current class(es)
     * @return string
     */
    public function getClass() {
        $result = '';

        foreach ($this->class as $class => $null) {
            $result .= ($result == '' ? '' : ' ' ) . $class;
        }

        return $result;
    }

    /**
     * Get the HTML of the class attribute
     * @return string
     */
    protected function getClassHtml() {
        if ($this->class) {
            return $this->getAttributeHtml(self::ATTRIBUTE_CLASS, $this->getClass());
        }

        return '';
    }

    /**
     * Sets an attribute for this element
     * @param string $attribute name of the attribute
     * @param string $value value of the attribute
     * @return null
     * @throws ride\ZiboException when the name of attribute is empty or not a string
     */
    public function setAttribute($attribute, $value) {
        if (!is_string($attribute) || $attribute === '') {
            throw new HtmlException('Provided name of the attribute is empty or invalid');
        }

        if ($attribute == self::ATTRIBUTE_ID) {
            return $this->setId($value);
        }
        if ($attribute == self::ATTRIBUTE_CLASS) {
            return $this->setClass($value);
        }

        $this->attributes[$attribute] = $value;
    }

    /**
     * Gets all the attributes of this element
     * @return array Array with the attribute name as key and the attribute value as value
     */
    public function getAttributes() {
        return $this->attributes;
    }

    /**
     * Gets a attribute of this element
     * @param string $attribute name of the attribute
     * @param mixed $default value to return when the attribute is not set
     * @return string the value of the attribute
     */
    public function getAttribute($attribute, $default = null) {
        if ($attribute == self::ATTRIBUTE_ID) {
            return $this->getId();
        }
        if ($attribute == self::ATTRIBUTE_CLASS) {
            return $this->getClass();
        }

        $result = $default;
        if (isset($this->attributes[$attribute])) {
            $result = $this->attributes[$attribute];
        }

        return $result;
    }

    /**
     * Clear all the attributes
     * @return null
     */
    public function resetAttributes() {
        $this->class = array();
        $this->attributes = array();
    }

    /**
     * Gets the HTML of the attributes of this element
     * @return string HTML of the attributes
     */
    protected function getAttributesHtml() {
        if (!$this->attributes) {
            return '';
        }

        $result = '';
        foreach ($this->attributes as $attribute => $value) {
            $result .= $this->getAttributeHtml($attribute, $value);
        }

        return $result;
    }

    /**
     * Gets the HTML of a attribute
     * @param string $attribute name of the attribute
     * @param string $value value of the attribute
     * @return string HTML of the attribute (eg. ' name="value"')
     */
    protected function getAttributeHtml($attribute, $value) {
        return ' ' . $attribute . '="' . htmlspecialchars((string) $value) . '"';
    }

    /**
     * Gets the HTML of this element
     * @param string $part The part to get
     * @return string
     */
    public function getHtml($part = Element::FULL) {
        $html = '';


        if ($part == Element::OPEN || $part == Element::FULL) {
            $html .= $this->getHtmlOpen();
        }

        if ($part == Element::CONTENT || $part == Element::FULL) {
            $html .= $this->getHtmlContent();
        }

        if ($part == Element::CLOSE || $part == Element::FULL) {
            $html .= $this->getHtmlClose();
        }

        return $html;
    }

    /**
     * Gets the HTML of the open tag
     * return string
     */
    protected function getHtmlOpen() {
        return '<' . $this->tag .
            $this->getIdHtml() .
            $this->getClassHtml() .
            $this->getAttributesHtml() .
            ($this->hasCloseTag ? '' : ' /') . '>';
    }

    /**
     * Gets the HTML of the content
     * return string
     */
    protected function getHtmlContent() {
        return '';
    }

    /**
     * Gets the HTML of the close tag
     * return string
     */
    protected function getHtmlClose() {
        if (!$this->hasCloseTag) {
            return '';
        }

        return '</' . $this->tag . '>';
    }

}