<?php

namespace App\Services;

use App\Facades\Helper;
use App\Models\TFactor as Model;
use Illuminate\Support\Facades\DB;

class TFactorService
{
    public function getPaginate(string $fromDate, string $toDate, string $goodsName, string $weightBridge, string $buyersName, string $sellersName, string $driver, string $users, int $unique, int $factorId, int $page, int $pageItems): mixed
    {
        $sql = $this->getSql($fromDate, $toDate, $goodsName, $weightBridge, $buyersName, $sellersName, $driver, $users, $unique, $factorId);
        $sql .= ' LIMIT ' . $pageItems . ',' . ($page - 1) * $pageItems;
        return DB::select(DB::raw($sql));
    }

    public function getAll(string $fromDate, string $toDate, string $goodsName, string $weightBridge, string $buyersName, string $sellersName, string $driver, string $users, int $unique, int $factorId): mixed
    {
        $sql = $this->getSql($fromDate, $toDate, $goodsName, $weightBridge, $buyersName, $sellersName, $driver, $users, $unique, $factorId);
        return DB::select(DB::raw($sql));
    }

    public static function getByFactorId(int $factorId, string $weightBridge): mixed
    {
        return Model::where('factor_id', $factorId)->where('weight_bridge', $weightBridge)->first();
    }

    public static function getLast(string $weightBridge): mixed
    {
        return Model::where('weight_bridge', $weightBridge)->orderBy('factor_id', 'DESC')->first();
    }

    public static function getAllGoodsName()
    {
        return Model::where('goods_name', '!=', '')->distinct()->select('goods_name')->orderBy('goods_name', 'ASC')->get();
    }

    public static function getAllBuyersName()
    {
        return Model::where('buyer_name', '!=', '')->distinct()->select('buyer_name')->orderBy('buyer_name', 'ASC')->get();
    }

    public static function getAllSellersName()
    {
        return Model::where('seller_name', '!=', '')->distinct()->select('seller_name')->orderBy('seller_name', 'ASC')->get();
    }

    public static function getAllDrivers()
    {
        return Model::where('driver', '!=', '')->distinct()->select('driver')->orderBy('driver', 'ASC')->get();
    }

    public static function getAllUsers()
    {
        return Model::where('user_name', '!=', '')->where('user_family', '!=', '')->distinct()->select('user_id', 'user_name', 'user_family')->orderBy('user_family', 'ASC')->orderBy('user_name', 'ASC')->get();
    }

    public static function deleteTFactors($id)
    {
        return DB::statement("DELETE FROM `tbl_tfactors` WHERE `id`>=$id");
    }

    public function getCurrentWeightSum(string $fromDate, string $toDate, string $goodsName, string $weightBridge, string $buyersName, string $sellersName, string $driver, string $users, int $unique, int $factorId): int
    {
        $sql = $this->sumCurrentWeightSql($fromDate, $toDate, $goodsName, $weightBridge, $buyersName, $sellersName, $driver, $users, $unique, $factorId);
        return DB::select(DB::raw($sql))[0];
    }

    public function getPrevWeightSum(string $fromDate, string $toDate, string $goodsName, string $weightBridge, string $buyersName, string $sellersName, string $driver, string $users, int $unique, int $factorId): int
    {
        $sql = $this->sumPrevWeightSql($fromDate, $toDate, $goodsName, $weightBridge, $buyersName, $sellersName, $driver, $users, $unique, $factorId);
        return DB::select(DB::raw($sql))[0];
    }

    public function count(string $fromDate, string $toDate, string $goodsName, string $weightBridge, string $buyersName, string $sellersName, string $driver, string $users, int $unique, int $factorId): int
    {
        $sql = $this->countSql($fromDate, $toDate, $goodsName, $weightBridge, $buyersName, $sellersName, $driver, $users, $unique, $factorId);
        return DB::select(DB::raw($sql))[0];
    }

    private function getSql(string $fromDate, string $toDate, string $goodsName, string $weightBridge, string $buyersName, string $sellersName, string $driver, string $users, int $unique, int $factorId): string
    {
        $sql = $this->handleGetOrCountOrSumSql($fromDate, $toDate, $goodsName, $weightBridge, $buyersName, $sellersName, $driver, $users, $unique, $factorId, 1);
        $sql .= ' ORDER BY tbl_tfactors1.`current_timestamp` DESC,tbl_tfactors1.`id` DESC';
        return $sql;
    }

    private function countSql(string $fromDate, string $toDate, string $goodsName, string $weightBridge, string $buyersName, string $sellersName, string $driver, string $users, int $unique, int $factorId): string
    {
        return $this->handleGetOrCountOrSumSql($fromDate, $toDate, $goodsName, $weightBridge, $buyersName, $sellersName, $driver, $users, $unique, $factorId, 2);
    }

    private function sumCurrentWeightSql(string $fromDate, string $toDate, string $goodsName, string $weightBridge, string $buyersName, string $sellersName, string $driver, string $users, int $unique, int $factorId): string
    {
        return $this->handleGetOrCountOrSumSql($fromDate, $toDate, $goodsName, $weightBridge, $buyersName, $sellersName, $driver, $users, $unique, $factorId, 3);
    }

    private function sumPrevWeightSql(string $fromDate, string $toDate, string $goodsName, string $weightBridge, string $buyersName, string $sellersName, string $driver, string $users, int $unique, int $factorId): string
    {
        return $this->handleGetOrCountOrSumSql($fromDate, $toDate, $goodsName, $weightBridge, $buyersName, $sellersName, $driver, $users, $unique, $factorId, 4);
    }

    private function handleGetOrCountOrSumSql(string $fromDate, string $toDate, string $goodsName, string $weightBridge, string $buyersName, string $sellersName, string $driver, string $users, int $unique, int $factorId, int $operation = 1): string
    {
        $goodsName = Helper::createORSQL($goodsName, 'tbl_tfactors.`goods_name');
        $buyersName = Helper::createORSQL($buyersName, 'tbl_tfactors.`buyer_name');
        $sellersName = Helper::createORSQL($sellersName, 'tbl_tfactors.`seller_name');
        $users = Helper::createORSQL($users, 'tbl_tfactors.`user_id');
        $sql = '';
        switch ($operation) {
            case 1:
            default:
                $select = 'tbl_tfactors1.*,COUNT(*) OVER() AS items_count';
                break;
            case 2:
                $select = 'COUNT(tbl_tfactors1.*)';
                break;
            case 3:
                $select = 'SUM(tbl_tfactors1.current_weight)';
                break;
            case 4:
                $select = 'SUM(tbl_tfactors1.prev_weight)';
                break;
            case 5:
                $select = 'SUM(tbl_tfactors1.prev_weight),SUM(tbl_tfactors1.current_weight)';
                break;
        }
        $sql .= $unique > 0 ?
            'SELECT ' . $select . ' FROM `tbl_tfactors` tbl_tfactors1 JOIN (SELECT factor_id, max(id) AS id FROM `tbl_tfactors` GROUP BY `factor_id`) tbl_tfactors2 ON tbl_tfactors1.`factor_id` = tbl_tfactors2.`factor_id` AND tbl_tfactors1.`id` = tbl_tfactors2.`id`'
            : 'SELECT ' . $select . ' FROM `tbl_tfactors` tbl_tfactors1';
        switch ($operation) {
            case 1:
            case 2:
            case 3:
            case 4:
            default:
                $sql .= ' WHERE tbl_tfactors1.`current_timestamp`>=' . $fromDate . ' AND tbl_tfactors1.`current_timestamp`<=' . $toDate . ' AND tbl_tfactors1.`driver` LIKE "%' . $driver . '%" AND tbl_tfactors1.`factor_id` LIKE "%' . $factorId . '%"';
                $sql .= strlen($goodsName) > 0 ? ' AND ' . $goodsName : '';
                $sql .= strlen($buyersName) > 0 ? ' AND ' . $buyersName : '';
                $sql .= strlen($sellersName) > 0 ? ' AND ' . $sellersName : '';
                $sql .= strlen($users) > 0 ? ' AND ' . $users : '';
                break;
            case 5:
                break;
        }
        if ($weightBridge) {
            $sql .= ' AND tbl_tfactors1.`weight_bridge` LIKE "' . $weightBridge . '"';
        }
        return $sql;
    }
}
