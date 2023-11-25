import { useForm } from "react-hook-form";
import { yupResolver } from "@hookform/resolvers/yup";

import { TFactor as Entity } from "../../../../http/entities";
import { BasePageUtils } from "../../../../utils/BasePageUtils";
import { BASE_PATH, ROLES } from "../../../../constants";
import { editTFactorSchema as schema } from "../../../validations";
import { editTFactorPage as strings } from "../../../../constants/strings/fa";

export class PageUtils extends BasePageUtils {
    constructor() {
        const form = useForm({
            resolver: yupResolver(schema),
        });
        super("EditTFactor", strings, form);
        this.entity = new Entity();
        this.callbackUrl = this.userState?.user?.roles?.includes(ROLES.ADMIN)
            ? `${BASE_PATH}/tfactors`
            : `${BASE_PATH}/dashboard`;
    }

    onLoad() {
        super.onLoad();
    }

    async onSubmit(data) {
        const promise = this.entity.update(
            data.factorId,
            data.factorDescription1
        );
        super.onModifySubmit(promise);
    }
}
