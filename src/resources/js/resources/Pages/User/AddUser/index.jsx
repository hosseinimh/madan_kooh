import React, { useEffect, useState } from "react";
import { useSelector } from "react-redux";

import {
    InputRadioColumn,
    InputTextColumn,
    FormPage,
    InputCheckboxColumn,
    InputCheckboxContainer,
    InputRadioContainer,
    InputRow,
} from "../../../components";
import { PageUtils } from "./PageUtils";
import { addUserPage as strings } from "../../../../constants/strings/fa";
import { PERMISSIONS } from "../../../../constants";

const AddUser = () => {
    const [showPass, setShowPass] = useState(false);
    const [showConfirmPass, setShowConfirmPass] = useState(false);
    const pageState = useSelector((state) => state.pageReducer);
    const pageUtils = new PageUtils();

    useEffect(() => {
        const element = document.querySelector("#password");
        if (!element) {
            return;
        }
        if (showPass) {
            element.setAttribute("type", "text");
        } else {
            element.setAttribute("type", "password");
        }
    }, [showPass]);

    useEffect(() => {
        const element = document.querySelector("#confirmPassword");
        if (!element) {
            return;
        }
        if (showConfirmPass) {
            element.setAttribute("type", "text");
        } else {
            element.setAttribute("type", "password");
        }
    }, [showConfirmPass]);

    useEffect(() => {
        if (pageState?.props?.permissions?.length > 0) {
            pageUtils.useForm.setValue(
                `permissionsContainer.${PERMISSIONS.READ_ALL_WBS}`,
                true
            );
        }
    }, [pageState?.props?.permissions]);

    return (
        <FormPage pageUtils={pageUtils}>
            <InputRow>
                <InputTextColumn
                    field="username"
                    textAlign="left"
                    inputClassName="autofill"
                    fullRow={false}
                    icon={"icon-frame-14"}
                />
                <InputTextColumn
                    field="password"
                    type="password"
                    textAlign="left"
                    inputClassName="autofill"
                    fullRow={false}
                    icon={`icon-eye3 icon-clickable${showPass ? " show" : ""}`}
                    iconClick={() => setShowPass(!showPass)}
                />
                <InputTextColumn
                    field="confirmPassword"
                    type="password"
                    textAlign="left"
                    inputClassName="autofill"
                    fullRow={false}
                    icon={`icon-eye3 icon-clickable${
                        showConfirmPass ? " show" : ""
                    }`}
                    iconClick={() => setShowConfirmPass(!showConfirmPass)}
                />
            </InputRow>
            <InputRow>
                <InputTextColumn
                    field="name"
                    inputClassName="autofill"
                    fullRow={false}
                    icon={"icon-personalcard4"}
                />
                <InputTextColumn
                    field="family"
                    inputClassName="autofill"
                    fullRow={false}
                    icon={"icon-personalcard4"}
                />
                <InputTextColumn
                    field="mobile"
                    textAlign="left"
                    inputClassName="autofill"
                    fullRow={false}
                    icon={"icon-mobile"}
                />
            </InputRow>
            <InputCheckboxContainer>
                <InputCheckboxColumn
                    name="isActiveContainer"
                    field="isActive"
                    checked={true}
                />
            </InputCheckboxContainer>
            <InputRadioContainer
                label={strings.type}
                containerClassName="d-flex-column"
            >
                <InputRadioColumn
                    name="role"
                    field="administrator"
                    onChange={(e) =>
                        pageUtils.onSetItem(e.target.id, e.target.checked)
                    }
                />
                <InputRadioColumn
                    name="role"
                    field="user"
                    onChange={(e) =>
                        pageUtils.onSetItem(e.target.id, e.target.checked)
                    }
                />
            </InputRadioContainer>
            {pageUtils?.useForm?.getValues("user") && (
                <>
                    <p>{strings.permissions}</p>
                    <InputCheckboxContainer containerClassName="mx-20">
                        {pageState?.props?.permissions?.map(
                            (permission, index) => {
                                let string = {};
                                string[permission.name] = permission.text;
                                return (
                                    <InputCheckboxColumn
                                        key={index}
                                        name={`permissionsContainer.[${permission.name}]`}
                                        field={permission.name}
                                        strings={string}
                                    />
                                );
                            }
                        )}
                    </InputCheckboxContainer>
                </>
            )}
        </FormPage>
    );
};

export default AddUser;
