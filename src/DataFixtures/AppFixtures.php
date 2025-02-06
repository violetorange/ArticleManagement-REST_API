<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Factory\ApiTokenFactory;
use App\Factory\ArticleFactory;
use App\Factory\CategoryFactory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        // Пользователь
        $user = new User();
        $user->setEmail('test@test.com');
        $hashedPassword = $this->userPasswordHasher->hashPassword($user, 'test');
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $manager->flush();

        // Токен для пользователя
        ApiTokenFactory::createOne(['ownedBy' => $user]);

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
