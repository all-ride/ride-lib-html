<?php

namespace ride\library\html\table\decorator;

use ride\library\decorator\Decorator as LibDecorator;
use ride\library\html\exception\TableException;
use ride\library\html\table\Cell;
use ride\library\html\table\Row;
use ride\library\reflection\ReflectionHelper;

/**
 * Generic decorator for a scalar value, an object value or an array value
 */
class ValueDecorator implements Decorator {

    /**
     * Name of the property
     * @var string|array
     */
    protected $property;

    /**
     * Decorator for the values
     * @var \ride\library\decorator\Decorator
     */
    protected $decorator;

    /**
     * Helper for value handling
     * @var \ride\library\reflection\ReflectionHelper
     */
    protected $reflectionHelper;

    /**
     * Style class for the decorated cell
     * @var string
     */
    protected $class;

    /**
     * Constructs a new decorator
     * @param string|array|null $property Property of the value
     * @param \ride\library\decorator\Decorator $decorator Decorator for the
     * values
     * @param \ride\library\reflection\ReflectionHelper $reflectionHelper
     * Instance of the reflection helper to resolve properties
     * @return null
     */
    public function __construct($property = null, LibDecorator $decorator = null, ReflectionHelper $reflectionHelper = null) {
        if (!$reflectionHelper) {
            $reflectionHelper = new ReflectionHelper();
        }

        $this->property = $property;
        $this->decorator = $decorator;
        $this->reflectionHelper = $reflectionHelper;
        $this->class = null;
    }

    /**
     * Sets the style class the for the decorated cells
     * @param string $class
     * @return null
     */
    public function setCellClass($class) {
        $this->class = $class;
    }

    /**
     * Decorates the value of the cell through the ZiboValueDecorator
     * @param \ride\library\html\table\Cell $cell Cell to decorate
     * @param \ride\library\html\table\Row $row Row which will contain the cell
     * @param int $rowNumber Number of the row in the table
     * @param array $remainingValues Array containing the values of the remaining rows of the table
     * @return null
     */
    public function decorate(Cell $cell, Row $row, $rowNumber, array $remainingValues) {
        $value = $this->getValue($cell);

        $value = $this->decorateValue($value);

        $cell->setValue($value);
        if ($this->class) {
            $cell->addToClass($this->class);
        }
    }

    /**
     * Perform the actual decorating of the value
     * @param mixed $value Value to decorate
     * @return string Decorated value
     */
    protected function decorateValue($value) {
        if ($this->decorator) {
            $value = $this->decorator->decorate($value);
        }

        if ($value === null) {
            return '';
        }

        if (is_scalar($value) || (is_object($value) && method_exists($value, '__toString'))) {
            return (string) $value;
        }

        if (is_array($value)) {
            $values = array();

            foreach ($value as $v) {
                $values[] = $this->decorateValue($v);
            }

            return implode(', ', $values);
        }

        if (is_object($value)) {
            $value = get_class($value);
        }

        throw new TableException('Could not decorate value: value unsupported for display');
    }

    /**
     * Gets the value from the cell
     * @param \ride\library\html\table\Cell $cell
     * @return mixed
     */
    protected function getValue(Cell $cell) {
        $value = $cell->getValue();
        if (!$this->property) {
            return $value;
        }

        return $this->reflectionHelper->getProperty($value, $this->property);
    }

}
