import React from "react";
import { useSelector } from "react-redux";

import { BlankPage } from "../../components";
import { PageUtils } from "./PageUtils";
import { dashboardPage as strings } from "../../../constants/strings/fa";
import utils from "../../../utils/Utils";
import { PERMISSIONS, ROLES } from "../../../constants";

const Dashboard = () => {
    const userState = useSelector((state) => state.userReducer);
    const pageUtils = new PageUtils();

    const renderAllWBs = () => (
        <div className="block pd-20 d-flex d-flex-column just-around align-center">
            <h3 className="text mb-30">{strings.tfactors}</h3>
            <p>
                {strings.no}:{" "}
                <span className="text">
                    {utils.addCommasPersianIfNum(
                        pageUtils?.pageState?.props?.wb1?.itemsCount +
                            pageUtils?.pageState?.props?.wb2?.itemsCount
                    )}
                </span>
            </p>
            <p>
                {strings.currentWeight}:{" "}
                <span className="text">
                    {utils.addCommasPersianIfNum(
                        pageUtils?.pageState?.props?.wb1?.prevWeightSum +
                            pageUtils?.pageState?.props?.wb2?.prevWeightSum
                    )}
                </span>
            </p>
            <p>
                {strings.netWeight}:{" "}
                <span className="text">
                    {utils.addCommasPersianIfNum(
                        pageUtils?.pageState?.props?.wb1?.currentWeightSum +
                            pageUtils?.pageState?.props?.wb2?.currentWeightSum
                    )}
                </span>
            </p>
        </div>
    );

    const renderWB1 = () => (
        <div className="block pd-20 d-flex d-flex-column just-around align-center">
            <h3 className="text mb-30">{strings.tfactors1}</h3>
            <p>
                {strings.no}:{" "}
                <span className="text">
                    {utils.addCommasPersianIfNum(
                        pageUtils?.pageState?.props?.wb1?.itemsCount
                    )}
                </span>
            </p>
            <p>
                {strings.currentWeight}:{" "}
                <span className="text">
                    {utils.addCommasPersianIfNum(
                        pageUtils?.pageState?.props?.wb1?.prevWeightSum
                    )}
                </span>
            </p>
            <p>
                {strings.netWeight}:{" "}
                <span className="text">
                    {utils.addCommasPersianIfNum(
                        pageUtils?.pageState?.props?.wb1?.currentWeightSum
                    )}
                </span>
            </p>
        </div>
    );

    const renderWB2 = () => (
        <div className="block pd-20 d-flex d-flex-column just-around align-center">
            <h3 className="text mb-30">{strings.tfactors2}</h3>
            <p>
                {strings.no}:{" "}
                <span className="text">
                    {utils.addCommasPersianIfNum(
                        pageUtils?.pageState?.props?.wb2?.itemsCount
                    )}
                </span>
            </p>
            <p>
                {strings.currentWeight}:{" "}
                <span className="text">
                    {utils.addCommasPersianIfNum(
                        pageUtils?.pageState?.props?.wb2?.prevWeightSum
                    )}
                </span>
            </p>
            <p>
                {strings.netWeight}:{" "}
                <span className="text">
                    {utils.addCommasPersianIfNum(
                        pageUtils?.pageState?.props?.wb2?.currentWeightSum
                    )}
                </span>
            </p>
        </div>
    );

    return (
        <BlankPage pageUtils={pageUtils}>
            <div className="section d-flex-wrap fix-mr15">
                {(userState?.user?.roles?.includes(ROLES.ADMIN) ||
                    userState?.user?.permissions?.includes(
                        PERMISSIONS.READ_ALL_WBS
                    )) &&
                    renderAllWBs()}
                {(userState?.user?.roles?.includes(ROLES.ADMIN) ||
                    userState?.user?.permissions?.includes(
                        PERMISSIONS.READ_WB_1
                    )) &&
                    renderWB1()}
                {(userState?.user?.roles?.includes(ROLES.ADMIN) ||
                    userState?.user?.permissions?.includes(
                        PERMISSIONS.READ_WB_2
                    )) &&
                    renderWB2()}
            </div>
        </BlankPage>
    );
};

export default Dashboard;
