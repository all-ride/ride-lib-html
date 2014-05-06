<?php

namespace ride\library\html\table\decorator;

use ride\library\html\table\Cell;
use ride\library\html\table\Row;
use ride\library\html\Anchor;
use ride\library\reflection\ReflectionHelper;

/**
 * Abstract decorator to create an action
 */
abstract class ActionDecorator extends AnchorDecorator {

    /**
     * Style class for action cells
     * @var string
     */
    const STYLE_ACTION = 'action';

    /**
     * The label of the action
     * @var string
     */
    protected $label;

    /**
     * Flag to hide the action
     * @var boolean
     */
    private $willDisplay;

    /**
     * Flag to disable the action
     * @var boolean
     */
    private $isDisabled;

    /**
     * Constructs a new action decorator
     * @param string $label The label for the action
     * @param string $href Base href attribute for the action
     * @param string $message A confirmation message
     * @param string|array|null $property Property of the value
     * @param \ride\library\reflection\ReflectionHelper $reflectionHelper
     * Instance of the reflection helper to resolve properties
     * @return null
     */
    public function __construct($label, $href, $message = null, $property = null, ReflectionHelper $reflectionHelper = null) {
        parent::__construct($href, $message, $property, null, $reflectionHelper);

        $this->label = $label;
    }

    /**
     * Decorates the cell with the action for the value of the cell
     * @param \ride\library\html\table\Cell $cell Cell to decorate
     * @param \ride\library\html\table\Row $row Row of the cell
     * @param integer $rowNumber Current row number
     * @param array $remainingValues Array containing the values of the
     * remaining rows of the table
     * @return null
     */
    public function decorate(Cell $cell, Row $row, $rowNumber, array $remainingValues) {
        $this->willDisplay = true;
        $this->isDisabled = false;

        $cell->addToClass(self::STYLE_ACTION);

        $value = $cell->getValue();

        parent::decorate($cell, $row, $rowNumber, $remainingValues);

        if (!$this->willDisplay) {
            $cell->setValue('');

            return;
        }

        if (!$this->isDisabled) {
            return;
        }

        $label = $this->decorateValue($value);

        $cell->setValue($label);
    }

    /**
     * Perform the actual decorating of the value
     * @param mixed $value Value to decorate
     * @return string Decorated value
     */
    protected function decorateValue($value) {
        return $this->label;
    }

    /**
     * Sets whether the decorator will display the action of the current row
     * @param boolean $flag
     * @return null
     */
    protected function setWillDisplay($flag) {
        $this->willDisplay = $flag;
    }

    /**
     * Sets whether will disable the action by only providing the label of the action, not the anchor
     * @param boolean $flag True to only display the label, false to display the full action
     * @return null
     */
    protected function setIsDisabled($flag) {
        $this->isDisabled = $flag;
    }

    /**
     * Hook to perform extra processing on the generated anchor
     * @param \ride\library\html\Anchor $anchor Generated anchor for the cell
     * @param mixed $value Value of the cell
     * @return null
     */
    protected function processAnchor(Anchor $anchor, $value) {
        $anchor->addToClass(self::STYLE_ACTION);

        parent::processAnchor($anchor, $value);
    }

}
