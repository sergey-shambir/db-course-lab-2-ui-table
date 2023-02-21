<?php
declare(strict_types=1);

namespace App\Controller;

use App\Controller\Data\LimitationsFormData;
use App\Data\LimitationData;
use App\Data\LimitationDataSource;
use App\Data\LimitationFilter;
use App\Data\ListLimitationsParams;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class LimitationsController
{
    private const UI_DATETIME_FORMAT = 'Y-m-d H:i:s';
    private const UI_DATE_FORMAT = 'Y-m-d';
    private const PRODUCTS_SEPARATOR = '; ';

    public function table(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $formData = LimitationsFormData::fromArray($request->getQueryParams());
        $listParams = $this->getListLimitationsParams($formData);

        $view = Twig::fromRequest($request);
        $dataSource = new LimitationDataSource();
        $limitations = $dataSource->listLimitations($listParams);
        $typeOptions = $dataSource->listLimitationTypeSelectOptions();
        $countryOptions = $dataSource->listCountrySelectOptions();
        $productOptions = $dataSource->listProductSelectOptions();

        return $view->render($response, 'limitations_page.twig', [
            'form' => [
                'type_options' => $typeOptions,
                'country_options' => $countryOptions,
                'product_options' => $productOptions,
                'values' => $formData->toArray(),
            ],
            'table_rows' => array_map(fn($limitation) => $this->getRowData($limitation), $limitations)
        ]);
    }

    private function getRowData(LimitationData $data): array
    {
        return [
            'act_number' => $data->getActNumber(),
            'created_at' => $data->getCreatedAt()->format(self::UI_DATETIME_FORMAT),
            'start_date' => $data->getStartDate()->format(self::UI_DATE_FORMAT),
            'type' => $data->getLimitationType(),
            'country' => $data->getCountryName(),
            'banned_products' => implode(self::PRODUCTS_SEPARATOR, $data->getBannedProducts()),
            'ban_on_transit' => $data->getBanOnTransit(),
        ];
    }

    private function getListLimitationsParams(LimitationsFormData $data): ListLimitationsParams
    {
        $filters = [];
        if ($value = $data->getFilterByType())
        {
            $filters[] = new LimitationFilter(LimitationFilter::FILTER_BY_TYPE, $value);
        }
        if ($value = $data->getFilterByCountry())
        {
            $filters[] = new LimitationFilter(LimitationFilter::FILTER_BY_COUNTRY, $value);
        }
        if ($value = $data->getFilterByProduct())
        {
            $filters[] = new LimitationFilter(LimitationFilter::FILTER_BY_PRODUCT, $value);
        }

        return new ListLimitationsParams(
            $data->getSearchQuery(),
            $filters,
            ListLimitationsParams::SORT_BY_ACT_NUMBER,
            true,
            10,
            1
        );
    }
}
