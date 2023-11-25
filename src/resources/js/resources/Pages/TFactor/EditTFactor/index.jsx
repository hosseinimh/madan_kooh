import React from "react";

import { InputTextColumn, FormPage, InputRow } from "../../../components";
import { PageUtils } from "./PageUtils";

const EditTFactor = () => {
    const pageUtils = new PageUtils();

    return (
        <FormPage pageUtils={pageUtils}>
            <InputRow>
                <InputTextColumn field="factorId" fullRow={false} showLabel />
                <InputTextColumn
                    field="factorDescription1"
                    fullRow={false}
                    showLabel
                />
                <div></div>
            </InputRow>
        </FormPage>
    );
};

export default EditTFactor;
