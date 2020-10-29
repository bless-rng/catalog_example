<?php


namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CategoryDto
{
    /**
     * @Assert\NotBlank()
     */
    private string $name;

    /**
     * @Assert\GreaterThanOrEqual(0)
     * @Assert\NotNull()
     */
    private int $minPrice;

    /**
     * @Assert\GreaterThanOrEqual(1)
     */
    private ?int $parentCategory = null;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getMinPrice(): int
    {
        return $this->minPrice;
    }

    public function setMinPrice(int $minPrice): void
    {
        $this->minPrice = $minPrice;
    }

    public function getParentCategory(): ?int
    {
        return $this->parentCategory;
    }

    public function setParentCategory(?int $parentCategory): void
    {
        $this->parentCategory = $parentCategory;
    }
}
