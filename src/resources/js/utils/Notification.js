import utils from "./Utils";
import { NOTIFICATION_SUB_CATEGORIES } from "../constants";

const getSubCategoryText = (item, locale) => {
    if (item.subCategory === NOTIFICATION_SUB_CATEGORIES.LOGIN_SUCCEED) {
        const { date, time } = utils.getTimezoneDate(item.createdAt, locale);
        return item.subCategoryText
            .replace(":field1", date)
            .replace(":field2", time)
            .replace(":field3", item.messageFields);
    } else if (item.subCategory === NOTIFICATION_SUB_CATEGORIES.LOGIN_FAILED) {
        const { date, time } = utils.getTimezoneDate(item.createdAt, locale);
        const messageFields = item.messageFields?.split("|");
        return item.subCategoryText
            .replace(":field1", messageFields[0])
            .replace(":field2", date)
            .replace(":field3", time)
            .replace(":field4", messageFields[1]);
    } else if (
        item.subCategory === NOTIFICATION_SUB_CATEGORIES.SEARCH_TFACTORS
    ) {
        const messageFields = item.messageFields;
        return item.subCategoryText.replace(":field1", messageFields);
    } else if (
        item.subCategory === NOTIFICATION_SUB_CATEGORIES.DELETE_TFACTORS
    ) {
        const messageFields = item.messageFields?.split("|");
        return item.subCategoryText
            .replace(":field1", messageFields[0])
            .replace(":field2", messageFields[1]);
    } else if (
        item.subCategory === NOTIFICATION_SUB_CATEGORIES.EDIT_FACTOR_DESCRIPTION
    ) {
        const messageFields = item.messageFields?.split("|");
        return item.subCategoryText
            .replace(":field1", messageFields[0])
            .replace(":field2", messageFields[1]);
    }
};

const notification = {
    getSubCategoryText,
};

export default notification;
