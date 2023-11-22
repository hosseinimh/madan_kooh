import { REPETITION_TYPES } from "..";
import { repetitionTypes as strings } from "../strings/fa";

export const repetitionTypes = [
    { id: REPETITION_TYPES.ALL, value: strings.all },
    { id: REPETITION_TYPES.LAST, value: strings.last },
    { id: REPETITION_TYPES.REPETITION, value: strings.repetition },
];
