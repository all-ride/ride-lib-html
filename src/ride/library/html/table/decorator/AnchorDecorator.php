<?php

namespace ride\library\html\table\decorator;

use ride\library\html\table\Cell;
use ride\library\html\table\Row;
use ride\library\html\Anchor;
use ride\library\reflection\ReflectionHelper;

/**
 * Abstract decorator to create an anchor from a cell value
 */
abstract class AnchorDecorator extends ValueDecorator {

    /**
     * Base href attribute for the anchor
     * @var string
     */
    protected $href;

    /**
     * The confirmation message
     * @var string
     */
    protected $message;

    /**
     * Constructs a new anchor decorator
     * @param string $href Base href attribute for the anchor
     * @param string $message The confirmation message
     * @param string|array|null $property Property of the value
     * @param \ride\library\decorator\Decorator $decorator Decorator for the
     * values
     * @param \ride\library\reflection\ReflectionHelper $reflectionHelper
     * Instance of the reflection helper to resolve properties
     * @return null
     */
    public function __construct($href, $message = null, $property = null, LibDecorator $decorator = null, ReflectionHelper $reflectionHelper = null) {
        parent::__construct($property, $decorator, $reflectionHelper);

        $this->href = $href;
        $this->message = $message;
    }

    /**
     * Decorates a table cell by setting an anchor to the cell based on the cell's value
     * @param \ride\library\html\table\Cell $cell Cell to decorate
     * @param \ride\library\html\table\Row $row Row which will contain the cell
     * @param int $rowNumber Number of the row in the table
     * @param array $remainingValues Array containing the values of the remaining rows of the table
     * @return null
     */
    public function decorate(Cell $cell, Row $row, $rowNumber, array $remainingValues) {
        $value = $this->getValue($cell);

        $label = $this->decorateValue($value);
        $href = $this->getHrefFromValue($value);

        $anchor = new Anchor($label, $href);

        $this->processAnchor($anchor, $value);

        $cell->setValue($anchor->getHtml());
    }

    /**
     * Gets the href attribute for the anchor
     * @param mixed $value Value of the cell
     * @return string Href attribute for the anchor
     */
    abstract protected function getHrefFromValue($value);

    /**
     * Hook to perform extra processing on the generated anchor
     * @param \ride\library\html\Anchor $anchor Generated anchor for the cell
     * @param mixed $value Value of the cell
     * @return null
     */
    protected function processAnchor(Anchor $anchor, $value) {
        $message = $this->processMessage($value);

        if ($message) {
            $anchor->setAttribute('onclick', 'return confirm(\'' . $message . '\');');
        }
    }

	/**
	 * Hook to process the message with the value of the cell
     * @param mixed $value Value of the cell
     * @return string|null The message to use for the confirmation, null for
     * no confirmation
     */
    protected function processMessage($value) {
        return $this->message;
    }

}
