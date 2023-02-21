<?php
declare(strict_types=1);

namespace App\Data;

class LimitationFilter
{
    public const FILTER_BY_TYPE = 'type';
    public const FILTER_BY_COUNTRY = 'country';
    public const FILTER_BY_PRODUCT = 'product';

    private const ALL_FILTERS = [
        self::FILTER_BY_TYPE,
        self::FILTER_BY_COUNTRY,
        self::FILTER_BY_PRODUCT,
    ];

    private string $filterByField;
    private string $value;

    public function __construct(string $filterByField, string $value)
    {
        if (!in_array($filterByField, self::ALL_FILTERS, true))
        {
            throw new \InvalidArgumentException("List cannot be filtered by field '$filterByField'");
        }
        $this->filterByField = $filterByField;
        $this->value = $value;
    }

    public function getFilterByField(): string
    {
        return $this->filterByField;
    }

    public function getValue(): string
    {
        return $this->value;
    }
}
