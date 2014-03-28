<?php

namespace ride\library\html\table\decorator;

/**
 * Container for the column decorators
 */
class ColumnDecorator {

    /**
     * Decorator of the column value
     * @var \ride\library\html\table\decorator\Decorator
     */
    private $valueDecorator;

    /**
     * Decorator of the column header
     * @var \ride\library\html\table\decorator\Decorator
     */
    private $headerDecorator;

    /**
     * Constructs a new column decorator container
     * @param \ride\library\html\table\decorator\Decorator $valueDecorator
     * Decorator for the column value
     * @param \ride\library\html\table\decorator\Decorator $headerDecorator
     * Decorator for the column header
     * @return null
     */
    public function __construct(Decorator $valueDecorator, Decorator $headerDecorator = null) {
        $this->valueDecorator = $valueDecorator;
        $this->headerDecorator = $headerDecorator;
    }

    /**
     * Gets the decorator for the column value
     * @return \ride\library\html\table\decorator\Decorator
     */
    public function getValueDecorator() {
        return $this->valueDecorator;
    }

    /**
     * Gets the decorator for the column header
     * @return \ride\library\html\table\decorator\Decorator
     */
    public function getHeaderDecorator() {
        return $this->headerDecorator;
    }

}