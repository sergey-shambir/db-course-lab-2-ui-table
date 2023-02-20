<?php
declare(strict_types=1);

namespace App\Data;

class LimitationData
{
    private string $actNumber;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $startDate;
    private string $countryName;
    private string $regionName;
    private string $limitationType;
    private string $banOnTransit;
    /** @var string[] */
    private array $products;

    /**
     * @param string $actNumber
     * @param \DateTimeImmutable $createdAt
     * @param \DateTimeImmutable $startDate
     * @param string $countryName
     * @param string $regionName
     * @param string $limitationType
     * @param string $banOnTransit
     * @param string[] $products
     */
    public function __construct(
        string $actNumber,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $startDate,
        string $countryName,
        string $regionName,
        string $limitationType,
        string $banOnTransit,
        array $products
    )
    {
        $this->actNumber = $actNumber;
        $this->createdAt = $createdAt;
        $this->startDate = $startDate;
        $this->countryName = $countryName;
        $this->regionName = $regionName;
        $this->limitationType = $limitationType;
        $this->banOnTransit = $banOnTransit;
        $this->products = $products;
    }

    public function getActNumber(): string
    {
        return $this->actNumber;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getStartDate(): \DateTimeImmutable
    {
        return $this->startDate;
    }

    public function getCountryName(): string
    {
        return $this->countryName;
    }

    public function getRegionName(): string
    {
        return $this->regionName;
    }

    public function getLimitationType(): string
    {
        return $this->limitationType;
    }

    public function getBanOnTransit(): string
    {
        return $this->banOnTransit;
    }

    /**
     * @return string[]
     */
    public function getProducts(): array
    {
        return $this->products;
    }
}
