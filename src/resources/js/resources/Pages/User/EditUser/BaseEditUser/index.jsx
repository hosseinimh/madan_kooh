import React from "react";
import { useSelector } from "react-redux";

import {
    InputRadioColumn,
    InputTextColumn,
    FormPage,
    InputCheckboxColumn,
    InputCheckboxContainer,
    InputRadioContainer,
    AlertMessage,
    InputRow,
} from "../../../../components";
import { PageUtils } from "./PageUtils";
import { MESSAGE_TYPES, ROLES } from "../../../../../constants";
import { editUserPage as strings } from "../../../../../constants/strings/fa";

const BaseEditUser = ({ userId }) => {
    const userState = useSelector((state) => state.userReducer);
    const pageState = useSelector((state) => state.pageReducer);
    const pageUtils = new PageUtils(userId);

    return (
        <FormPage
            pageUtils={pageUtils}
            submitEnabled={userState?.user?.verifyRequest3At ? false : true}
            renderBefore={
                userState?.user?.verifyRequest3At && (
                    <AlertMessage
                        message={strings.editNotAllowed}
                        messageType={MESSAGE_TYPES.ERROR}
                    />
                )
            }
        >
            <InputRow>
                <InputTextColumn
                    field="name"
                    showLabel={true}
                    readonly={userState?.user?.verifyRequest3At ? true : false}
                    fullRow={false}
                    icon={"icon-personalcard4"}
                />
                <InputTextColumn
                    field="family"
                    showLabel={true}
                    readonly={userState?.user?.verifyRequest3At ? true : false}
                    fullRow={false}
                    icon={"icon-personalcard4"}
                />
                <InputTextColumn
                    field="mobile"
                    showLabel={true}
                    textAlign="left"
                    readonly={
                        userState?.user?.roles?.includes(ROLES.ADMIN)
                            ? false
                            : true
                    }
                    fullRow={false}
                    icon={"icon-mobile"}
                />
            </InputRow>
            {userState?.user?.roles?.includes(ROLES.ADMIN) && (
                <>
                    <InputCheckboxContainer>
                        <InputCheckboxColumn
                            name="isActiveContainer"
                            field="isActive"
                            disabled={userState?.user?.id === userId}
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
                                pageUtils.onSetItem(
                                    e.target.id,
                                    e.target.checked
                                )
                            }
                        />
                        <InputRadioColumn
                            name="role"
                            field="user"
                            onChange={(e) =>
                                pageUtils.onSetItem(
                                    e.target.id,
                                    e.target.checked
                                )
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
                                        string[permission.name] =
                                            permission.text;
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
                </>
            )}
        </FormPage>
    );
};

export default BaseEditUser;
