import { useSelector } from "react-redux";
import { useForm } from "react-hook-form";
import { yupResolver } from "@hookform/resolvers/yup";

import { User as Entity } from "../../../../../http/entities";
import {
    setPageAction,
    setPageTitleAction,
} from "../../../../../state/page/pageActions";
import { BasePageUtils } from "../../../../../utils/BasePageUtils";
import {
    BASE_PATH,
    MESSAGE_CODES,
    MESSAGE_TYPES,
    ROLES,
} from "../../../../../constants";
import { setLoadingAction } from "../../../../../state/layout/layoutActions";
import { editUserSchema as schema } from "../../../../validations";
import {
    editUserPage,
    editProfilePage,
} from "../../../../../constants/strings/fa";
import { setPagePropsAction } from "../../../../../state/page/pageActions";
import { setMessageAction } from "../../../../../state/message/messageActions";

export class PageUtils extends BasePageUtils {
    constructor(userId) {
        const form = useForm({
            resolver: yupResolver(schema),
        });
        const userState = useSelector((state) => state.userReducer);
        const strings =
            userState?.user?.id === userId ? editProfilePage : editUserPage;
        super("Users", strings, form);
        this.entity = new Entity();
        this.initialPageProps = {
            userId,
            permissions: [],
        };
        this.callbackUrl = `${BASE_PATH}/users`;
    }

    onLoad() {
        this.navigateIfNotValidateParams();
        super.onLoad();
        const name =
            this.initialPageProps.userId === this.userState?.user?.id
                ? "EditProfile"
                : "Users";
        this.dispatch(setPageAction(name));
        this.dispatch(setPagePropsAction(this.initialPageProps));
        this.fillForm(this.initialPageProps);
    }

    navigateIfNotValidateParams() {
        this.navigateIfNotValidId(this.initialPageProps.userId);
    }

    async fillForm(data) {
        try {
            this.dispatch(setLoadingAction(true));
            const result = await this.fetchItem(data.userId);
            this.navigateIfItemNotFound(result);
            this.navigateIfPermissionsNotFound(result?.permissions);
            this.handleFetchResult(result);
        } catch {
        } finally {
            this.dispatch(setLoadingAction(false));
        }
    }

    async fetchItem(id) {
        return this.userState?.user?.roles?.includes(ROLES.ADMIN)
            ? await this.entity.getWithAdmin(id)
            : await this.entity.get();
    }

    navigateIfPermissionsNotFound(permissions) {
        if (permissions === null) {
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
        this.useForm.setValue("name", result.item.name);
        this.useForm.setValue("family", result.item.family);
        this.useForm.setValue("mobile", result.item.mobile);
        this.useForm.setValue("isActiveContainer", result.item.isActive);
        this.useForm.setValue(
            result.item.roles?.includes(ROLES.ADMIN) ? "administrator" : "user",
            "on"
        );
        result.item.permissions?.forEach((permission) => {
            this.useForm.setValue(`permissionsContainer.${permission}`, true);
        });
        this.dispatch(
            setPageTitleAction(
                `${this.strings._title} [ ${result.item.name} ${result.item.family} - ${result.item.username} ]`,
                this.strings._subTitle
            )
        );
        this.dispatch(setPagePropsAction({ permissions: result.permissions }));
    }

    onSetItem(item, value) {
        let props = this.pageState?.props;
        props[item] = value;
        this.dispatch(setPagePropsAction(props));
    }

    async onSubmit(data) {
        const promise = this.userState?.user?.roles?.includes(ROLES.ADMIN)
            ? this.handleSubmitWithAdmin(data)
            : this.handleSubmit(data);
        this.onModifySubmit(promise);
    }

    async handleSubmit(data) {
        return this.entity.update(data.name, data.family, data.mobile);
    }

    async handleSubmitWithAdmin(data) {
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
        return this.entity.updateWithAdmin(
            this.pageState?.props?.userId,
            data.name,
            data.family,
            data.mobile,
            data.isActiveContainer ? 1 : 0,
            roles,
            permissions
        );
    }
}
