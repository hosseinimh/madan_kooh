import { useForm } from "react-hook-form";
import { yupResolver } from "@hookform/resolvers/yup";

import { User as Entity, Permission } from "../../../../http/entities";
import { BasePageUtils } from "../../../../utils/BasePageUtils";
import {
    BASE_PATH,
    MESSAGE_CODES,
    MESSAGE_TYPES,
    ROLES,
} from "../../../../constants";
import { addUserSchema as schema } from "../../../validations";
import {
    permissions,
    addUserPage as strings,
} from "../../../../constants/strings/fa";
import { setLoadingAction } from "../../../../state/layout/layoutActions";
import { setPagePropsAction } from "../../../../state/page/pageActions";
import { setMessageAction } from "../../../../state/message/messageActions";

export class PageUtils extends BasePageUtils {
    constructor() {
        const form = useForm({
            resolver: yupResolver(schema),
        });
        super("Users", strings, form);
        this.entity = new Entity();
        this.callbackUrl = `${BASE_PATH}/users`;
        this.initialPageProps = { permissions: [] };
    }

    onLoad() {
        super.onLoad();
        this.fillForm();
    }

    async fillForm() {
        try {
            this.dispatch(setLoadingAction(true));
            const permission = new Permission();
            const result = await permission.getAll();
            this.navigateIfPermissionsNotFound(result);
            this.handleFetchResult(result);
        } catch {
        } finally {
            this.dispatch(setLoadingAction(false));
        }
    }

    navigateIfPermissionsNotFound(result) {
        if (result === null) {
            this.dispatch(
                setMessageAction(
                    permissions.noPermissionsFound,
                    MESSAGE_TYPES.ERROR,
                    MESSAGE_CODES.ITEM_NOT_FOUND,
                    false
                )
            );
            this.navigate(this.callbackUrl);

            throw new Error();
        }
    }

    handleFetchResult(result) {
        this.useForm.setValue("isActiveContainer", 1);
        this.useForm.setValue("user", "on");
        this.dispatch(setPagePropsAction({ permissions: result.items }));
    }

    onSetItem(item, value) {
        let props = this.pageState?.props;
        props[item] = value;
        this.dispatch(setPagePropsAction(props));
    }

    async onSubmit(data) {
        let roles = [];
        let permissions = [];
        if (data.administrator === "on") {
            roles = [...roles, ROLES.ADMIN];
        } else {
            permissions = Object.keys(data.permissionsContainer)
                .map((key) => [key, data.permissionsContainer[key]])
                .filter((permission) => permission[1])
                .map((permission) => permission[0]);
        }
        const promise = this.entity.store(
            data.username,
            data.password,
            data.confirmPassword,
            data.name,
            data.family,
            data.mobile,
            data.isActiveContainer ? 1 : 0,
            roles,
            permissions
        );
        this.onModifySubmit(promise);
    }
}
