<?php

namespace ride\library\html\table\decorator;

use ride\library\html\table\Cell;
use ride\library\html\table\Row;

/**
 * Table decorator to set a static value to cells
 */
class StaticDecorator implements Decorator {

    /**
     * Value to set to the cells
     * @var string
     */
    private $value;

    /**
     * Constructs a new decorator
     * @param string Value to set to the cells
     * @return null
     */
    public function __construct($value) {
        $this->value = $value;
    }

    /**
     * Decorates the table cell by setting the static value to it
     * @param \ride\library\html\table\Cell $cell Cell to decorate
     * @param \ride\library\html\table\Row $row Row which will contain the cell
     * @param int $rowNumber Number of the row in the table
     * @param array $remainingValues Array containing the values of the
     * remaining rows of the table
     * @return null
     */
    public function decorate(Cell $cell, Row $row, $rowNumber, array $remainingValues) {
        $cell->setValue($this->value);
    }

}