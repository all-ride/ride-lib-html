<?php

namespace ride\library\html\table;

use ride\library\html\exception\TableException;
use ride\library\html\table\decorator\ColumnDecorator;
use ride\library\html\table\decorator\Decorator;
use ride\library\html\table\decorator\ValueDecorator;
use ride\library\html\Element;

/**
 * Table implementation for an array of values
 */
class ArrayTable extends Table {

    /**
     * Style class for a group row
     * @var string
     */
    const STYLE_GROUP = 'group';

    /**
     * Array with the values for the table
     * @var array
     */
    protected $values;

    /**
     * Array with the value and header decorators
     * @var array
     */
    protected $columnDecorators;

    /**
     * Array the group decorators
     * @var array
     */
    protected $groupDecorators;

    /**
     * Flag to see whether there are header decorators assigned to this table
     * @var boolean
     */
    protected $hasHeaderDecorators;

    /**
     * Flag to see if the values are converted to rows
     * @var boolean
     */
    protected $isPopulated;

    /**
     * Constructs a new table
     * @param array $values Array with the values for the table, values are
     * passed to the decorators when rendering the rows of the table
     * @return null
     */
    public function __construct(array $values) {
        parent::__construct();

        $this->values = $values;

        $this->columnDecorators = array();
        $this->groupDecorators = array();

        $this->hasHeaderDecorators = false;
        $this->isPopulated = false;
    }

    /**
     * Sets the header row for this table. Not implemented, use the a header
     * decorator to set the header.
     * @param Row $row Table header row
     * @return null
     * @throws ride\library\html\exception\TableException when trying to set
     * the header manually
     * @see addDecorator
     */
    public function setHeader(Row $row) {
        throw new TableException('You cannot manually add rows to this type of table. Rows are populated through passing the values of the table to the added decorators.');
    }

    /**
     * Adds a row to tĥe table. Not implemented, use the constructor or another
     * implementation instead
     * @param Row $row Table row to add
     * @return null
     * @throws ride\library\html\exception\TableException when trying to add a
     * row manually
     */
    public function addRow(Row $row) {
        throw new TableException('You cannot manually add rows to this type of table. Rows are populated through passing the values of the table to the added decorators.');
    }

    /**
     * Gets whether this table has rows
     * @return boolean True if the table has rows, false otherwise
     */
    public function hasRows() {
        return !empty($this->values);
    }

    /**
     * Adds a column to the table by adding a decorator.
     *
     * <p>A column decorator gets a specific value from the table value and
     * formats it for the column value.</p>
     * @param ride\library\html\table\decorator\Decorator $valueDecorator
     * Decorator to decorate the values of the table into a column
     * @param ride\library\html\table\decorator\Decorator $headerDecorator
     * Decorator to decorate the header of the column
     * @param boolean $prepend Set to true to prepend the decorator instead of appending it
     * @return null
     */
    public function addDecorator(Decorator $valueDecorator, Decorator $headerDecorator = null, $prepend = false) {
        $columnDecorator = new ColumnDecorator($valueDecorator, $headerDecorator);

        if ($prepend) {
            array_unshift($this->columnDecorators, $columnDecorator);
        } else {
            array_push($this->columnDecorators, $columnDecorator);
        }

        if ($headerDecorator !== null) {
            $this->hasHeaderDecorators = true;
        }
    }

    /**
     * Adds a group decorator to the table.
     *
     * <p>Group decorators should return a boolean to set whether to add the
     * group row or not.</p>
     * <p>Group rows consist by default of a single cell with the number of
     * column decorators set as colspan. This can be altered from within your
     * group decorator.</p>
     * @param ride\library\html\table\decorator\Decorator $groupDecorator
     * Decorator to use for group rows
     * @param boolean $prepend Set to true to prepend the decorator instead of appending it
     * @return null
     */
    public function addGroupDecorator(Decorator $groupDecorator, $prepend = false) {
        if ($prepend) {
            array_unshift($this->groupDecorators, $groupDecorator);
        } else {
            array_push($this->groupDecorators, $groupDecorator);
        }
    }

    /**
     * Gets the HTML of this table
     * @param string $part The part to get
     * @return string
     */
    public function getHtml($part = Element::FULL) {
        if (!$this->isPopulated) {
            $this->addHeader();
            $this->addRows();

            $this->isPopulated = true;
        }

        return parent::getHtml($part);
    }

    /**
     * Adds the header row to the table based on the header decorators
     * @return null
     */
    protected function addHeader() {
        if (!$this->hasHeaderDecorators) {
            return;
        }

        $row = new Row();

        foreach ($this->columnDecorators as $columnDecorator) {
            $cell = new HeaderCell();

            $headerDecorator = $columnDecorator->getHeaderDecorator();
            if ($headerDecorator) {
                $headerDecorator->decorate($cell, $row, 0, array());
            }

            $row->addCell($cell);
        }

        parent::setHeader($row);
    }

    /**
     * Populates the rows of the table based on the provided values and the
     * added decorators
     * @return null
     */
    protected function addRows() {
        if (empty($this->values)) {
            return;
        }

        if (empty($this->columnDecorators)) {
            $this->columnDecorators = new ColumnDecorator(new ValueDecorator());
        }

        $rowNumber = count($this->rows) + 1;
        while ($value = array_shift($this->values)) {
            $this->addGroupRow($value, $rowNumber);
            $this->addDataRow($value, $rowNumber);

            $rowNumber++;
        }
    }

    /**
     * Adds a group row to the table if necessairy, group decorators should
     * return a boolean to indicate whether to add the group row or not
     * @param mixed $value Value of the current row
     * @param integer $rowNumber Number of the current row
     * @return null
     */
    protected function addGroupRow($value, $rowNumber) {
        if (!$this->groupDecorators) {
            return;
        }

        $colSpan = max(count($this->columnDecorators), 1);

        foreach ($this->groupDecorators as $groupDecorator) {
            $cell = new Cell();
            $cell->setAttribute('colspan', $colSpan);
            $cell->setValue($value);

            $row = new Row();
            $row->addCell($cell);

            $addRow = $groupDecorator->decorate($cell, $row, $rowNumber, $this->values);
            if (!$addRow) {
                continue;
            }

            parent::addRow($row);
        }
    }

    /**
     * Adds a data row to the table
     * @param mixed $value Value to decorate and add as table row
     * @param integer $rowNumber Number of the current row
     * @return null
     */
    protected function addDataRow($value, $rowNumber) {
        $row = new Row();

        foreach ($this->columnDecorators as $columnDecorator) {
            $cell = new Cell();
            $cell->setValue($value);

            $valueDecorator = $columnDecorator->getValueDecorator();
            $valueDecorator->decorate($cell, $row, $rowNumber, $this->values);

            $row->addCell($cell);
        }

        parent::addRow($row);
    }

}