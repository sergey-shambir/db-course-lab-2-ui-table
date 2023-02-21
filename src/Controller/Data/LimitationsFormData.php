<?php
declare(strict_types=1);

namespace App\Controller\Data;

class LimitationsFormData
{
    private ?string $filterByType;
    private ?string $filterByCountry;
    private ?string $filterByProduct;
    private string $searchQuery;

    public function __construct(
        ?string $filterByType,
        ?string $filterByCountry,
        ?string $filterByProduct,
        string $searchQuery,
    )
    {
        $this->filterByType = $filterByType;
        $this->filterByCountry = $filterByCountry;
        $this->filterByProduct = $filterByProduct;
        $this->searchQuery = $searchQuery;
    }

    public function toArray(): array
    {
        return [
            'filter_by_type' => $this->filterByType,
            'filter_by_country' => $this->filterByCountry,
            'filter_by_product' => $this->filterByProduct,
            'search_query' => $this->searchQuery,
        ];
    }

    public static function fromArray(array $parameters): self
    {
        return new self(
            $parameters['filter_by_type'] ?: null,
            $parameters['filter_by_country'] ?: null,
            $parameters['filter_by_product'] ?: null,
            $parameters['search_query'] ?? ''
        );
    }

    public function getFilterByType(): ?string
    {
        return $this->filterByType;
    }

    public function getFilterByCountry(): ?string
    {
        return $this->filterByCountry;
    }

    public function getFilterByProduct(): ?string
    {
        return $this->filterByProduct;
    }

    public function getSearchQuery(): string
    {
        return $this->searchQuery;
    }
}
