import React from "react";
import { useDispatch, useSelector } from "react-redux";
import { Link } from "react-router-dom";

import { BASE_PATH, PERMISSIONS, ROLES } from "../../../constants";
import { fetchLogoutAction } from "../../../state/user/userActions";
import { CustomLink } from "../";
import { general, sidebar as strings } from "../../../constants/strings/fa";
import { toggleSidebarAction } from "../../../state/layout/layoutActions";

const menuItems = {
    DASHBOARD: {
        page: "Dashboard",
        path: BASE_PATH,
        icon: "icon-category4",
        label: strings.dashboard,
    },
    ERRORS: {
        page: "Errors",
        path: `${BASE_PATH}/errors`,
        icon: "icon-category4",
        label: strings.errors,
    },
    NOTIFICATIONS: {
        page: "Notifications",
        path: `${BASE_PATH}/notifications`,
        icon: "icon-notification-bing",
        label: strings.notifications,
    },
    USERS: {
        page: "Users",
        path: `${BASE_PATH}/users`,
        icon: "icon-personalcard",
        label: strings.users,
    },
    EDIT_PROFILE: {
        page: "EditProfile",
        path: `${BASE_PATH}/users/edit`,
        icon: "icon-user-edit4",
        label: strings.editProfile,
    },
    CHANGE_PASSWORD_PROFILE: {
        page: "ChangePasswordProfile",
        path: `${BASE_PATH}/users/change_password`,
        icon: "icon-key4",
        label: strings.changePassword,
    },
    TFACTORS: {
        page: "TFactors",
        path: `${BASE_PATH}/tfactors`,
        icon: "icon-truck3",
        label: strings.tfactors,
    },
};

function Sidebar() {
    const dispatch = useDispatch();
    const layoutState = useSelector((state) => state.layoutReducer);
    const pageState = useSelector((state) => state.pageReducer);
    const userState = useSelector((state) => state.userReducer);

    const toggleSidebar = () => {
        dispatch(toggleSidebarAction());
    };

    const onLogout = () => {
        dispatch(fetchLogoutAction());
    };

    const renderMenuItem = (menuItem, badge = 0) => {
        const active = menuItem.page === pageState?.page;
        return (
            <li className={`${active ? "active" : ""}`}>
                <Link to={menuItem.path}>
                    <i className={menuItem.icon}></i>
                    <span>{menuItem.label}</span>
                    {badge > 0 && (
                        <div
                            className="dot"
                            style={{
                                display: "inline",
                                position: "relative",
                                right: "-40px",
                                top: "2px",
                            }}
                        >
                            <span className="bg-success"></span>
                        </div>
                    )}
                </Link>
            </li>
        );
    };

    const renderMainItems = () => (
        <>
            <div className="menu-title">{strings.mainItems}</div>
            <ul>
                {renderMenuItem(menuItems.DASHBOARD)}
                {(userState?.user?.roles?.includes(ROLES.ADMIN) ||
                    userState?.user?.permissions?.includes(
                        PERMISSIONS.READ_ALL_WBS
                    ) ||
                    userState?.user?.permissions?.includes(
                        PERMISSIONS.READ_WB_1
                    ) ||
                    userState?.user?.permissions?.includes(
                        PERMISSIONS.READ_WB_2
                    )) && <>{renderMenuItem(menuItems.TFACTORS)}</>}
            </ul>
        </>
    );

    const renderBaseItems = () => <></>;

    const renderSystemItems = () => (
        <>
            <div className="menu-title">{strings.systemItems}</div>
            <ul>
                {renderMenuItem(menuItems.NOTIFICATIONS)}
                {userState?.user?.roles?.includes(ROLES.ADMIN) && (
                    <>{renderMenuItem(menuItems.USERS)}</>
                )}
                {renderMenuItem(menuItems.EDIT_PROFILE)}
                {renderMenuItem(menuItems.CHANGE_PASSWORD_PROFILE)}
                {userState?.user?.roles?.includes(ROLES.ADMIN) && (
                    <>{renderMenuItem(menuItems.ERRORS)}</>
                )}
                <li>
                    <CustomLink onClick={onLogout} className="danger">
                        <i className="icon-logout"></i>
                        <span>{strings.logout}</span>
                    </CustomLink>
                </li>
            </ul>
        </>
    );

    return (
        <div
            className={`sidebar ${
                layoutState?.sidebarCollapsed ? "active" : ""
            }`}
        >
            <div className="sidebar-hd d-flex align-start just-center">
                <div className="logo mb-20">
                    <h1 className="text mb-20">{general.brand}</h1>
                    <div className="line-gr"></div>
                </div>

                <div className="closemenu" onClick={toggleSidebar}>
                    <i className="icon-arrow-right"></i>
                </div>
            </div>
            <div className="menu scrollhide">
                {renderMainItems()}
                {renderBaseItems()}
                {renderSystemItems()}
                <div className="menu-title">{strings.telSupport}</div>
                <ul>
                    <li className="pd-d-30">
                        <a href={`tel:${strings.tel}`}>
                            <i className="icon-call"></i>
                            <span className="tel">{strings.tel}</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    );
}

export default Sidebar;
