<?php

use Yii;
use yii\db\Migration;

class m250000_000005_seed_dummy_data extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        if (!defined('YII_ENV') || YII_ENV !== 'dev') {
            return;
        }

        $time = time();

        $authors = [
            ['name' => 'Лев Толстой', 'created_at' => $time, 'updated_at' => $time],
            ['name' => 'Фёдор Достоевский', 'created_at' => $time, 'updated_at' => $time],
            ['name' => 'Антон Чехов', 'created_at' => $time, 'updated_at' => $time],
            ['name' => 'Александр Пушкин', 'created_at' => $time, 'updated_at' => $time],
            ['name' => 'Иван Тургенев', 'created_at' => $time, 'updated_at' => $time],
            ['name' => 'George Orwell', 'created_at' => $time, 'updated_at' => $time],
            ['name' => 'Jane Austen', 'created_at' => $time, 'updated_at' => $time],
            ['name' => 'J.K. Rowling', 'created_at' => $time, 'updated_at' => $time],
            ['name' => 'Николай Гоголь', 'created_at' => $time, 'updated_at' => $time],
            ['name' => 'Михаил Лермонтов', 'created_at' => $time, 'updated_at' => $time],
            ['name' => 'Стивен Кинг', 'created_at' => $time, 'updated_at' => $time],
            ['name' => 'Джордж Мартин', 'created_at' => $time, 'updated_at' => $time],
        ];

        $authorIds = [];
        foreach ($authors as $author) {
            $this->insert('{{%author}}', $author);
            $authorIds[] = Yii::$app->db->getLastInsertID();
        }

        $books = [
            [
                'name' => 'Война и мир',
                'description' => 'Эпическое произведение о России эпохи наполеоновских войн. Один из величайших романов мировой литературы.',
                'year' => 1869,
                'isbn' => '978-5-17-105151-7',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[0]],
            ],
            [
                'name' => 'Анна Каренина',
                'description' => null,
                'year' => 1869,
                'isbn' => '978-5-17-105152-4',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[0]],
            ],
            [
                'name' => 'Смерть Ивана Ильича',
                'description' => 'Повесть о поиске смысла жизни перед лицом смерти.',
                'year' => 1869,
                'isbn' => null,
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[0]],
            ],
            [
                'name' => 'Преступление и наказание',
                'description' => 'Психологический роман о студенте, совершившем убийство. Вопросы морали и совести.',
                'year' => 1866,
                'isbn' => '978-5-17-105153-1',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[1]],
            ],
            [
                'name' => 'Идиот',
                'description' => 'Роман о князе Мышкине, "положительно прекрасном человеке".',
                'year' => 1869,
                'isbn' => '978-5-17-105154-8',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[1]],
            ],
            [
                'name' => 'Братья Карамазовы',
                'description' => null,
                'year' => 1880,
                'isbn' => '978-5-17-105155-5',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[1]],
            ],
            [
                'name' => 'Вишнёвый сад',
                'description' => null,
                'year' => 1880,
                'isbn' => null,
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[2]],
            ],
            [
                'name' => 'Чайка',
                'description' => 'Пьеса о творчестве и любви.',
                'year' => 1880,
                'isbn' => null,
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[2]],
            ],
            [
                'name' => 'Евгений Онегин',
                'description' => 'Роман в стихах, "энциклопедия русской жизни".',
                'year' => 1833,
                'isbn' => '978-5-17-105156-2',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[3]],
            ],
            [
                'name' => 'Руслан и Людмила',
                'description' => 'Поэма-сказка о приключениях богатыря Руслана.',
                'year' => 1833,
                'isbn' => '978-5-17-105157-9',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[3]],
            ],
            [
                'name' => 'Отцы и дети',
                'description' => 'Роман о конфликте поколений и нигилизме.',
                'year' => 1866,
                'isbn' => '978-5-17-105158-6',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[4]],
            ],
            [
                'name' => '1984',
                'description' => 'Дистопический роман о тоталитарном обществе под контролем Большого Брата.',
                'year' => 1949,
                'isbn' => '978-0-452-28423-4',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[5]],
            ],
            [
                'name' => 'Скотный двор',
                'description' => 'Сатирическая повесть-аллегория на революцию 1917 года.',
                'year' => 1945,
                'isbn' => '978-0-452-28424-1',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[5]],
            ],
            [
                'name' => 'Гордость и предубеждение',
                'description' => 'Классический роман о любви и социальных предрассудках в Англии XIX века.',
                'year' => 1833,
                'isbn' => '978-0-14-143951-8',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[6]],
            ],
            [
                'name' => 'Гарри Поттер и философский камень',
                'description' => null,
                'year' => 1997,
                'isbn' => '978-0-7475-3269-6',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[7]],
            ],
            [
                'name' => 'Гарри Поттер и Тайная комната',
                'description' => 'Вторая книга о приключениях юного волшебника.',
                'year' => 1998,
                'isbn' => '978-0-7475-3849-0',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[7]],
            ],
            [
                'name' => 'Капитанская дочка',
                'description' => 'Исторический роман о пугачёвском восстании.',
                'year' => 1833,
                'isbn' => '978-5-17-105159-3',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[3]],
            ],
            [
                'name' => 'Воскресение',
                'description' => 'Последний большой роман Толстого о духовном возрождении.',
                'year' => 1869,
                'isbn' => '978-5-17-105160-0',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[0]],
            ],
            [
                'name' => 'Хаджи-Мурат',
                'description' => 'Повесть о кавказском герое.',
                'year' => 1869,
                'isbn' => '978-5-17-105161-7',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[0]],
            ],
            [
                'name' => 'Бесы',
                'description' => 'Роман о революционном движении в России.',
                'year' => 1869,
                'isbn' => '978-5-17-105162-4',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[1]],
            ],
            [
                'name' => 'Подросток',
                'description' => 'Роман о духовном становлении молодого человека.',
                'year' => 1875,
                'isbn' => '978-5-17-105163-1',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[1]],
            ],
            [
                'name' => 'Три сестры',
                'description' => 'Пьеса о трёх сёстрах, мечтающих о Москве.',
                'year' => 1896,
                'isbn' => '978-5-17-105164-8',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[2]],
            ],
            [
                'name' => 'Дядя Ваня',
                'description' => 'Пьеса о жизни в русской усадьбе.',
                'year' => 1896,
                'isbn' => '978-5-17-105165-5',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[2]],
            ],
            [
                'name' => 'Мёртвые души',
                'description' => 'Поэма о путешествии Чичикова по России и его скупке мёртвых душ.',
                'year' => 1842,
                'isbn' => '978-5-17-105166-2',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[8]],
            ],
            [
                'name' => 'Ревизор',
                'description' => 'Комедия о чиновниках провинциального города и мнимом ревизоре.',
                'year' => 1842,
                'isbn' => '978-5-17-105167-9',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[8]],
            ],
            [
                'name' => 'Герой нашего времени',
                'description' => 'Роман о "лишнем человеке" Печорине.',
                'year' => 1842,
                'isbn' => '978-5-17-105168-6',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[9]],
            ],
            [
                'name' => 'Гарри Поттер и узник Азкабана',
                'description' => 'Третья книга о приключениях юного волшебника.',
                'year' => 1997,
                'isbn' => '978-0-7475-4215-5',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[7]],
            ],
            [
                'name' => 'Гарри Поттер и Кубок огня',
                'description' => 'Четвёртая книга о приключениях юного волшебника.',
                'year' => 1997,
                'isbn' => '978-0-7475-4624-5',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[7]],
            ],
            [
                'name' => 'Сияние',
                'description' => 'Ужасы о писателе, сходящем с ума в заснеженном отеле.',
                'year' => 1997,
                'isbn' => '978-0-385-12167-5',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[10]],
            ],
            [
                'name' => 'Игра престолов',
                'description' => 'Первый роман эпической фэнтези-саги "Песнь Льда и Пламени".',
                'year' => 1997,
                'isbn' => '978-0-553-10354-0',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[11]],
            ],
            [
                'name' => 'Гарри Поттер и Орден Феникса',
                'description' => 'Пятая книга о приключениях юного волшебника.',
                'year' => 1998,
                'isbn' => '978-0-7475-5100-6',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[7]],
            ],
            [
                'name' => 'Оно',
                'description' => 'Роман ужасов о группе друзей, сражающихся со злом.',
                'year' => 1998,
                'isbn' => '978-0-670-81302-5',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[10]],
            ],
            [
                'name' => 'Битва королей',
                'description' => 'Второй том "Песни Льда и Пламени".',
                'year' => 1998,
                'isbn' => '978-0-553-10803-3',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[11]],
            ],
            [
                'name' => 'Буря мечей',
                'description' => 'Третий том "Песни Льда и Пламени".',
                'year' => 2000,
                'isbn' => '978-0-553-10663-3',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[11]],
            ],
            [
                'name' => 'Кладбище домашних животных',
                'description' => 'Роман ужасов о семье, переехавшей в новый дом.',
                'year' => 2000,
                'isbn' => '978-0-670-02027-9',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[10]],
            ],
            [
                'name' => 'Гарри Поттер и Принц-полукровка',
                'description' => 'Шестая книга о приключениях юного волшебника.',
                'year' => 2000,
                'isbn' => '978-0-7475-8108-9',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[7]],
            ],
            [
                'name' => 'Пир стервятников',
                'description' => 'Четвёртый том "Песни Льда и Пламени".',
                'year' => 2005,
                'isbn' => '978-0-553-80150-7',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[11]],
            ],
            [
                'name' => 'Танец с драконами',
                'description' => 'Пятый том "Песни Льда и Пламени".',
                'year' => 2005,
                'isbn' => '978-0-553-80147-7',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[11]],
            ],
            [
                'name' => 'Зелёная миля',
                'description' => 'Роман о надзирателе тюрьмы и его отношениях с заключённым.',
                'year' => 2005,
                'isbn' => '978-0-451-19261-7',
                'cover_path' => null,
                'created_at' => $time,
                'updated_at' => $time,
                'author_ids' => [$authorIds[10]],
            ],
        ];

        foreach ($books as $book) {
            $authorIdsForBook = $book['author_ids'];
            unset($book['author_ids']);

            $this->insert('{{%book}}', $book);
            $bookId = Yii::$app->db->getLastInsertID();

            foreach ($authorIdsForBook as $authorId) {
                $this->insert('{{%book_author}}', [
                    'book_id' => $bookId,
                    'author_id' => $authorId,
                ]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        if (!defined('YII_ENV') || YII_ENV !== 'dev') {
            return;
        }

        $this->delete('{{%book_author}}');
        $this->delete('{{%book}}');
        $this->delete('{{%author}}');
    }
}

