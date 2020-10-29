<?php


namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class ProductDto
{
    /**
     * @Assert\NotBlank()
     */
    private string $name;

    /**
     * @Assert\GreaterThanOrEqual(0)
     * @Assert\NotNull()
     */
    private int $price;

    /**
     * @Assert\GreaterThanOrEqual(0)
     * @Assert\NotNull()
     */
    private int $category;

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    /**
     * @param int $price
     */
    public function setPrice(int $price): void
    {
        $this->price = $price;
    }

    /**
     * @return int
     */
    public function getCategory(): int
    {
        return $this->category;
    }

    /**
     * @param int $category
     */
    public function setCategory(int $category): void
    {
        $this->category = $category;
    }
}
