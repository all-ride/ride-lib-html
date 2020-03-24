<?php

namespace ride\library\decorator;

use ride\library\reflection\ReflectionHelper;

/**
 * Decorator for property of a data object
 */
class PropertyDecorator implements Decorator {

    /**
     * Instance of the reflection helper
     * @var \ride\library\reflection\ReflectionHelper
     */
    protected $reflectionHelper;

    /**
     * Name of the property
     * @var string
     */
    protected $property;

    /**
     * Constructs a new data decorator
     * @param \ride\library\reflection\ReflectionHelper $reflectionHelper
     * @param string $property
     * @return null
     */
    public function __construct($property, ReflectionHelper $reflectionHelper = null) {
        if (!$reflectionHelper) {
            $reflectionHelper = new ReflectionHelper();
        }

        $this->reflectionHelper = $reflectionHelper;
        $this->property = $property;
    }

    /**
     * Decorates the value
     * @param mixed $value Value to decorate
     * @return string Decorated value
     */
    public function decorate($value) {
        if (!is_object($value)) {
            return $value;
        }

        return $this->reflectionHelper->getProperty($value, $this->property);
    }

}
