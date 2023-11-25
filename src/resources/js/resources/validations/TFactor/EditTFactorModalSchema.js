import * as yup from "yup";

import { stringValidator } from "../CommonValidators";
import { editTFactorModal as strings } from "../../../constants/strings/fa";

const editTFactorModalSchema = yup.object().shape({
    factorIdEditTFactorModal: stringValidator(
        yup.string(),
        strings.factorIdEditTFactorModal,
        null,
        15
    ),
    factorDescription1EditTFactorModal: stringValidator(
        yup.string(),
        strings.factorDescription1EditTFactorModal,
        null,
        15
    ),
});

export default editTFactorModalSchema;
