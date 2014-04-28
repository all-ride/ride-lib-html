<?php

namespace ride\library\decorator;

use ride\library\decorator\Decorator;
use ride\library\html\table\FormTable;
use ride\library\reflection\ReflectionHelper;

/**
 * Decorator to create an option field for a row of a FormTable
 */
class TableOptionDecorator implements Decorator {

    /**
     * Instance of the reflection helper
     * @var \ride\library\reflection\ReflectionHelper
     */
    protected $reflectionHelper;

    /**
     * Name of the value property
     * @var string
     */
    protected $property;

    /**
     * Constructs a new data option decorator
     * @param \ride\library\reflection\ReflectionHelper $reflectionHelper
     * Instance of the reflection helper
     * @param string $property Name of the value property
     * @return null
     */
    public function __construct(ReflectionHelper $reflectionHelper, $property = null) {
        $this->reflectionHelper = $reflectionHelper;
        $this->property = $property;
    }

    /**
     * Decorates the provided value into a id option for a row of a form table
     * @param mixed $value
     * @return string HTML for the id option of a row
     */
    public function decorate($value) {
        if ($this->property && !is_scalar($value)) {
            $value = $this->reflectionHelper->getProperty($value, $this->property);
        }

        return '<input type="checkbox" name="' . FormTable::FIELD_ID . '[]" value="' . $value . '" />';
    }

}
