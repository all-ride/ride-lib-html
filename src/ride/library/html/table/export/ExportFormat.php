<?php

namespace ride\library\html\table\export;

use ride\library\html\table\Row;

/**
 * Interface for a format of a table export
 */
interface ExportFormat {

    /**
     * Initializes the export
     * @param string $title
     * @return null
     */
    public function initExport($title);

    /**
     * Adds a header row to the export
     * @param \ride\library\html\table\Row $row Header row to set
     * @return null
     */
    public function addExportHeaderRow(Row $row);

    /**
     * Adds a data row to the export
     * @param \ride\library\html\table\Row $row Data row to add
     * @param boolean $isGroupRow Flag to see if the row is a group row
     * @return null
     */
    public function addExportDataRow(Row $row, $isGroupRow);

    /**
     * Finishes the export
     * @return \ride\library\system\file\File
     */
    public function finishExport();

}