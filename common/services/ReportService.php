<?php

declare(strict_types=1);

namespace common\services;

use common\models\Book;
use common\services\contracts\ReportServiceInterface;
use yii\db\Query;

class ReportService implements ReportServiceInterface
{
    /**
     * {@inheritdoc}
     */
    public function getAvailableYears(): array
    {
        return Book::find()
            ->select('year')
            ->distinct()
            ->where(['IS NOT', 'year', null])
            ->andWhere(['>', 'year', 0])
            ->orderBy(['year' => SORT_DESC])
            ->column();
    }

    /**
     * {@inheritdoc}
     */
    public function getTopAuthorsByYear(int $year, int $limit = 10): array
    {
        return (new Query())
            ->select([
                'author.id',
                'author.name',
                'COUNT(book.id) as book_count'
            ])
            ->from('{{%author}} author')
            ->innerJoin('{{%book_author}} ba', 'ba.author_id = author.id')
            ->innerJoin('{{%book}} book', 'book.id = ba.book_id')
            ->where(['book.year' => $year])
            ->groupBy(['author.id', 'author.name'])
            ->orderBy(['book_count' => SORT_DESC])
            ->limit($limit)
            ->all();
    }
}

