import React from "react";
import { useSelector } from "react-redux";
import {
    Navigate,
    Route,
    BrowserRouter as Router,
    Routes,
} from "react-router-dom";

import { BASE_PATH, PERMISSIONS, ROLES } from "../../constants";
import * as Pages from "../Pages";

const renderNotAuthRoutes = () => (
    <>
        <Route path={`${BASE_PATH}/users/login`} element={<Pages.Login />} />
        <Route
            path="*"
            element={<Navigate to={`${BASE_PATH}/users/login`} />}
        />
    </>
);

const renderAuthRoutes = () => (
    <>
        <Route
            path={`${BASE_PATH}/notifications`}
            element={<Pages.Notifications />}
        />
        <Route
            path={`${BASE_PATH}/users/edit`}
            element={<Pages.EditCurrentUser />}
        />
        <Route
            path={`${BASE_PATH}/users/change_password`}
            element={<Pages.ChangePasswordCurrentUser />}
        />
        <Route path={`${BASE_PATH}`} element={<Pages.Dashboard />} />
        <Route path="*" element={<Navigate to={BASE_PATH} />} />
    </>
);

const renderAdministratorRoutes = () => (
    <>
        <Route path={`${BASE_PATH}/errors`} element={<Pages.Errors />} />
        <Route
            path={`${BASE_PATH}/users/change_password/:userId`}
            element={<Pages.ChangePasswordUser />}
        />
        <Route path={`${BASE_PATH}/users/add`} element={<Pages.AddUser />} />
        <Route
            path={`${BASE_PATH}/users/edit/:userId`}
            element={<Pages.EditUser />}
        />
        <Route path={`${BASE_PATH}/users`} element={<Pages.Users />} />
    </>
);

function AppRoutes() {
    const userState = useSelector((state) => state.userReducer);

    return (
        <Router>
            <Routes>
                {!userState?.user && renderNotAuthRoutes()}
                {userState?.user?.roles?.includes(ROLES.ADMIN) &&
                    renderAdministratorRoutes()}
                {(userState?.user?.roles?.includes(ROLES.ADMIN) ||
                    userState?.user?.permissions?.includes(
                        PERMISSIONS.READ_ALL_WBS
                    ) ||
                    userState?.user?.permissions?.includes(
                        PERMISSIONS.READ_WB_1
                    ) ||
                    userState?.user?.permissions?.includes(
                        PERMISSIONS.READ_WB_2
                    )) && (
                    <Route
                        path={`${BASE_PATH}/tfactors`}
                        element={<Pages.TFactors />}
                    />
                )}
                {userState?.user && renderAuthRoutes()}
            </Routes>
        </Router>
    );
}

export default AppRoutes;
