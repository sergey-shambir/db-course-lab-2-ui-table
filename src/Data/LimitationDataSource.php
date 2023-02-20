<?php
declare(strict_types=1);

namespace App\Data;

use App\Common\Database\ConnectionProvider;

class LimitationDataSource
{
    public function __construct()
    {
        $this->connection = ConnectionProvider::getConnection();
    }

    /**
     * @return LimitationData[] array
     */
    public function listLimitations(ListLimitationsParams $params): array
    {
        // TODO: Реализовать получение списка
        return [];
    }
}
