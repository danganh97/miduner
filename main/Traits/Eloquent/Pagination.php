<?php

namespace Main\Traits\Eloquent;

use Main\Eloquent\Paginator;

trait Pagination
{
    public function paginate($perPage = null, $columns = null)
    {
        $this->columns = $columns === null ? $this->columns : $columns;
        $this->isPagination = true;
        $perPage = $perPage === null ? config('settings.pagination') : $perPage;
        $pageUrl = $this->getPageUrl();
        $total = $this->getTotalParentResources($this->getFullSql());
        $currentPage = Paginator::resolveCurrentPage();
        $skip = (int) $currentPage == 1 ? 0 : ($currentPage - 1) * $perPage;
        $this->take($perPage);
        $this->skip($skip);
        return $this->makeRequest($pageUrl, $total, $currentPage, $perPage);

    }

    /**
     * Make request
     * @param string $pageUrl
     * @param int $total
     * @param int $currentPage
     * @param int $perPage
     * 
     * @return 
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
            "to" => Paginator::resolveTo($currentPage, $perPage),
            "data" => $this->get(),
        ];
        return $pagination;
    }

    /**
     * Get page url from parameters
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
     */
    private function getTotalParentResources(string $sql)
    {
        $connection = app()->make('mysqlConnection');
        $object = $connection->prepare($sql);
        $object->execute();
        return $object->rowCount();
    }
}
