<?php

namespace App\Services;

use App\Constants\TFactorRepitionType;
use App\Constants\WeightBridge;
use App\Facades\Helper;
use App\Models\TFactor as Model;
use Illuminate\Support\Facades\DB;

class TFactorService
{
    public function getPaginate(?string $weightBridge, string $fromDate, string $toDate, ?string $goodsName, ?string $driver, ?string $buyersName, ?string $sellersName, ?string $users, ?string $factorId, ?string $factorDescription1, string $repetitionType, int $page, int $pageItems): mixed
    {
        $weightBridge = ($weightBridge === WeightBridge::WB_1 || $weightBridge === WeightBridge::WB_2) ? $weightBridge : null;
        $fromDate = Helper::getTimestamp($fromDate);
        $toDate = Helper::getTimestamp($toDate, '23:59:59');
        $sql = $this->select($weightBridge, $fromDate, $toDate, $goodsName, $driver, $buyersName, $sellersName, $users, $factorId, $factorDescription1, $repetitionType);
        $sql .= ' LIMIT ' . ($page - 1) * $pageItems . ',' . $pageItems;
        return DB::select(DB::raw($sql));
    }

    public function getAll(?string $weightBridge, string $fromDate, string $toDate, ?string $goodsName, ?string $driver, ?string $buyersName, ?string $sellersName, ?string $users, ?string $factorId, ?string $factorDescription1, string $repetitionType): mixed
    {
        $weightBridge = ($weightBridge === WeightBridge::WB_1 || $weightBridge === WeightBridge::WB_2) ? $weightBridge : null;
        $fromDate = Helper::getTimestamp($fromDate);
        $toDate = Helper::getTimestamp($toDate, '23:59:59');
        $sql = $this->select($weightBridge, $fromDate, $toDate, $goodsName, $driver, $buyersName, $sellersName, $users, $factorId, $factorDescription1, $repetitionType);
        return DB::select(DB::raw($sql));
    }

    public function getByFactorId(int $factorId, string $weightBridge): mixed
    {
        return Model::where('factor_id', $factorId)->where('weight_bridge', $weightBridge)->first();
    }

    public function getLast(string $weightBridge): mixed
    {
        return Model::where('weight_bridge', $weightBridge)->orderBy('factor_id', 'DESC')->first();
    }

    public function getAllGoodsName()
    {
        return Model::where('goods_name', '!=', '')->distinct()->select('goods_name')->orderBy('goods_name', 'ASC')->get();
    }

    public function getAllBuyersName()
    {
        return Model::where('buyer_name', '!=', '')->distinct()->select('buyer_name')->orderBy('buyer_name', 'ASC')->get();
    }

    public function getAllSellersName()
    {
        return Model::where('seller_name', '!=', '')->distinct()->select('seller_name')->orderBy('seller_name', 'ASC')->get();
    }

    public function getAllDrivers()
    {
        return Model::where('driver', '!=', '')->distinct()->select('driver')->orderBy('driver', 'ASC')->get();
    }

    public function getAllUsers()
    {
        return Model::where('user_name', '!=', '')->where('user_family', '!=', '')->distinct()->select('user_id', 'user_name', 'user_family')->orderBy('user_family', 'ASC')->orderBy('user_name', 'ASC')->get();
    }

    public function getSum(string $weightBridge): mixed
    {
        $sql = $this->sum($weightBridge);
        return DB::select(DB::raw($sql))[0];
    }

    public static function deleteTFactors($id)
    {
        return DB::statement("DELETE FROM `tbl_tfactors` WHERE `id`>=$id");
    }

    private function select(?string $weightBridge, string $fromDate, string $toDate, ?string $goodsName, ?string $driver, ?string $buyersName, ?string $sellersName, ?string $users, ?string $factorId, ?string $factorDescription1, string $repetitionType): string
    {
        $sql = $this->handleGetOrSumSql($weightBridge, $fromDate, $toDate, $goodsName, $driver, $buyersName, $sellersName, $users, $factorId, $factorDescription1, $repetitionType, 'select');
        $sql .= ' ORDER BY tbl_tfactors1.`current_timestamp` DESC,tbl_tfactors1.`id` DESC';
        return $sql;
    }

    private function sum(string $weightBridge): string
    {
        $sql = $this->handleGetOrSumSql($weightBridge, null, null, null, null, null, null, null, null, null, TFactorRepitionType::LAST, 'sum');
        return $sql;
    }

    private function handleGetOrSumSql(?string $weightBridge, ?int $fromDate, ?int $toDate, ?string $goodsName, ?string $driver, ?string $buyersName, ?string $sellersName, ?string $users, ?string $factorId, ?string $factorDescription1,  string $repetitionType, string $operation = 'get'): string
    {
        $goodsName = Helper::createORSQL($goodsName, 'tbl_tfactors1.`goods_name`');
        $buyersName = Helper::createORSQL($buyersName, 'tbl_tfactors1.`buyer_name`');
        $sellersName = Helper::createORSQL($sellersName, 'tbl_tfactors1.`seller_name`');
        $users = Helper::createORSQL($users, 'tbl_tfactors1.`user_id`', true);
        $sql = '';
        switch ($operation) {
            case 'get':
            default:
                $select = 'tbl_tfactors1.*,SUM(tbl_tfactors1.prev_weight) OVER() AS prev_weight_sum,SUM(tbl_tfactors1.current_weight) OVER() AS current_weight_sum,COUNT(*) OVER() AS items_count';
                break;
            case 'sum':
                $select = 'SUM(tbl_tfactors1.prev_weight) AS prev_weight_sum,SUM(tbl_tfactors1.current_weight) AS current_weight_sum,COUNT(*) OVER() AS items_count';
                break;
        }
        switch ($repetitionType) {
            case TFactorRepitionType::ALL:
            default:
                $sql .= 'SELECT ' . $select . ' FROM `tbl_tfactors` tbl_tfactors1';
                break;
            case TFactorRepitionType::LAST:
                $sql .= 'SELECT ' . $select . ' FROM `tbl_tfactors` tbl_tfactors1 JOIN (SELECT factor_id, max(id) AS id FROM `tbl_tfactors` GROUP BY `factor_id`) tbl_tfactors2 ON tbl_tfactors1.`factor_id` = tbl_tfactors2.`factor_id` AND tbl_tfactors1.`id` = tbl_tfactors2.`id`';
                break;
            case TFactorRepitionType::REPETITION:
                $sql .= 'SELECT ' . $select . ' FROM `tbl_tfactors` tbl_tfactors1 GROUP BY `factor_id` HAVING COUNT(`factor_id`)>1';
                break;
        }
        switch ($operation) {
            case 'get':
            default:
                $sql .= ' WHERE tbl_tfactors1.`current_timestamp`>=' . $fromDate . ' AND tbl_tfactors1.`current_timestamp`<=' . $toDate . ' AND tbl_tfactors1.`driver` LIKE "%' . $driver . '%" AND tbl_tfactors1.`factor_id` LIKE "%' . $factorId . '%" AND tbl_tfactors1.`factor_description1` LIKE "%' . $factorDescription1 . '%"';
                $sql .= strlen($goodsName) > 0 ? ' AND (' . $goodsName . ')' : '';
                $sql .= strlen($buyersName) > 0 ? ' AND (' . $buyersName . ')' : '';
                $sql .= strlen($sellersName) > 0 ? ' AND (' . $sellersName . ')' : '';
                $sql .= strlen($users) > 0 ? ' AND (' . $users . ')' : '';
                break;
            case 'sum':
                break;
        }
        if ($weightBridge) {
            $sql .= ' AND tbl_tfactors1.`weight_bridge` LIKE "' . $weightBridge . '"';
        }
        return $sql;
    }
}
