import React from "react";
import { useDispatch, useSelector } from "react-redux";
import { slideDown, slideUp } from "es6-slide-up-down";
import { easeOutQuint } from "es6-easings";

import {
    CustomLink,
    InputRow,
    InputTextColumn,
    ListPage,
    SearchBox,
    TableFooter,
    TableItems,
} from "../../../components";
import { ROLES } from "../../../../constants";
import { PageUtils } from "./PageUtils";
import {
    usersPage as strings,
    general,
} from "../../../../constants/strings/fa";
import { setDropDownElementAction } from "../../../../state/layout/layoutActions";

const Users = () => {
    const layoutState = useSelector((state) => state.layoutReducer);
    const pageState = useSelector((state) => state.pageReducer);
    const userState = useSelector((state) => state.userReducer);
    const dispatch = useDispatch();
    const columnsCount = 7;
    const pageUtils = new PageUtils();

    const toggleActions = (e, id) => {
        e.stopPropagation();
        const element = document.querySelector(`#${id}`).lastChild;
        if (layoutState?.dropDownElement) {
            slideUp(layoutState.dropDownElement);
            if (layoutState?.dropDownElement === element) {
                dispatch(setDropDownElementAction(null));
                return;
            }
        }
        dispatch(setDropDownElementAction(element));
        slideDown(element, {
            duration: 400,
            easing: easeOutQuint,
        });
    };

    const renderSearch = () => (
        <SearchBox
            pageUtils={pageUtils}
            onSubmit={pageUtils.onSubmit}
            onReset={pageUtils.onReset}
        >
            <InputRow>
                <InputTextColumn
                    field="username"
                    textAlign="left"
                    icon={"icon-frame-14"}
                    fullRow={false}
                />
                <InputTextColumn
                    field="nameFamily"
                    icon={"icon-personalcard4"}
                    fullRow={false}
                />
            </InputRow>
        </SearchBox>
    );

    const renderHeader = () => (
        <tr>
            <th style={{ width: "100px" }}>{strings.username}</th>
            <th style={{ width: "150px" }}>{strings.nameFamily}</th>
            <th style={{ width: "100px" }}>{strings.mobile}</th>
            <th style={{ width: "100px" }}>{strings.roles}</th>
            <th>{strings.permissions}</th>
            <th style={{ width: "100px" }}>{strings.status}</th>
            <th style={{ width: "100px" }}>{general.actions}</th>
        </tr>
    );

    const renderItems = () => {
        const children = pageState?.props?.items?.map((item) => (
            <tr key={item.id}>
                <td>{item.username}</td>
                <td>{`${item.name} ${item.family}`}</td>
                <td>{item.mobile}</td>
                <td>{item.rolesText?.length > 0 ? item.rolesText : "-"}</td>
                <td>
                    {item.permissionsText?.length > 0
                        ? item.permissionsText
                        : "-"}
                </td>
                <td>
                    {item.isActive === 1 ? strings.active : strings.notActive}
                </td>
                <td>
                    <button
                        id={`actions-${item.id}`}
                        type="button"
                        className="btn btn-primary btn-dropdown mx-rdir-10"
                        onClick={(e) => toggleActions(e, `actions-${item.id}`)}
                        disabled={layoutState?.loading}
                    >
                        <div className="d-flex">
                            <span className="grow-1 mx-rdir-10">
                                {general.actions}
                            </span>
                            <div className="icon">
                                <i className="icon-arrow-down5"></i>
                            </div>
                        </div>
                        <div className="dropdown-menu dropdown-menu-end">
                            <ul>
                                <li>
                                    <CustomLink
                                        onClick={() => pageUtils.onEdit(item)}
                                        disabled={layoutState?.loading}
                                    >
                                        {general.edit}
                                    </CustomLink>
                                </li>
                                <li>
                                    <CustomLink
                                        onClick={() =>
                                            pageUtils.onChangePassword(item)
                                        }
                                        disabled={layoutState?.loading}
                                    >
                                        {strings.changePassword}
                                    </CustomLink>
                                </li>
                            </ul>
                        </div>
                    </button>
                </td>
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
            hasAdd={
                userState?.user?.roles?.includes(ROLES.ADMIN) ? true : false
            }
            renderTopList={renderSearch}
        ></ListPage>
    );
};

export default Users;
