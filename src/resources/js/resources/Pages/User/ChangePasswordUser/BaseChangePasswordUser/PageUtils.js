import { useForm } from "react-hook-form";
import { yupResolver } from "@hookform/resolvers/yup";

import { User as Entity } from "../../../../../http/entities";
import {
    setPageAction,
    setPageTitleAction,
} from "../../../../../state/page/pageActions";
import { BasePageUtils } from "../../../../../utils/BasePageUtils";
import { BASE_PATH, ROLES } from "../../../../../constants";
import { setLoadingAction } from "../../../../../state/layout/layoutActions";
import { changePasswordUserSchema as schema } from "../../../../validations";
import { changePasswordUserPage as strings } from "../../../../../constants/strings/fa";
import { setPagePropsAction } from "../../../../../state/page/pageActions";

export class PageUtils extends BasePageUtils {
    constructor(userId) {
        const form = useForm({
            resolver: yupResolver(schema),
        });
        super("Users", strings, form);
        this.entity = new Entity();
        this.initialPageProps = {
            userId,
        };
        this.callbackUrl = this.userState?.user?.roles?.includes(ROLES.ADMIN)
            ? `${BASE_PATH}/users`
            : BASE_PATH;
    }

    onLoad() {
        this.navigateIfNotValidateParams();
        super.onLoad();
        const name =
            this.initialPageProps.userId === this.userState?.user?.id
                ? "ChangePasswordProfile"
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

    handleFetchResult(result) {
        if (this.userState?.user?.roles?.includes(ROLES.ADMIN)) {
            this.dispatch(
                setPageTitleAction(
                    `${this.strings._title} [ ${result.item.name} ${result.item.family} - ${result.item.username} ]`,
                    this.strings._subTitle
                )
            );
        }
    }

    async onSubmit(data) {
        const promise = this.userState?.user?.roles?.includes(ROLES.ADMIN)
            ? this.handleSubmitWithAdmin(data)
            : this.handleSubmit(data);
        this.onModifySubmit(promise);
    }

    async handleSubmit(data) {
        return this.entity.changePassword(
            data.newPassword,
            data.confirmPassword
        );
    }

    async handleSubmitWithAdmin(data) {
        return this.entity.changePasswordWithAdmin(
            this.pageState?.props?.userId,
            data.newPassword,
            data.confirmPassword
        );
    }
}
