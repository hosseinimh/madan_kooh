import * as yup from "yup";

import { dateValidator, stringValidator } from "../CommonValidators";
import { tfactorsPage as strings } from "../../../constants/strings/fa";

const searchTFactorsSchema = yup.object().shape({
    fromDate: dateValidator(yup.string(), strings.fromDate, false),
    toDate: dateValidator(yup.string(), strings.toDate, false),
    factorId: stringValidator(yup.string(), strings.factorId, null, 15, false),
    factorDescription1: stringValidator(
        yup.string(),
        strings.factorDescription1,
        null,
        15,
        false
    ),
});

export default searchTFactorsSchema;
