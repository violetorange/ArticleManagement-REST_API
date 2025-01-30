<?php

namespace App\Factory;

use App\Entity\Article;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Zenstruck\Foundry\Persistence\PersistentProxyObjectFactory;

/**
 * @extends PersistentProxyObjectFactory<Article>
 */
final class ArticleFactory extends PersistentProxyObjectFactory
{
    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#factories-as-services
     *
     * @todo inject services if required
     */
    public function __construct()
    {
    }

    public static function class(): string
    {
        return Article::class;
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#model-factories
     *
     * @todo add your default values here
     */
    protected function defaults(): array|callable
    {
        return [
            'content' => self::faker()->text(),
            'image' => self::faker()->text(10),
            'isPublished' => true,
            'title' => self::faker()->text(15),
            'imageFile' => $this->createImageFile(),
        ];
    }

    private function createImageFile(): UploadedFile
    {
        // Использован DIRECTORY_SEPARATOR во избежании проблем на разных OS
        $pathForCopy = dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'DataFixtures'.DIRECTORY_SEPARATOR;
        copy($pathForCopy.'testImage.jpg', $pathForCopy.'testImage-copy.jpg');
        $imagePath = dirname(__DIR__, 2).DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'DataFixtures'.DIRECTORY_SEPARATOR.'testImage-copy.jpg';
        return new UploadedFile($imagePath, 'testImage-copy.jpg', 'image/jpeg', null, true);
    }

    /**
     * @see https://symfony.com/bundles/ZenstruckFoundryBundle/current/index.html#initialization
     */
    protected function initialize(): static
    {
        return $this
            // ->afterInstantiate(function(Article $article): void {})
        ;
    }
}
