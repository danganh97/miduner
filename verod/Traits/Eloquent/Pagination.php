<?php

namespace Midun\Traits\Eloquent;

use Midun\Eloquent\Paginator;

trait Pagination
{
    /**
     * Pagination collection
     * 
     * @param int $perPage
     * @param mixed $column
     * 
     * @return array
     */
    public function paginate($perPage = null, $columns = null)
    {
        $this->setColumns($columns);
        $this->makePagination(true);

        $perPage = $this->getPerPage($perPage);
        $pageUrl = $this->getPageUrl();

        $total = $this->getTotalParentResources(
            $this->getFullSql()
        );

        $currentPage = Paginator::resolveCurrentPage();

        $skip = $this->getSkip(
            $currentPage,
            $perPage
        );

        $this->take($perPage);
        $this->skip($skip);

        return $this->makeRequest(
            $pageUrl,
            $total,
            $currentPage,
            $perPage
        );
    }

    /**
     * Get skip
     * 
     * @param int $currentPage
     * @param int $perPage
     * 
     * @return int
     */
    public function getSkip(int $currentPage, int $perPage)
    {
        return (int) $currentPage == 1 ? 0 : ($currentPage - 1) * $perPage;
    }

    /**
     * Get per page pagination
     * 
     * @param int $perPage
     * 
     * @return int
     */
    public function getPerPage(int $perPage)
    {
        return $perPage === null ? config('settings.pagination') : $perPage;
    }

    /**
     * Make pagination request
     * 
     * @param bool $status
     * 
     * @return void
     */
    public function makePagination(bool $status)
    {
        $this->isPagination = $status;
    }

    /**
     * Set selecting columns
     * 
     * @param null|array
     * 
     * @return array
     */
    public function setColumns($columns)
    {
        $this->columns = $columns === null ? $this->columns : $columns;
    }

    /**
     * Make request
     * @param string $pageUrl
     * @param int $total
     * @param int $currentPage
     * @param int $perPage
     * 
     * @return array
     */
    private function makeRequest($pageUrl, $total, $currentPage, $perPage)
    {
        list($lastPage, $firstPageUrl, $lastPageUrl, $nextPageUrl, $prevPageUrl) = Paginator::resolvePagePagination($total, $perPage, $pageUrl);
        $pagination = [
            "total" => (int) $total,
            "per_page" => (int) $perPage,
            "current_page" => (int) $currentPage,
            "last_page" => (int) $lastPage,
            "first_page_url" => $firstPageUrl,
            "last_page_url" => $lastPageUrl,
            "next_page_url" => $nextPageUrl,
            "prev_page_url" => $prevPageUrl,
            "path" => $pageUrl,
            "from" => Paginator::resolveFrom($currentPage, $perPage),
            "to" => Paginator::resolveTo($currentPage, $perPage, (int) $total, (int) $lastPage),
            "data" => $this->get()->toArray(),
        ];
        return $pagination;
    }

    /**
     * Get page url from parameters
     * 
     * @return string
     */
    private function getPageUrl()
    {
        $queryParams = http_build_query(request()->getQueryParams());
        $appUrl = config('app.url');
        $uri = explode('?', $_SERVER['REQUEST_URI']);
        $pageUrl = $appUrl . array_shift($uri) . '?' . $queryParams;
        return $pageUrl;
    }

    /**
     * Get parent resources
     * 
     * @param string $sql
     * 
     * @return int
     */
    private function getTotalParentResources(string $sql)
    {
        $connection = app()->make('connection')->getConnection();
        $object = $connection->prepare($sql);
        $object->execute();
        return $object->rowCount();
    }
}
