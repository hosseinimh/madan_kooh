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

    public function store(string $weightBridge, ?string $factorId, ?string $carCode, ?string $carNumber1, ?string $carNumber2, ?string $driver, ?string $currentWaghit, ?string $prevWaghit, ?string $currentDate, ?string $currentTime, ?string $prevDate, ?string $prevTime, ?string $goodsCode, ?string $goodsName, ?string $unitPrice, ?string $purchCode, ?string $purchName, ?string $salerCode, ?string $salerName, ?string $userId, ?string $userName, ?string $userFamily, ?string $currentRow, ?string $prevRow, ?string $factorDesc1, ?string $factorDesc2, ?string $factorDesc3, ?string $factorDesc4, ?string $factorDesc5, ?string $factorDesc6, ?string $factorDesc7, ?string $factorDesc8, ?string $factorDesc9, ?string $formType, ?string $printLocation, ?string $tozinCost, ?string $pNo, ?string $costId, ?string $factorMod, ?string $digree, ?string $maliat, ?string $userEditId): mixed
    {
        $currentTimestamp = Helper::getTimestamp($currentDate, $currentTime);
        $prevTimestamp = Helper::getTimestamp($prevDate, $prevTime);
        $data = [
            'weight_bridge' => $weightBridge,
            'factor_id' => $factorId,
            'car_code' => $carCode ?? '',
            'car_number1' => $carNumber1 ?? '',
            'car_number2' => $carNumber2 ?? '',
            'driver' => $driver ?? '',
            'current_weight' => $currentWaghit ?? 0,
            'prev_weight' => $prevWaghit ?? 0,
            'current_date' => $currentDate ?? '',
            'current_time' => $currentTime ?? '',
            'current_timestamp' => $currentTimestamp,
            'prev_date' => $prevDate ?? '',
            'prev_time' => $prevTime ?? '',
            'prev_timestamp' => $prevTimestamp,
            'goods_code' => $goodsCode ?? 0,
            'goods_name' => $goodsName ?? '',
            'unit_price' => $unitPrice ?? 0,
            'buyer_code' => $purchCode ?? 0,
            'buyer_name' => $purchName ?? '',
            'seller_code' => $salerCode ?? 0,
            'seller_name' => $salerName ?? '',
            'user_id' => $userId ?? 0,
            'user_name' => $userName ?? '',
            'user_family' => $userFamily ?? '',
            'current_row' => $currentRow ?? 0,
            'prev_row' => $prevRow ?? 0,
            'factor_description1' => $factorDesc1 ?? '',
            'factor_description2' => $factorDesc2 ?? '',
            'factor_description3' => $factorDesc3 ?? '',
            'factor_description4' => $factorDesc4 ?? '',
            'factor_description5' => $factorDesc5 ?? '',
            'factor_description6' => $factorDesc6 ?? '',
            'factor_description7' => $factorDesc7 ?? '',
            'factor_description8' => $factorDesc8 ?? '',
            'factor_description9' => $factorDesc9 ?? '',
            'form_type' => $formType ?? 0,
            'print_location' => $printLocation ?? 0,
            'tozin_cost' => $tozinCost ?? 0,
            'p_no' => $pNo ?? 0,
            'cost_id' => $costId ?? 0,
            'factor_mode' => $factorMod ?? 0,
            'degree' => $digree ?? '',
            'tax' => $maliat ?? 0,
            'user_edit_id' => $userEditId ?? 0,
        ];
        return Model::create($data);
    }

    public function update(string $factorId, string $factorDescription1): bool
    {
        $data = [
            'factor_description1' => $factorDescription1
        ];
        return Model::where('factor_id', $factorId)->update($data);
    }

    public static function deleteTFactors(string $factorId): bool
    {
        return Model::where('factor_id', $factorId)->delete();
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
