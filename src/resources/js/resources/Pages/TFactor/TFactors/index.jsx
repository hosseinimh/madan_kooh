import React from "react";
import { useSelector } from "react-redux";

import {
    InputDatePickerColumn,
    InputReactSelectColumn,
    InputRow,
    InputSelectColumn,
    InputTextColumn,
    ListPage,
    SearchBox,
    TableFooter,
    TableItems,
} from "../../../components";
import { PageUtils } from "./PageUtils";
import { tfactorsPage as strings } from "../../../../constants/strings/fa";
import { repetitionTypes } from "../../../../constants/lists";
import { WEIGHT_BRIDGES } from "../../../../constants";
import utils from "../../../../utils/Utils";

const TFactors = () => {
    const layoutState = useSelector((state) => state.layoutReducer);
    const pageState = useSelector((state) => state.pageReducer);
    const columnsCount = 12;
    const pageUtils = new PageUtils();

    const renderSearch = () => (
        <SearchBox
            pageUtils={pageUtils}
            onSubmit={pageUtils.onSubmit}
            onReset={pageUtils.onReset}
        >
            <InputRow>
                <InputSelectColumn
                    field="weightBridge"
                    showLabel
                    items={pageUtils.getPermittedWeightBridges()}
                    fullRow={false}
                />
                <InputDatePickerColumn
                    field="fromDate"
                    showLabel
                    fullRow={false}
                />
                <InputDatePickerColumn
                    field="toDate"
                    showLabel
                    fullRow={false}
                />
                <InputReactSelectColumn
                    field="goodsName"
                    showLabel
                    items={pageUtils?.pageState?.props?.goodsName}
                    fullRow={false}
                />
            </InputRow>
            <InputRow>
                <InputSelectColumn
                    field="driver"
                    showLabel
                    items={pageUtils?.pageState?.props?.drivers}
                    fullRow={false}
                />
                <InputSelectColumn
                    field="buyersName"
                    showLabel
                    items={pageUtils?.pageState?.props?.buyersName}
                    fullRow={false}
                />
                <InputSelectColumn
                    field="sellersName"
                    showLabel
                    items={pageUtils?.pageState?.props?.sellersName}
                    fullRow={false}
                />
                <InputSelectColumn
                    field="user"
                    showLabel
                    items={pageUtils?.pageState?.props?.users}
                    fullRow={false}
                />
            </InputRow>
            <InputRow>
                <InputTextColumn field="tfactorId" showLabel fullRow={false} />
                <InputSelectColumn
                    field="repetitionType"
                    showLabel
                    items={repetitionTypes}
                    fullRow={false}
                />
                <div></div>
                <div></div>
            </InputRow>
        </SearchBox>
    );

    const renderHeader = () => (
        <tr>
            <th scope="col" style={{ width: "80px" }}>
                #
            </th>
            <th scope="col" style={{ width: "90px" }}>
                {strings.weightBridge}
            </th>
            <th scope="col" style={{ width: "120px" }}>
                {strings.factorId}
            </th>
            <th scope="col" style={{ width: "210px" }}>
                {strings.carNumber}
            </th>
            <th scope="col" style={{ width: "110px" }}>
                {strings.currentDate}
            </th>
            <th scope="col" style={{ width: "110px" }}>
                {strings.prevWeight}
            </th>
            <th scope="col" style={{ width: "110px" }}>
                {strings.currentWeight}
            </th>
            <th scope="col" style={{ width: "110px" }}>
                {strings.netWeight}
            </th>
            <th scope="col" style={{ width: "110px" }}>
                {pageUtils?.pageState?.props?.searchFields?.weightBridge ===
                WEIGHT_BRIDGES.WB_1
                    ? strings.buyer2
                    : strings.buyer}
            </th>
            <th scope="col" style={{ width: "110px" }}>
                {pageUtils?.pageState?.props?.searchFields?.weightBridge ===
                WEIGHT_BRIDGES.WB_2
                    ? strings.seller2
                    : strings.seller}
            </th>
            <th scope="col" style={{ width: "110px" }}>
                {strings.goodName}
            </th>
            <th scope="col" style={{ width: "110px" }}>
                {strings.factorDescription1}
            </th>
        </tr>
    );

    const renderItems = () => {
        const children = pageState?.props?.items?.map((item, index) => (
            <tr key={item.id}>
                <td>
                    {layoutState?.loading
                        ? (pageUtils.pageState?.props?.prevPageNumber - 1) *
                              50 +
                          index +
                          1
                        : (pageUtils.pageState?.props?.pageNumber - 1) * 50 +
                          index +
                          1}
                </td>
                <td>{item.weightBridgeText}</td>
                <td>
                    <p>{utils.addCommas(item.factorId)}</p>
                    <p>{`${item.userName} ${item.userFamily}`}</p>
                </td>
                <td>
                    <p>{`${item.carNumber2} ${strings.carNumber1} - ${item.carNumber1}`}</p>
                    <p>{item.driver}</p>
                </td>
                <td>
                    <p>{item.currentDate}</p>
                    <p>{item.currentTime}</p>
                </td>
                <td>{utils.addCommas(item.prevWeight)}</td>
                <td>{utils.addCommas(item.currentWeight)}</td>
                <td>{utils.addCommas(item.currentWeight - item.prevWeight)}</td>
                <td>{item.buyerName}</td>
                <td>{item.sellerName}</td>
                <td>{item.goodsName}</td>
                <td>{item.factorDescription1}</td>
            </tr>
        ));

        return <TableItems columnsCount={columnsCount}>{children}</TableItems>;
    };

    const renderFooter = () => (
        <TableFooter columnsCount={columnsCount} pageUtils={pageUtils} />
    );

    return (
        <ListPage
            pageUtils={pageUtils}
            table={{ renderHeader, renderItems, renderFooter }}
            renderTopList={renderSearch}
            hasAdd={false}
        ></ListPage>
    );
};

export default TFactors;
