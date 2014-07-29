<?php

namespace ride\library\html\table\decorator;

use ride\library\html\exception\TableException;
use ride\library\html\table\decorator\Decorator;
use ride\library\html\table\Cell;
use ride\library\html\table\Row;
use ride\library\html\Anchor;
use ride\library\html\Image;
use ride\library\image\exception\ImageException;
use ride\library\image\ImageUrlGenerator;
use ride\library\reflection\ReflectionHelper;

/**
 * Decorator for a generic data object
 */
class DataDecorator implements Decorator {

    /**
     * Path to the default data image
     * @var string
     */
    const DEFAULT_IMAGE = 'img/data.png';

    /**
     * Style class for the image of the data
     * @var string
     */
    const STYLE_IMAGE = 'data';

    /**
     * Instance of the reflection helper
     * @var \ride\library\reflection\ReflectionHelper
     */
    protected $reflectionHelper;

    /**
     * Generator for images
     * @var \ride\library\image\ImageUrlGenerator
     */
    protected $imageUrlGenerator;

    /**
     * URL where the title of the data will point to
     * @var string
     */
    protected $action;

    /**
     * Path to the default image of the data
     * @var string
     */
    protected $defaultImage;

    /**
     * Name of the id property
     * @var string
     */
    protected $propertyId;

    /**
     * Name of the title property
     * @var string
     */
    protected $propertyTitle;

    /**
     * Name of the teaser property
     * @var string
     */
    protected $propertyTeaser;

    /**
     * Name of the image property
     * @var string
     */
    protected $propertyImage;

    /**
     * Constructs a new data decorator
     * @param string $action URL where the title of the data will point to. Use
     * %id% as placeholder for the primary key of the data
     * @param \ride\\libraryimage\ImageUrlGenerator $imageUrlGenerator URL generator for images
     * @param string $defaultImage Path to the default image of the data
     * @return null
     */
    public function __construct(ReflectionHelper $reflectionHelper, $action = null, ImageUrlGenerator $imageUrlGenerator = null, $defaultImage = null) {
        if (!$defaultImage) {
            $defaultImage = self::DEFAULT_IMAGE;
        }

        $this->reflectionHelper = $reflectionHelper;
        $this->action = $action;

        $this->imageUrlGenerator = $imageUrlGenerator;
        $this->defaultImage = $defaultImage;

        $this->propertyId = 'id';
        $this->propertyImage = 'image';
    }

    /**
     * Maps a property name of the data to a field for the decorator
     * @param string $field Name of the field (id, title, teaser or image)
     * @param string $property Name of the property in the data
     * @return null
     */
    public function mapProperty($field, $property) {
        switch ($field) {
            case 'id':
                $this->propertyId = $property;

                return;
            case 'title':
                $this->propertyTitle = $property;

                return;
            case 'teaser':
                $this->propertyTeaser = $property;

                return;
            case 'image':
                $this->propertyImage = $property;

                return;
            default:
                throw new TableException('Could not set property: invalid field provided. Try id, title, teaser or image');
        }
    }

    /**
     * Decorates the data in the cell
     * @param \ride\library\html\table\Cell $cell Cell to decorate
     * @param \ride\library\html\table\Row $row Row containing the cell
     * @param int $rowNumber Number of the current row
     * @param array $remainingValues Array with the values of the remaining rows of the table
     * @return null
     */
    public function decorate(Cell $cell, Row $row, $rowNumber, array $remainingValues) {
        $data = $cell->getValue();

        $url = $this->getDataUrl($data);
        $title = $this->getDataTitle($data);
        if (!$title) {
            $title = 'Data';
        }

        $teaser = $this->getDataTeaser($data);

        $value = '';

        if ($this->imageUrlGenerator) {
            $image = $this->getDataImage($data);

            $value .= $this->getImageHtml($image);
        }

        if ($url) {
            $anchor = new Anchor($title, $url);

            $value .= $anchor->getHtml();
        } else {
            $value .= $title;
        }

        if ($teaser) {
            $value .= '<div class="info">' . $teaser . '</div>';
        }

        $cell->setValue($value);
    }

    /**
     * Gets the id of the data
     * @param mixed $data Instance of the data
     * @return string|null
     */
    protected function getDataId($data) {
        if ($this->propertyId) {
            return $this->reflectionHelper->getProperty($data, $this->propertyId);
        }

        return null;
    }

    /**
     * Gets the title of the data
     * @param mixed $data Instance of the data
     * @return string|null
     */
    protected function getDataTitle($data) {
        if ($this->propertyTitle) {
            return $this->reflectionHelper->getProperty($data, $this->propertyTitle);
        }

        if (is_scalar($data) || (is_object($data) && method_exists($data, '__toString'))) {
            return (string) $data;
        } elseif (is_array($data)) {
            return 'array';
        } elseif (is_object($data)) {
            return get_class($data);
        }

        return null;
    }

    /**
     * Gets the teaser of the data
     * @param mixed $data Instance of the data
     * @return string|null
     */
    protected function getDataTeaser($data) {
        if ($this->propertyTeaser) {
            return $this->reflectionHelper->getProperty($data, $this->propertyTeaser);
        }

        return null;
    }

    /**
     * Gets the image for the data
     * @param mixed $data Instance of the data
     * @return string|null Path to the image of the data
     */
    protected function getDataImage($data) {
        if ($this->propertyImage) {
            return $this->reflectionHelper->getProperty($data, $this->propertyImage);
        }

        return null;
    }

    /**
     * Gets the URL for the data
     * @param mixed $data Instance of the data
     * @return string|null
     */
    protected function getDataUrl($data) {
        if (!$this->action) {
            return null;
        }

        $id = $this->getDataId($data);
        if (!$id) {
            return null;
        }

        $url = $this->action;
        $url = str_replace('%id%', $id, $url);
        $url = str_replace('%25id%25', $id, $url);

        return $url;
    }

    /**
     * Gets the HTML for the image of the data
     * @param string $image Path to the image
     * @return string
     */
    protected function getImageHtml($image) {
        if (!$image) {
            $image = $this->defaultImage;
        }

        try {
            $url = $this->imageUrlGenerator->generateUrl($image, 'crop', array(
                'width' => 50,
                'height' => 50,
            ));

            $image = new Image($url);
            $image->addToClass(self::STYLE_IMAGE);

            return $image->getHtml();
        } catch (ImageException $exception) {
            return '<span style="color: red;">' . $exception->getMessage() . '</span>';
        }
    }

}
