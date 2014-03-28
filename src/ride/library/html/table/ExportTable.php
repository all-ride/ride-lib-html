<?php

namespace ride\library\html\table;

use ride\library\html\table\decorator\Decorator;
use ride\library\html\table\export\ExportFormat;
use ride\library\form\Form;

/**
 * Simpe table extended with export functionality
 */
interface ExportTable {

    /**
     * Adds the decorators for a export column. A column decorator gets a specific value from the table value and formats it for the column value.
     * @param \ride\library\html\table\decorator\Decorator $valueDecorator Decorator to decorate the values of the table into a column
     * @param \ride\library\html\table\decorator\Decorator $headerDecorator Decorator to decorate the header of the column
     * @return null
     */
    public function addExportDecorator(Decorator $valueDecorator, Decorator $headerDecorator);

    /**
     * Adds the group decorator to the table. Group decorators should return a boolean to set whether to add the group row or not
     * @param \ride\library\html\table\decorator\Decorator $groupDecorator Decorator to use for group rows
     * @return null
     */
    public function addExportGroupDecorator(Decorator $groupDecorator);

    /**
     * Processes the search and order for the export
     * @param \ride\library\form\Form $form
     * @return null
     */
    public function processExport(Form $form);

    /**
     * Populates the export, generates the actual export
     * @param \ride\library\html\table\export\ExportFormat $export
     * @return null
     */
    public function populateExport(ExportFormat $export);

}