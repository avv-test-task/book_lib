<?php

declare(strict_types=1);

namespace common\services\contracts;

interface ReportServiceInterface
{
    /**
     * Returns list of available years from books.
     *
     * @return array<int>
     */
    public function getAvailableYears(): array;

    /**
     * Returns top N authors by book count for the given year.
     *
     * @param int $year
     * @param int $limit
     *
     * @return array<int, array<string, mixed>>
     */
    public function getTopAuthorsByYear(int $year, int $limit = 10): array;
}

