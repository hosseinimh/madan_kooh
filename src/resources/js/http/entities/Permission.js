import { BASE_URL } from "../../constants";
import Entity from "./Entity";

export class Permission extends Entity {
    constructor() {
        super();
    }

    async getAll() {
        return await this.handlePost(`${BASE_URL}/api/permissions`);
    }
}
