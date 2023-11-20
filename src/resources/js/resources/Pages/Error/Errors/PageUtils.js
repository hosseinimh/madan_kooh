import { useForm } from "react-hook-form";

import { Error as Entity } from "../../../../http/entities";
import { BasePageUtils } from "../../../../utils/BasePageUtils";
import { errorsPage as strings } from "../../../../constants/strings/fa";
import { clearMessageAction } from "../../../../state/message/messageActions";
import { setShownModalAction } from "../../../../state/layout/layoutActions";

export class PageUtils extends BasePageUtils {
    constructor() {
        const form = useForm();
        super("Errors", strings, form);
        this.entity = new Entity();
        this.initialPageProps = {
            pageNumber: 1,
            item: null,
            items: null,
            action: null,
        };
    }

    onLoad() {
        super.onLoad();
        this.fillForm();
    }

    async fillForm() {
        const promise = this.entity.getPaginate(
            this.pageState.props?.pageNumber ?? 1
        );
        super.fillForm(promise);
    }

    showErrorModal(e, item) {
        this.dispatch(clearMessageAction());
        e.stopPropagation();
        this.dispatch(
            setShownModalAction("errorModal", {
                error: item,
            })
        );
    }
}
