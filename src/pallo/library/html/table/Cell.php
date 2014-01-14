<?php

namespace pallo\library\html\table;

use pallo\library\html\AbstractElement;

/**
 * Table cell element
 */
class Cell extends AbstractElement {

    /**
     * The value of the cell
     * @var mixed
     */
    protected $value;

    /**
     * Constructs a new cell
     * @param mixed $value Value for the cell
     * @return null
     */
    public function __construct($value = null) {
        parent::__construct('td');

        $this->setValue($value);
    }

    /**
     * Sets the value for this cell
     * @param mixed $value Value for the cell
     * @return null
     */
    public function setValue($value) {
        $this->value = $value;
    }

    /**
     * Gets the value of this cell
     * @return mixed Value of the cell
     */
    public function getValue() {
        return $this->value;
    }

    /**
     * Gets the HTML of the content
     * @return string
     */
    protected function getHtmlContent() {
        return $this->value;
    }

}