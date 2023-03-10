<?php
declare(strict_types=1);

namespace App\Data;

class LimitationData
{
    private string $actNumber;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $startDate;
    private string $countryName;
    private string $limitationType;
    private string $banOnTransit;
    /** @var string[] */
    private array $bannedProducts;

    /**
     * @param string $actNumber
     * @param \DateTimeImmutable $createdAt
     * @param \DateTimeImmutable $startDate
     * @param string $countryName
     * @param string $limitationType
     * @param string $banOnTransit
     * @param string[] $bannedProducts
     */
    public function __construct(
        string $actNumber,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $startDate,
        string $countryName,
        string $limitationType,
        string $banOnTransit,
        array $bannedProducts
    )
    {
        $this->actNumber = $actNumber;
        $this->createdAt = $createdAt;
        $this->startDate = $startDate;
        $this->countryName = $countryName;
        $this->limitationType = $limitationType;
        $this->banOnTransit = $banOnTransit;
        $this->bannedProducts = $bannedProducts;
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
    public function getBannedProducts(): array
    {
        return $this->bannedProducts;
    }
}
