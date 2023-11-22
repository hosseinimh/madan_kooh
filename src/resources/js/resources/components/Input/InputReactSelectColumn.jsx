import React, { useState, useEffect } from "react";
import { useSelector } from "react-redux";
import Select from "react-select";

import InputRow from "./InputRow";
import { general } from "../../../constants/strings/fa";

const InputReactSelectColumn = ({
    field,
    useForm,
    strings,
    fullRow = true,
    showLabel = false,
    items = {},
    isMulti = true,
}) => {
    const layoutState = useSelector((state) => state.layoutReducer);
    const pageState = useSelector((state) => state.pageReducer);
    const messageState = useSelector((state) => state.messageReducer);
    const [label, setLabel] = useState(
        strings && field in strings ? strings[field] : ""
    );
    const [placeholder, setPlaceholder] = useState(
        strings && `${field}Placeholder` in strings
            ? strings[`${field}Placeholder`]
            : ""
    );
    const [form, setForm] = useState(useForm);

    useEffect(() => {
        if (!strings) {
            setLabel(
                pageState?.pageUtils?.strings &&
                    field in pageState.pageUtils.strings
                    ? pageState?.pageUtils?.strings[field]
                    : ""
            );
            setPlaceholder(
                pageState?.pageUtils?.strings &&
                    `${field}Placeholder` in pageState.pageUtils.strings
                    ? pageState.pageUtils.strings[`${field}Placeholder`]
                    : ""
            );
        }

        if (!useForm) {
            setForm(pageState?.pageUtils?.useForm);
        }
    }, [pageState]);

    const renderItem = () => {
        return (
            <div className="d-flex d-flex-column">
                {showLabel && <div className="input-info">{label}</div>}
                <div className="input-text input-bg input-border">
                    <Select
                        options={items}
                        isMulti={isMulti}
                        placeholder={placeholder}
                        disabled={layoutState?.loading}
                        unstyled={true}
                        className="react-select-container"
                        classNamePrefix="react-select"
                        onChange={(selectedOption) => {
                            form.setValue(field, selectedOption);
                        }}
                        noOptionsMessage={() => general.noDataFound}
                    />
                    {messageState?.messageField === field && (
                        <span className="error">{messageState?.message}</span>
                    )}
                </div>
            </div>
        );
    };

    if (form) {
        if (fullRow) {
            return <InputRow>{renderItem()}</InputRow>;
        }
        return renderItem();
    }
    return <></>;
};

export default InputReactSelectColumn;
