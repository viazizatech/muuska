<?php
namespace muuska\url\pagination;

interface PaginationUrl
{
    /**
     * @param int $page
     * @return string
     */
    public function createPageUrl($page);
}