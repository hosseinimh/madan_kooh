import React, { useEffect, useState } from "react";
import { useSelector } from "react-redux";
import { useForm } from "react-hook-form";
import { yupResolver } from "@hookform/resolvers/yup";

import { AlertMessage, InputRow, InputTextColumn, Modal } from "../..";
import { MODAL_RESULT } from "../../../../constants";
import {
    general,
    editTFactorModal as strings,
} from "../../../../constants/strings/fa";
import { editTFactorModalSchema as schema } from "../../../validations";

function EditTFactorModal() {
    const layoutState = useSelector((state) => state.layoutReducer);
    const [modalResult, setModalResult] = useState(undefined);
    const [message, setMessage] = useState(null);
    const form = useForm({
        resolver: yupResolver(schema),
    });

    useEffect(() => {
        if (
            typeof form?.formState?.errors === "object" &&
            form?.formState?.errors
        ) {
            const hasKeys = !!Object.keys(form?.formState?.errors).length;
            if (hasKeys) {
                setMessage(
                    form?.formState?.errors[
                        Object.keys(form?.formState?.errors)[0]
                    ].message
                );
                document
                    .querySelector("#editTFactorModal")
                    .querySelector(".modal-main")
                    .firstChild.scrollTo(0, 0);
            }
        }
    }, [form?.formState?.errors]);

    useEffect(() => {
        if (modalResult === MODAL_RESULT.OK) {
            if (
                typeof layoutState?.shownModal?.props?.onSubmit === "function"
            ) {
                layoutState?.shownModal?.props?.onSubmit(true, {
                    factorId: form.getValues("factorIdEditTFactorModal"),
                    factorDescription1: form.getValues(
                        "factorDescription1EditTFactorModal"
                    ),
                });
            }
        } else if (modalResult === MODAL_RESULT.CANCEL) {
            if (
                typeof layoutState?.shownModal?.props?.onCancel === "function"
            ) {
                layoutState?.shownModal?.props?.onCancel();
            }
        }
        setModalResult(undefined);
    }, [modalResult]);

    useEffect(() => {
        if (layoutState?.shownModal?.modal === "editTFactorModal") {
            setMessage(null);
            form.setValue(
                "factorIdEditTFactorModal",
                layoutState?.shownModal?.props?.tfactor?.factorId ?? ""
            );
            form.setValue(
                "factorDescription1EditTFactorModal",
                layoutState?.shownModal?.props?.tfactor?.factorDescription1 ??
                    ""
            );
        }
    }, [layoutState?.shownModal]);

    const onSubmit = () => {
        setModalResult(MODAL_RESULT.OK);
    };

    const renderFooter = () => {
        return (
            <div className="btns d-flex mtd-10">
                <button
                    className="btn btn-success"
                    type="button"
                    title={general.edit}
                    onClick={form.handleSubmit(onSubmit)}
                >
                    {general.edit}
                </button>
                <button
                    className="btn btn-border"
                    type="button"
                    title={general.cancel}
                    onClick={() => setModalResult(MODAL_RESULT.CANCEL)}
                >
                    {general.cancel}
                </button>
            </div>
        );
    };

    return (
        <Modal
            id="editTFactorModal"
            title={strings._title}
            modalResult={modalResult}
            footer={renderFooter()}
        >
            <AlertMessage message={message} containerClassName="mb-30" />
            <InputRow>
                <InputTextColumn
                    field="factorIdEditTFactorModal"
                    useForm={form}
                    strings={strings}
                    fullRow={false}
                    showLabel
                />
                <InputTextColumn
                    field="factorDescription1EditTFactorModal"
                    useForm={form}
                    strings={strings}
                    fullRow={false}
                    showLabel
                />
            </InputRow>
        </Modal>
    );
}

export default EditTFactorModal;
