<?php

namespace common\services\contracts;

interface ReportServiceInterface
{
    /**
     * Returns list of available years from books.
     *
     * @return array
     */
    public function getAvailableYears();

    /**
     * Returns top N authors by book count for the given year.
     *
     * @param int $year
     * @param int $limit
     *
     * @return array
     */
    public function getTopAuthorsByYear($year, $limit = 10);
}

