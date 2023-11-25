import { BASE_URL } from "../../constants";
import Entity from "./Entity";

export class Dashboard extends Entity {
    constructor() {
        super();
    }

    async index() {
        return await this.handlePost(`${BASE_URL}/api/dashboard`);
    }
}
