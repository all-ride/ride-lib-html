<?php

namespace pallo\library\html;

/**
 * Pagination HTML element
 */
class Pagination extends AbstractElement {

    /**
     * The style class of the pagination element
     * @var string
     */
    const STYLE_PAGINATION = 'pagination';

    /**
     * Label for the gaps
     * @var string
     */
    private $ellipsis = '...';

    /**
     * Label for the next page anchor
     * @var string
     */
    private $nextLabel = '&raquo;';

    /**
     * Style class for the next page anchor
     * @var string
     */
    private $nextClass = 'next';

    /**
     * Flag to see if the next button should be shown
     * @var boolean
     */
    private $nextShow = true;

    /**
     * Label for the previous page anchor
     * @var string
     */
    private $previousLabel = '&laquo;';

    /**
     * Style class for the previous page anchor
     * @var string
     */
    private $previousClass = 'prev';

    /**
     * Flag to see if the previous button should be shown
     * @var boolean
     */
    private $previousShow = true;

    /**
     * Style class for the active item
     * @var string
     */
    private $activeClass = 'active';

    /**
     * Style class for a disabled item
     * @var string
     */
    private $disabledClass = 'disabled';

    /**
     * Href attribute for the page anchors
     * @var string
     */
    private $href;

    /**
     * Onclick attribute for the page anchors
     * @var string
     */
    private $onclick;

    /**
     * Total number of pages
     * @var int
     */
    private $pages;

    /**
     * Number of the current page
     * @var int
     */
    private $page;

    /**
     * Construct a new pagination HTML element
     * @param int $pages number of pages
     * @param int $page number of the current page
     * @return null
     */
    public function __construct($pages, $page) {
        parent::__construct('div');
        $this->setAttribute('class', self::STYLE_PAGINATION);

        $this->pages = $pages;
        $this->page = $page;
    }

    /**
     * Sets the href attribute for each page anchor
     * @param string $onclick
     * @return null
     */
    public function setHref($href) {
        $this->href = $href;
    }

    /**
     * Sets the onclick attribute for each page anchor
     * @param string $onclick
     * @return null
     */
    public function setOnclick($onclick) {
        $this->onclick = $onclick;
    }

    /**
     * Get the HTML of the page numbers
     * @return string
     */
    public function getAnchors() {
        $gaps = $this->getGaps();
        $gap = null;
        $currentGap = null;
        $anchors = array();

        if ($gaps) {
            $gap = array_pop($gaps);
        }


        if (!empty($this->page)) {
            $class = $this->previousClass;

            if ($this->page != 1) {
                $page = $this->page - 1;
            } else {
                $page = $this->page;
                $class .= ' ' . $this->disabledClass;
            }

            $anchors[] = $this->createAnchor($this->previousLabel, $page, $class);
        }

        for ($i = 1; $i <= $this->pages; $i++) {
            if ($i == $this->page) {
                $anchors[] = $this->createAnchor($i, $i, $this->activeClass);

                continue;
            }

            if ($currentGap != null && $currentGap['stop'] == $i) {
                $currentGap = null;
                if ($gaps) {
                    $gap = array_pop($gaps);
                }

                $anchor = new Anchor($this->ellipsis);
                $anchor->addToClass($this->disabledClass);

                $anchors[] = $anchor;
            } elseif ($currentGap != null && ($currentGap['start'] < $i && $i < $currentGap['stop'])) {
                continue;
            } else if ($gap != null && $gap['start'] == $i) {
                $currentGap = $gap;
                $gap = null;

                continue;
            }

            $anchors[] = $this->createAnchor($i, $i);
        }

        if (!empty($this->page)) {
            $class = $this->nextClass;

            if ($this->page != $this->pages) {
                $page = $this->page + 1;
            } else {
                $page = $this->page;
                $class = ' disabled';
            }

            $anchors[] = $this->createAnchor($this->nextLabel, $page, $class);
        }

        return $anchors;
    }

    /**
     * Gets the HTML of the content of this pagination element
     * @return string
     */
    protected function getHtmlContent() {
        $anchors = $this->getAnchors();

        $html = '<ul>' . "\n";

        foreach ($anchors as $anchor) {
            $html .= '<li' . ($anchor->getLabel() == $this->page ? ' class="' . $this->activeClass . '"' : '') . '>' . $anchor->getHtml() . '</li>';
        }

        $html .= '</ul>' . "\n";

        return $html;
    }

    /**
     * Get a gap array for the pages in this element
     * @return array Array containing arrays with the start and stop of the gaps in the pagination
     */
    private function getGaps() {
        $gaps = array();
        if ($this->pages <= 10) {
            return $gaps;
        }

        $gap = array();
        if ($this->page < 6) {
            $gap['start'] = 8;
            $gap['stop'] = $this->pages - 1;
        } elseif ($this->page > ($this->pages - 6)) {
            $gap['start'] = 3;
            $gap['stop'] = $this->pages - 7;
        } else {
            $gap['start'] = $this->page + 3;
            $gap['stop'] = $this->pages - 1;
            $gaps[] = $gap;

            $gap = array();
            $gap['start'] = 3;
            $gap['stop'] = $this->page - 2;
        }
        $gaps[] = $gap;

        return $gaps;
    }

    /**
     * Create a new page anchor
     * @param string $label label for the anchor
     * @param int $page page number to link to
     * @param string $class style class for the anchor
     * @return zibo\library\html\Anchor
     */
    private function createAnchor($label, $page, $class = null) {
        $anchor = new Anchor($label);

        if ($this->onclick) {
            $anchor->setAttribute('onclick', str_replace('%page%', $page, $this->onclick));
        }
        if ($this->href) {
            $anchor->setAttribute('href', str_replace('%page%', $page, $this->href));
        }
        if ($class) {
            $anchor->setClass($class);
        }

        return $anchor;
    }

}