<?php

declare(strict_types=1);

namespace common\services\contracts;

interface ReportServiceInterface
{
    /**
     * @return array<int>
     */
    public function getAvailableYears(): array;

    /**
     * @return array<int, array<string, mixed>>
     */
    public function getTopAuthorsByYear(int $year, int $limit = 10): array;
}

