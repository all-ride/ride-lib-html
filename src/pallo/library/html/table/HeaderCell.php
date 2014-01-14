<?php

namespace pallo\library\html\table;

/**
 * Table header cell element
 */
class HeaderCell extends Cell {

    /**
     * Constructs a new cell
     * @param mixed $value Value for the cell
     * @return null
     */
    public function __construct($value = null) {
        parent::__construct($value);

        $this->setTag('th');
    }

}