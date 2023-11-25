import { BASE_URL, PAGE_ITEMS } from "../../constants";
import Entity from "./Entity";

export class TFactor extends Entity {
    constructor() {
        super();
    }

    async getPaginate(
        weightBridge,
        fromDate,
        toDate,
        goodsName,
        driver,
        buyersName,
        sellersName,
        users,
        factorId,
        factorDescription1,
        repetitionType,
        _pn = 1,
        _pi = PAGE_ITEMS
    ) {
        return await this.handleGetPaginate(
            weightBridge,
            fromDate,
            toDate,
            goodsName,
            driver,
            buyersName,
            sellersName,
            users,
            factorId,
            factorDescription1,
            repetitionType,
            _pn,
            _pi,
            false
        );
    }

    async getPaginateWithProps(
        weightBridge,
        fromDate,
        toDate,
        goodsName,
        driver,
        buyersName,
        sellersName,
        users,
        factorId,
        factorDescription1,
        repetitionType,
        _pn = 1,
        _pi = PAGE_ITEMS
    ) {
        return await this.handleGetPaginate(
            weightBridge,
            fromDate,
            toDate,
            goodsName,
            driver,
            buyersName,
            sellersName,
            users,
            factorId,
            factorDescription1,
            repetitionType,
            _pn,
            _pi,
            true
        );
    }

    async handleGetPaginate(
        weightBridge,
        fromDate,
        toDate,
        goodsName,
        driver,
        buyersName,
        sellersName,
        users,
        factorId,
        factorDescription1,
        repetitionType,
        _pn,
        _pi,
        withProps
    ) {
        const data = {
            weight_bridge: weightBridge,
            from_date: fromDate,
            to_date: toDate,
            goods_name: goodsName,
            driver,
            buyers_name: buyersName,
            sellers_name: sellersName,
            users,
            factor_id: factorId,
            factor_description1: factorDescription1,
            repetition_type: repetitionType,
            _pn,
            _pi,
        };
        return withProps
            ? await this.handlePost(`${BASE_URL}/api/tfactors/props`, data)
            : await this.handlePost(`${BASE_URL}/api/tfactors`, data);
    }

    async update(factorId, factorDescription1) {
        return await this.handlePost(`${BASE_URL}/api/tfactors/update`, {
            factor_id: factorId,
            factor_description1: factorDescription1,
        });
    }

    async deleteTFactors(factorId) {
        return await this.handlePost(`${BASE_URL}/api/tfactors/delete`, {
            factor_id: factorId,
        });
    }
}
