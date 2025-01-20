<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Doctrine\Orm\Filter\DateFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Serializer\Filter\PropertyFilter;
use App\Repository\ArticleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use function Symfony\Component\String\u;

#[ORM\Entity(repositoryClass: ArticleRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['article:read']],
    denormalizationContext: ['groups' => ['article:write']],
    paginationClientEnabled: true,
    paginationClientItemsPerPage: true
)]
#[ORM\HasLifecycleCallbacks]
#[ApiFilter(PropertyFilter::class)]
#[ApiFilter(DateFilter::class, properties: ['createdAt', 'changedAt'])]
#[ApiFilter(SearchFilter::class, properties: ['category.title' => 'partial'])]
#[ApiFilter(BooleanFilter::class, properties: ['isPublished'])]
class Article
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['article:read', 'article:write'])]
    #[NotBlank(message: 'Это поле не может быть пустым')]
    #[Length(max: 255, maxMessage: 'Максимум - 255 символов')]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['article:read', 'article:write'])]
    #[ApiFilter(SearchFilter::class, strategy: 'partial')]
    private ?string $content = null;

    #[ORM\Column(length: 255)]
    #[Groups(['article:read', 'article:write'])]
    private ?string $image = null;

    #[ORM\Column]
    #[Groups(['article:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    #[Groups(['article:read'])]
    private ?\DateTimeImmutable $changedAt = null;

    #[Groups(['article:read', 'article:write'])]
    #[ORM\Column]
    private ?bool $isPublished = false;

    #[ORM\ManyToOne(inversedBy: 'article')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['article:read', 'article:write'])]
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

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
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

    public function setIsPublished(bool $isPublished): static
    {
        $this->isPublished = $isPublished;

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
