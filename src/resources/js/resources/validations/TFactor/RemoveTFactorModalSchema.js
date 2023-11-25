import * as yup from "yup";

import { stringValidator } from "../CommonValidators";
import { removeTFactorsModal as strings } from "../../../constants/strings/fa";

const removeTFactorModalSchema = yup.object().shape({
    factorIdRemoveTFactorsModal: stringValidator(
        yup.string(),
        strings.factorIdRemoveTFactorsModal,
        null,
        15
    ),
});

export default removeTFactorModalSchema;
