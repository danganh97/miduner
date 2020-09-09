<?php

namespace Midun\Eloquent;

final class Paginator
{
    /**
     * Get current page
     * 
     * @return int
     */
    public static function resolveCurrentPage()
    {
        return request()->get('page') ?: 1;
    }

    /**
     * Resolve page pagination
     * @param int $total
     * @param int $perPage
     * @param string $pageUrl
     * 
     * @return array
     */
    public static function resolvePagePagination($total, $perPage, $pageUrl)
    {
        $lastPage = self::resolveLastPage($total, $perPage);
        $currentPage = self::resolveCurrentPage();
        $firstPage = self::resolveFirstPage();
        $nextPage = self::resolveNextPage($currentPage, $lastPage);
        $prevPage = self::resolvePreviousPage($currentPage, $lastPage);
        return self::buildPagination($lastPage, $firstPage, $nextPage, $prevPage, $currentPage, $pageUrl);
    }

    /**
     * Build endpoint pagination
     * @param int $lastPage
     * @param int $firstPage
     * @param int $nextPage
     * @param int $prevPage
     * @param int $currentPage
     * @param string $pageUrl
     * 
     * @return array
     */
    public static function buildPagination($lastPage, $firstPage, $nextPage, $prevPage, $currentPage, $pageUrl)
    {
        $parseUrl = parse_url($pageUrl);

        $query = isset($parseUrl['query']) ? $parseUrl['query'] : "page={$currentPage}";

        parse_str($query, $params);

        foreach ($params as $param => $value) {
            if ($param == 'page') {
                $hasPage = true;
                $params['page'] = $currentPage;
            } else {
                $params[$param] = $value;
            }
            $params = isset($hasPage) ? $params : array_merge($params, ['page' => $currentPage]);

            $pageUrl = explode('?', $pageUrl)[0] . '?' . http_build_query($params);
        }

        $firstPageUrl = str_replace('page=' . $currentPage, "page={$firstPage}", $pageUrl);
        $nextPageUrl = $nextPage === null ? null : str_replace('page=' . $currentPage, "page={$nextPage}", $pageUrl);
        $lastPageUrl = $lastPage == 0 ? null : str_replace('page=' . $currentPage, "page={$lastPage}", $pageUrl);
        $prevPageUrl = $prevPage === null ? null : str_replace('page=' . $currentPage, "page={$prevPage}", $pageUrl);
        return [
            $lastPage,
            $firstPageUrl,
            $lastPageUrl,
            $nextPageUrl,
            $prevPageUrl
        ];
    }

    /**
     * Get last page number
     * @param int $total
     * @param int $perPage
     * 
     * @return int
     */
    public static function resolveLastPage($total, $perPage)
    {
        $lastPage = (string) $total / $perPage;
        $lastPage = explode('.', $lastPage);
        $lastPage = count($lastPage) > 1 ? array_shift($lastPage) + 1 : array_shift($lastPage);
        return $lastPage;
    }

    /**
     * Get first page number
     * 
     * @return int
     */
    public static function resolveFirstPage()
    {
        return 1;
    }

    /**
     * Get next page number
     * 
     * @param int $currentPage
     * @param int $lastPage
     * 
     * @return int/null
     */
    public static function resolveNextPage($currentPage, $lastPage)
    {
        return $currentPage + 1 <= $lastPage ? $currentPage + 1 : null;
    }

    /**
     * Get previous page number
     * 
     * @param int $currentPage
     * 
     * @return int/null
     */
    public static function resolvePreviousPage($currentPage)
    {
        return $currentPage > 1 ? $currentPage - 1 : null;
    }

    /**
     * Resolve from item
     * 
     * @param int $currentPage
     * @param int $perPage
     * 
     * @return int
     */
    public static function resolveFrom($currentPage, $perPage)
    {
        return (int) ($currentPage - 1) * $perPage + 1;
    }

    /**
     * Resolve to item
     * 
     * @param int $currentPage
     * @param int $perPage
     * @param int $total
     * @param int $lastPage
     * 
     * @return int
     */
    public static function resolveTo($currentPage, $perPage, int $total, int $lastPage)
    {
        if ($currentPage == $lastPage) {
            return $total;
        }
        return (int) ($currentPage - 1) * $perPage + $perPage;
    }
}
