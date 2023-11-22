import { BASE_URL, PAGE_ITEMS } from "../../constants";
import utils from "../../utils/Utils";
import Entity from "./Entity";

export class User extends Entity {
    constructor() {
        super();
    }

    async getPaginate(username, name, _pn = 1, _pi = PAGE_ITEMS) {
        return await this.handlePost(`${BASE_URL}/api/users`, {
            username: username,
            name: name,
            _pn,
            _pi,
        });
    }

    async get() {
        return await this.handlePost(`${BASE_URL}/api/users/show`);
    }

    async getWithAdmin(id) {
        return await this.handlePost(`${BASE_URL}/api/users/show/admin/${id}`);
    }

    async store(
        username,
        password,
        confirmPassword,
        name,
        family,
        mobile,
        isActive,
        roles,
        permissions
    ) {
        return await this.handlePost(`${BASE_URL}/api/users/store`, {
            username: username,
            password: password,
            password_confirmation: confirmPassword,
            name,
            family,
            mobile,
            is_active: isActive,
            roles,
            permissions,
        });
    }

    async update(name, family, mobile) {
        return await this.handlePost(`${BASE_URL}/api/users/update`, {
            name,
            family,
            mobile,
        });
    }

    async updateWithAdmin(
        id,
        name,
        family,
        mobile,
        isActive,
        roles,
        permissions
    ) {
        return await this.handlePost(
            `${BASE_URL}/api/users/update/admin/${id}`,
            {
                name,
                family,
                mobile,
                is_active: isActive,
                roles,
                permissions,
            }
        );
    }

    async changePassword(newPassword, confirmPassword) {
        return await this.handlePost(`${BASE_URL}/api/users/change_password`, {
            new_password: newPassword,
            new_password_confirmation: confirmPassword,
        });
    }

    async changePasswordWithAdmin(id, newPassword, confirmPassword) {
        return await this.handlePost(
            `${BASE_URL}/api/users/change_password/admin/${id}`,
            {
                new_password: newPassword,
                new_password_confirmation: confirmPassword,
            }
        );
    }

    logOut() {
        utils.clearLS();
    }
}
