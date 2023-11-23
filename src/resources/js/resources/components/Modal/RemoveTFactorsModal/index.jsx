import React, { useEffect, useState } from "react";
import { useSelector } from "react-redux";
import { useForm } from "react-hook-form";

import { InputRow, InputTextColumn, Modal } from "../..";
import { MODAL_RESULT } from "../../../../constants";
import {
    general,
    removeTFactorsModal as strings,
} from "../../../../constants/strings/fa";

function RemoveTFactorsModal() {
    const layoutState = useSelector((state) => state.layoutReducer);
    const [modalResult, setModalResult] = useState(undefined);
    const form = useForm();

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
                    onClick={onSubmit}
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
            title={strings.removeTFactorsModalTitle}
            modalResult={modalResult}
            footer={renderFooter()}
        >
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
