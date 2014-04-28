<?php

namespace ride\library\html\table;

/**
 * Helper to process a request with a form table
 */
class TableHelper {

    /**
     * Argument name of the order method
     * @var string
     */
    const PARAMETER_ORDER_METHOD = 'order';

    /**
     * Argument name of the order direction
     * @var string
     */
    const PARAMETER_ORDER_DIRECTION = 'direction';

    /**
     * Argument name of the page
     * @var string
     */
    const PARAMETER_PAGE = 'page';

    /**
     * Argument name of the number of rows per page
     * @var string
     */
    const PARAMETER_ROWS = 'rows';

    /**
     * Argument name of the search query
     * @var string
     */
    const PARAMETER_SEARCH_QUERY = 'search';

    /**
     * Generates a URL for the table
     * @param \ride\library\html\table\FormTable $table
     * @param string $baseUrl
     * @param int $page The current page
     * @param int $rowsPerPage Number or rows to display on each page
     * @param string $searchQuery Value for the search query
     * @param string $orderMethod Name of the order method to use
     * @param string $orderDirection Name of the order direction
     * @return string
     */
    public function getUrl(FormTable $table, $baseUrl, $page = null, $rowsPerPage = null, $searchQuery = null, $orderMethod = null, $orderDirection = null) {
        $parameters = array();

        if ($table->getPaginationOptions()) {
            $parameters[] = self::PARAMETER_PAGE . '=' . $page;
            $parameters[] = self::PARAMETER_ROWS . '=' . $rowsPerPage;
        }

        if ($table->hasOrderMethods() && ($orderMethod || $orderDirection)) {
            $parameters[] = self::PARAMETER_ORDER_METHOD . '=' . urlencode($orderMethod);
            $parameters[] = self::PARAMETER_ORDER_DIRECTION . '=' . strtolower($orderDirection);
        }

        if ($table->hasSearch() && $searchQuery) {
            $parameters[] = self::PARAMETER_SEARCH_QUERY . '=' . urlencode($searchQuery);
        }

        if ($parameters) {
            $baseUrl .= '?' . implode('&', $parameters);
        }

        return $baseUrl;
    }

    /**
     * Generates a URL for the table based on the arguments in the table
     * @param \ride\library\html\table\FormTable $table
     * @param string $baseUrl
     * @return string URL of the table
     */
    public function getUrlFromTable(FormTable $table, $baseUrl) {
        $page = $table->getPage();
        $rowsPerPage = $table->getRowsPerPage();
        $searchQuery = $table->getSearchQuery();
        $orderMethod = $table->getOrderMethod();
        $orderDirection = $table->getOrderDirection();

        return $this->getUrl($table, $baseUrl, $page, $rowsPerPage, $searchQuery, $orderMethod, $orderDirection);
    }

    /**
     * Updates the option URLs of the table
     * @param \ride\library\html\table\FormTable $table
     * @param string $url URL of the table
     * @return null
     */
    public function setUrlToTable(FormTable $table, $url) {
        $table->setFormUrl($url);

        if ($table->hasPaginationOptions()) {
            $table->setPaginationUrl(str_replace(self::PARAMETER_PAGE . '=' . $table->getPage(), self::PARAMETER_PAGE . '=%page%', $url));
        }

        if ($table->hasOrderMethods()) {
            $table->setOrderDirectionUrl(str_replace(self::PARAMETER_ORDER_DIRECTION. '=' . strtolower($table->getOrderDirection()), self::PARAMETER_ORDER_DIRECTION . '=%direction%', $url));
        }
    }

    /**
     * Gets the table arguments from the argument array
     * @param array $parameters Arguments array with the name as key and the
     * argument as value
     * @param int $page Current page
     * @param int $rowsPerPage Number or rows to display on each page
     * @param string $searchQuery Value for the search query
     * @param string $orderMethod Name of the order method to use
     * @param string $orderDirection Name of the order direction
     * @return null
     */
    public function getArgumentsFromArray($parameters, &$page, &$rowsPerPage, &$searchQuery = null, &$orderMethod = null, &$orderDirection = null) {
        if (isset($parameters[self::PARAMETER_PAGE])) {
            $page = $parameters[self::PARAMETER_PAGE];
        }

        if (isset($parameters[self::PARAMETER_ROWS])) {
            $rowsPerPage = $parameters[self::PARAMETER_ROWS];
        }

        if (isset($parameters[self::PARAMETER_ORDER_METHOD])) {
            $orderMethod = urldecode($parameters[self::PARAMETER_ORDER_METHOD]);
        }

        if (isset($parameters[self::PARAMETER_ORDER_DIRECTION])) {
            $orderDirection = $parameters[self::PARAMETER_ORDER_DIRECTION];
        }

        if (isset($parameters[self::PARAMETER_SEARCH_QUERY])) {
            $searchQuery = urldecode($parameters[self::PARAMETER_SEARCH_QUERY]);
        }
    }

    /**
     * Sets the table arguments
     * @param \ride\library\html\table\FormTable $table
     * @param int $page The current page
     * @param int $rowsPerPage Number or rows to display on each page
     * @param string $searchQuery Value for the search query
     * @param string $orderMethod Name of the order method to use
     * @param string $orderDirection Name of the order direction
     * @return null
     */
    public function setArgumentsToTable(FormTable $table, $page, $rowsPerPage, $searchQuery = null, $orderMethod = null, $orderDirection = null) {
        if ($table->hasPaginationOptions()) {
            if ($rowsPerPage && $table->getRowsPerPage() != $rowsPerPage) {
                $table->setRowsPerPage($rowsPerPage);
            }
            if ($page) {
                $table->setPage($page);
            }
        }

        if ($table->hasOrderMethods()) {
            if ($orderMethod) {
                $table->setOrderMethod($orderMethod);
            }
            if ($orderDirection) {
                $table->setOrderDirection($orderDirection);
            }
        }

        if ($table->hasSearch()) {
            $table->setSearchQuery($searchQuery);
        }
    }

    /**
     * Checks if the table arguments have changed
     * @param \ride\library\html\table\FormTable $table
     * @param integer $page The current page
     * @param integer $rowsPerPage Number or rows to display on each page
     * @param string $searchQuery Value for the search query
     * @param string $orderMethod Name of the order method to use
     * @param string $orderDirection Name of the order direction
     * @return boolean
     */
    public function isTableChanged(FormTable $table, &$page = null, &$rowsPerPage = null, &$searchQuery = null, &$orderMethod = null, &$orderDirection = null) {
        $isTableChanged = false;

        if ($table->hasPaginationOptions()) {
            if ($table->getPage() != $page) {
                $isTableChanged = true;

                $page = $table->getPage();
            }
            if ($table->getRowsPerPage() != $rowsPerPage) {
                $isTableChanged = true;

                $rowsPerPage = $table->getRowsPerPage();
                $page = 1;
            }
        }

        if ($table->hasOrderMethods()) {
            if ($table->getOrdermethod() != $orderMethod) {
                $isTableChanged = true;

                $orderMethod = $table->getOrderMethod();
            }

            if ($table->getOrderDirection() != $orderDirection) {
                $isTableChanged = true;

                $orderDirection = strtolower($table->getOrderDirection());
            }
        }

        if ($table->hasSearch() && $table->getSearchQuery() != $searchQuery) {
            $isTableChanged = true;

            $searchQuery = $table->getSearchQuery();
            $page = 1;
        }

        return $isTableChanged;
    }

}
