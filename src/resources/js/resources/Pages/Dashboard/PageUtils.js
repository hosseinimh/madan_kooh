import { ROLES } from "../../../constants";
import { dashboardPage as strings } from "../../../constants/strings/fa";
import { Dashboard as Entity } from "../../../http/entities";
import {
    setLoadingAction,
    setNotificationsAction,
} from "../../../state/layout/layoutActions";
import { setPagePropsAction } from "../../../state/page/pageActions";
import { BasePageUtils } from "../../../utils/BasePageUtils";

export class PageUtils extends BasePageUtils {
    constructor(useForm) {
        super("Dashboard", strings, useForm);
        this.entity = new Entity();
        this.initialPageProps = {
            weightSumWB1: null,
            weightSumWB2: null,
        };
    }

    onLoad() {
        super.onLoad();
        this.dispatch(setPagePropsAction(this.initialPageProps));
        this.fillForm();
    }

    async fillForm(data = null) {
        this.dispatch(setLoadingAction(true));
        await this.fetchData(data);
        this.dispatch(setLoadingAction(false));
    }

    async fetchData() {
        try {
            let result = this.userState?.user?.roles?.includes(ROLES.ADMIN)
                ? await this.entity.indexWithAdmin()
                : await this.entity.index();
            this.handleFetchResult(
                result,
                this.propsIfOK(result),
                this.propsIfNull()
            );
        } catch {}
    }

    propsIfOK(result) {
        this.dispatch(
            setNotificationsAction({
                systemNotifications: result.systemNotifications,
                userNotifications: result.userNotifications,
            })
        );
        return {
            weightSumWB1: result.weightSumWB1,
            weightSumWB2: result.weightSumWB2,
        };
    }
}
