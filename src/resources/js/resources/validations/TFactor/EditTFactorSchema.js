import * as yup from "yup";

import { stringValidator } from "../CommonValidators";
import { tfactorsPage as strings } from "../../../constants/strings/fa";

const editTFactorSchema = yup.object().shape({
    factorId: stringValidator(yup.string(), strings.factorId, null, 15),
    factorDescription1: stringValidator(
        yup.string(),
        strings.factorDescription1,
        null,
        15
    ),
});

export default editTFactorSchema;
