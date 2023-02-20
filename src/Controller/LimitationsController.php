<?php
declare(strict_types=1);

namespace App\Controller;

use App\Data\LimitationData;
use App\Data\LimitationDataSource;
use App\Data\ListLimitationsParams;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Views\Twig;

class LimitationsController
{
    private const UI_DATETIME_FORMAT = 'Y-m-d H:i:s';
    private const UI_DATE_FORMAT = 'Y-m-d';
    const PRODUCTS_SEPARATOR = '; ';

    public function list(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $params = new ListLimitationsParams(
            '',
            [],
            ListLimitationsParams::SORT_BY_ACT_NUMBER,
            true,
            10,
            1
        );

        $view = Twig::fromRequest($request);
        $dataSource = new LimitationDataSource();
        $limitations = $dataSource->listLimitations($params);

        return $view->render($response, 'limitations_page.twig', [
            'table_headers' => $this->getTableHeaders(),
            'table_rows' => array_map(function (LimitationData $limitation): array {
                return $this->getRowData($limitation);
            }, $limitations)
        ]);
    }

    /**
     * @return string[]
     */
    private function getTableHeaders(): array
    {
        return [
            'Act Number',
            'Created At',
            'Start Date',
            'Country Name',
            'Region Name',
            'Limitation Type',
            'Products',
            'Ban on transit'
        ];
    }

    private function getRowData(LimitationData $data): array
    {
        return [
            $data->getActNumber(),
            $data->getCreatedAt()->format(self::UI_DATETIME_FORMAT),
            $data->getStartDate()->format(self::UI_DATE_FORMAT),
            $data->getCountryName(),
            $data->getRegionName(),
            $data->getLimitationType(),
            implode(self::PRODUCTS_SEPARATOR, $data->getProducts()),
            $data->getBanOnTransit(),
        ];
    }
}
