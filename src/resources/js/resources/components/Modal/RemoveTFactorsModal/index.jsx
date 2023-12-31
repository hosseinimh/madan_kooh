import React, { useEffect, useState } from "react";
import { useSelector } from "react-redux";
import { useForm } from "react-hook-form";
import { yupResolver } from "@hookform/resolvers/yup";

import { AlertMessage, InputRow, InputTextColumn, Modal } from "../..";
import { MODAL_RESULT } from "../../../../constants";
import {
    general,
    removeTFactorsModal as strings,
} from "../../../../constants/strings/fa";
import { removeTFactorModalSchema as schema } from "../../../validations";

function RemoveTFactorsModal() {
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
                    .querySelector("#removeTFactorsModal")
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
                    factorId: form.getValues("factorIdRemoveTFactorsModal"),
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
        if (layoutState?.shownModal?.modal === "removeTFactorsModal") {
            setMessage(null);
            form.setValue("factorIdRemoveTFactorsModal", "");
        }
    }, [layoutState?.shownModal]);

    const onSubmit = () => {
        setModalResult(MODAL_RESULT.OK);
    };

    const renderFooter = () => {
        return (
            <div className="btns d-flex mtd-10">
                <button
                    className="btn btn-dark-warning"
                    type="button"
                    title={general.remove}
                    onClick={form.handleSubmit(onSubmit)}
                >
                    {general.remove}
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
            id="removeTFactorsModal"
            title={strings._title}
            modalResult={modalResult}
            footer={renderFooter()}
        >
            <AlertMessage message={message} containerClassName="mb-30" />
            <InputRow>
                <InputTextColumn
                    field="factorIdRemoveTFactorsModal"
                    showLabel
                    useForm={form}
                    strings={strings}
                    fullRow={false}
                />
                <div></div>
                <div></div>
            </InputRow>
        </Modal>
    );
}

export default RemoveTFactorsModal;
