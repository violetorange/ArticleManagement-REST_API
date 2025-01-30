<?php

namespace App\DataFixtures;

use App\Factory\ArticleFactory;
use App\Factory\CategoryFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Категории
        $titles = [
            'Электроника',
            'Одежда',
            'Книги',
            'Дом и сад',
            'Путешествия',
        ];

        foreach ($titles as $title) {
            CategoryFactory::createOne(['title' => $title]);
        }

        // Статьи
        ArticleFactory::createMany(24, function () {
            return [
                'category' => CategoryFactory::random(),
            ];
        });
    }
}
