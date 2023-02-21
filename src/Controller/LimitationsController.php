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

    public function table(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
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
        $typeOptions = $dataSource->listLimitationTypeSelectOptions();
        $countryOptions = $dataSource->listCountrySelectOptions();
        $productOptions = $dataSource->listProductSelectOptions();

        return $view->render($response, 'limitations_page.twig', [
            'form' => [
                'type_options' => $typeOptions,
                'country_options' => $countryOptions,
                'product_options' => $productOptions,
            ],
            'table_headers' => $this->getTableHeaders(),
            'table_rows' => array_map(fn($limitation) => $this->getRowData($limitation), $limitations)
        ]);
    }

    /**
     * @return string[]
     */
    private function getTableHeaders(): array
    {
        // TODO: Перенести названия в шаблон
        return [
            'Номер указания',
            'Дата публикации',
            'Дата начала',
            'Тип ограничения',
            'Страна происхождения',
            'Продукция (ввоз)',
            'Продукция (транзит)'
        ];
    }

    private function getRowData(LimitationData $data): array
    {
        return [
            $data->getActNumber(),
            $data->getCreatedAt()->format(self::UI_DATETIME_FORMAT),
            $data->getStartDate()->format(self::UI_DATE_FORMAT),
            $data->getLimitationType(),
            $data->getCountryName(),
            implode(self::PRODUCTS_SEPARATOR, $data->getBannedProducts()),
            $data->getBanOnTransit(),
        ];
    }
}
