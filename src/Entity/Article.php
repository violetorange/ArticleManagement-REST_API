<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

use function Symfony\Component\String\u;

#[\AllowDynamicProperties] #[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new Post(
            denormalizationContext: ['groups' => ['article:create'], 'disable_type_enforcement' => true],
        ),
        new GetCollection(),
        new Delete(),
        new Patch(),
    ],
    normalizationContext: ['groups' => ['article:read']],
    denormalizationContext: ['groups' => ['article:write']],
    order: ['id' => 'DESC'],
    paginationClientEnabled: true,
    paginationClientItemsPerPage: true
)]
#[ORM\HasLifecycleCallbacks]
#[ApiFilter(PropertyFilter::class)]
#[ApiFilter(DateFilter::class, properties: ['createdAt', 'changedAt'])]
#[ApiFilter(SearchFilter::class, properties: ['category.title' => 'partial'])]
#[ApiFilter(BooleanFilter::class, properties: ['isPublished'])]
#[ApiFilter(OrderFilter::class, properties: ['title', 'createdAt', 'changedAt', 'category.title', 'isPublished'])]
#[Vich\Uploadable()]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['article:read'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['article:read', 'article:write', 'article:create'])]
    #[NotBlank(message: 'Это поле не может быть пустым')]
    #[Length(max: 255, maxMessage: 'Максимум - 255 символов')]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['article:read', 'article:write', 'article:create'])]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    private ?string $content = null;

    #[Vich\UploadableField(mapping: 'article', fileNameProperty: 'image')]
    #[Groups(['article:create'])]
    private ?File $imageFile = null;

    #[ORM\Column(length: 255)]
    #[Groups(['article:read'])]
    private ?string $image = null;

    #[ORM\Column]
    #[Groups(['article:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['article:read'])]
    private ?\DateTimeImmutable $changedAt = null;

    #[Groups(['article:read', 'article:write', 'article:create'])]
    #[ORM\Column]
    private ?bool $isPublished = false;

    #[ORM\ManyToOne(inversedBy: 'article')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['article:read', 'article:write', 'article:create'])]
    private ?Category $category = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->changedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    #[Groups(['article:read'])]
    public function getShortContent(): ?string
    {
        return u($this->content)->truncate(60, '...');
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    #[Groups(['article:read'])]
    public function getImageUrl(): ?string
    {
        if (null == $this->image) {
            return null;
        }

        return '/images/articles/'.$this->image;
    }

    public function setImage(?string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    #[Groups(['article:read'])]
    public function getFormattedCreatedAt(): string
    {
        return $this->createdAt->format('Y-m-d H:i');
    }

    public function getChangedAt(): ?\DateTimeImmutable
    {
        return $this->changedAt;
    }

    #[ORM\PreUpdate()]
    public function setChangedAt(): void
    {
        $this->changedAt = new \DateTimeImmutable();
    }

    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    public function setIsPublished(bool|string $isPublished): static
    {
        if (is_string($isPublished)) {
            $this->isPublished = 'true' === $isPublished; // исправление проблемы POST-запроса (multipart/form-data преобразует булевые значения в строки)
        } else {
            $this->isPublished = $isPublished;
        }

        return $this;
    }

    public function getCategory(): ?Category
    {
        return $this->category;
    }

    public function setCategory(?Category $category): static
    {
        $this->category = $category;

        return $this;
    }
}
