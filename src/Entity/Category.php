<?php

namespace App\Entity;

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
use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
#[ApiResource(
    operations: [
        new Get(),
        new Post(
            security: 'is_granted("ROLE_ADMIN")'
        ),
        new GetCollection(),
        new Delete(
            security: 'is_granted("ROLE_ADMIN")'
        ),
        new Patch(
            security: 'is_granted("ROLE_ADMIN")'
        ),
    ],
    normalizationContext: ['groups' => ['category:read']],
    denormalizationContext: ['groups' => ['category:write']],
    order: ['id' => 'DESC'],
    paginationClientEnabled: true,
    paginationClientItemsPerPage: true,
)]
#[ApiFilter(PropertyFilter::class)]
#[ApiFilter(SearchFilter::class, properties: ['title' => 'partial'])]
#[ApiFilter(OrderFilter::class, properties: ['title'])]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Groups(['category:read', 'category:write'])]
    #[ORM\Column(length: 255)]
    private ?string $title = null;

    /**
     * @var Collection<int, Article>
     */
    #[Groups(['category:read'])]
    #[ORM\OneToMany(targetEntity: Article::class, mappedBy: 'category', cascade: ['remove'])]
    private Collection $article;

    public function __construct()
    {
        $this->article = new ArrayCollection();
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

    /**
     * @return Collection<int, Article>
     */
    public function getArticle(): Collection
    {
        return $this->article;
    }

    public function addArticle(Article $article): static
    {
        if (!$this->article->contains($article)) {
            $this->article->add($article);
            $article->setCategory($this);
        }

        return $this;
    }

    public function removeArticle(Article $article): static
    {
        if ($this->article->removeElement($article)) {
            // set the owning side to null (unless already changed)
            if ($article->getCategory() === $this) {
                $article->setCategory(null);
            }
        }

        return $this;
    }
}
