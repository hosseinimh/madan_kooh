import * as yup from "yup";

import { mobileValidator, nameValidator } from "../CommonValidators";
import { editUserPage as strings } from "../../../constants/strings/fa";

const editUserSchema = yup.object().shape({
    name: nameValidator(yup.string(), strings.name),
    family: nameValidator(yup.string(), strings.family),
    mobile: mobileValidator(yup.string(), strings.mobile),
    permissionsContainer: yup.object().when("user", {
        is: (user) => user === "on",
        then: (schema) =>
            schema.test("oneRequired", strings.noPermissionsSelected, (val) =>
                Object.values(val)?.find((v) => v)
            ),
    }),
});

export default editUserSchema;
