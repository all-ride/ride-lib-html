<?php

namespace ride\library\html\table;

use ride\library\html\AbstractElement;

/**
 * Table HTML element
 */
class Table extends AbstractElement {

    /**
     * Style class for a table
     * @var string
     */
    const STYLE_TABLE = 'table';

    /**
     * Header row of the table
     * @var Row
     */
    protected $header;

    /**
     * Footer row of the table
     * @var Row
     */
    protected $footer;

    /**
     * Array with Row objects
     * @var array
     */
    protected $rows;

    /**
     * Constructs a new table element
     * @return null
     */
    public function __construct() {
        parent::__construct('table');

        $this->addToClass(self::STYLE_TABLE);

        $this->header = null;
        $this->footer = null;
        $this->rows = array();
    }

    /**
     * Sets a header row for this table
     * @param Row $row
     * @return null
     */
    public function setHeader(Row $row) {
        $this->header = $row;
    }

    /**
     * Sets a footer row for this table
     * @param Row $row
     * @return null
     */
    public function setFooter(Row $row) {
        $this->footer = $row;
    }

    /**
     * Adds a data row to this table
     * @param Row $row
     */
    public function addRow(Row $row) {
        $this->rows[] = $row;
    }

    /**
     * Checks whether this table has rows
     * @return boolean true if the table has rows, false otherwise
     */
    public function hasRows() {
        return !empty($this->rows);
    }

    /**
     * Gets the number of rows set to this table
     * @return integer Number of rows
     */
    public function countRows() {
        return count($this->rows);
    }

    /**
     * Gets the HTML of the table content
     * @return string
     */
    protected function getHtmlContent() {
        $html = '';

        if ($this->header) {
            $html .= "\t<thead>\n";
            $html .= "\t\t" . $this->header->getHtml();
            $html .= "\t</thead>\n";
        }

        $html .= "\t<tbody>\n";
        foreach ($this->rows as $row) {
            $html .= "\t\t" . $row->getHtml() . "\n";
        }
        $html .= "\t</tbody>\n";

        if ($this->footer) {
            $html .= "\t<tfoot>\n";
            $html .= "\t\t" . $this->footer->getHtml();
            $html .= "\t</tfoot>\n";
        }

        return $html;
    }

}