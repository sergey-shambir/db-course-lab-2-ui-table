<?php
declare(strict_types=1);

namespace App\Data;

use App\Common\Database\Connection;
use App\Common\Database\ConnectionProvider;

class LimitationDataSource
{
    private const MYSQL_DATETIME_FORMAT = 'Y-m-d H:i:s';
    private const MYSQL_DATE_FORMAT = 'Y-m-d';

    private Connection $connection;

    public function __construct()
    {
        $this->connection = ConnectionProvider::getConnection();
    }

    /**
     * @return LimitationData[]
     */
    public function listLimitations(ListLimitationsParams $params): array
    {
        $queryParams = [];
        $query = $this->buildSqlQuery($params, $queryParams);

        $stmt = $this->connection->execute($query, $queryParams);
        return array_map(function (array $row): LimitationData {
            return $this->hydrateData($row);
        }, $stmt->fetchAll(\PDO::FETCH_ASSOC));
    }

    /**
     * @param string[] $row
     * @return LimitationData
     */
    private function hydrateData(array $row): LimitationData
    {
        try
        {
            return new LimitationData(
                $row['act_number'],
                \DateTimeImmutable::createFromFormat(self::MYSQL_DATETIME_FORMAT, $row['created_at']),
                \DateTimeImmutable::createFromFormat(self::MYSQL_DATE_FORMAT, $row['start_date']),
                $row['country_name'],
                $row['type_name'],
                $row['ban_on_transit'],
                json_decode($row['products'], true, 512, JSON_THROW_ON_ERROR)
            );
        }
        catch (\Exception $e)
        {
            throw new \RuntimeException($e->getMessage(), $e->getCode(), $e);
        }
    }

    private function buildSqlQuery(ListLimitationsParams $params, array &$queryParams): string
    {
        // TODO: Реализовать фильтрацию, сортировку, пагинацию.

        $query = <<<SQL
SELECT
  l.act_number,
  l.created_at,
  l.start_date,
  c.country_name,
  lt.type_name,
  JSON_ARRAYAGG(p.product_name) AS products,
  l.ban_on_transit
FROM limitation l
  INNER JOIN limitation_type lt on l.type = lt.type
  INNER JOIN country c on l.country_id = c.id
  INNER JOIN limitation_ban_on_product lbop on l.act_number = lbop.act_number
  INNER JOIN product p on lbop.product_id = p.id
GROUP BY l.act_number
SQL;

        return $query;
    }
}
