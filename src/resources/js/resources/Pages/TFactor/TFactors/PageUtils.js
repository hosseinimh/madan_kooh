import { useForm } from "react-hook-form";
import { yupResolver } from "@hookform/resolvers/yup";

import { TFactor as Entity } from "../../../../http/entities";
import { BasePageUtils } from "../../../../utils/BasePageUtils";
import {
    BASE_PATH,
    PERMISSIONS,
    REPETITION_TYPES,
    ROLES,
    WEIGHT_BRIDGES,
} from "../../../../constants";
import { searchTfactorsSchema as schema } from "../../../validations";
import {
    general,
    tfactorsPage as strings,
} from "../../../../constants/strings/fa";
import { setShownModalAction } from "../../../../state/layout/layoutActions";
import { setPagePropsAction } from "../../../../state/page/pageActions";
import utils from "../../../../utils/Utils";
import { weightBridges } from "../../../../constants/lists";

export class PageUtils extends BasePageUtils {
    constructor() {
        const form = useForm({
            resolver: yupResolver(schema),
        });
        super("TFactors", strings, form);
        this.entity = new Entity();
        this.initialPageProps = {
            prevPageNumber: 1,
            pageNumber: 1,
            itemsCount: 0,
            item: null,
            items: null,
            currentWeightSum: 0,
            prevWeightSum: 0,
            action: null,
            searchFields: null,
            goodsName: null,
            driver: null,
            buyersName: null,
            sellersName: null,
            users: null,
        };
        this.onExcel = this.onExcel.bind(this);
        this.onPrint = this.onPrint.bind(this);
        this.handlePromptSubmit = this.handlePromptSubmit.bind(this);
    }

    onLoad() {
        super.onLoad();
        this.useForm.setValue(
            "fromDate",
            utils.toNumericLocaleDateString(Date.now(), general.locale)
        );
        this.useForm.setValue(
            "toDate",
            utils.toNumericLocaleDateString(Date.now(), general.locale)
        );
        this.useForm.setValue("repetitionType", REPETITION_TYPES.ALL);
        this.fillForm(this.getSearchFields(), true);
    }

    onAction(props) {
        switch (props.action) {
            case "SET_PAGE":
                props.action = null;
                this.onSubmit(this.getSearchFields());

                break;
        }

        super.onAction(props);
    }

    onExcel() {
        let searchFields = this.pageState?.props?.searchFields;
        let url = `${BASE_PATH}/excel/p_files?file_no=${searchFields?.fileNo}&name=${searchFields?.name}&family=${searchFields?.family}&blood_disease_type=${searchFields?.bloodDiseaseType}&hospitalization_reason=${searchFields?.hospitalizationReason}&continuing_drug=${searchFields?.continuingDrug}&weekly_drug=${searchFields?.weeklyDrug}&cancer_type=${searchFields?.cancerType}&radiation_place=${searchFields?.radiationPlace}&pregnancy_week=${searchFields?.pregnancyWeek}&pregnancy_num=${searchFields?.pregnancyNum}&pregnancy_rank=${searchFields?.pregnancyRank}&ad_explanation=${searchFields?.adExplanation}&sleep_status=${searchFields?.sleepStatus}&functional_capacity=${searchFields?.functionalCapacity}&use_tobacco_duration=${searchFields?.useTobaccoDuration}&use_tobacco_type=${searchFields?.useTobaccoType}&use_drug_duration=${searchFields?.useDrugDuration}&use_drug_type=${searchFields?.useDrugType}&retromolar_area=${searchFields?.retromolarArea}&gums=${searchFields?.gums}&toothless_ridge=${searchFields?.toothlessRidge}&hard_soft_palate=${searchFields?.hardSoftPalate}&tongue_dorsal=${searchFields?.tongueDorsal}&tongue_ventral=${searchFields?.tongueVentral}&tongue_pharyngeal=${searchFields?.tonguePharyngeal}&neurological_changes=${searchFields?.neurologicalChanges}&salivary_grand_examination=${searchFields?.salivaryGrandExamination}&dental_changes_examination=${searchFields?.dentalChangesExamination}&probable_diagnosis=${searchFields?.probableDiagnosis}&difinitive_diagnosis=${searchFields?.difinitiveDiagnosis}&final_treatment_plan=${searchFields?.finalTreatmentPlan}&assistant=${searchFields?.assistant}&master=${searchFields?.master}`;
        if (searchFields?.birthDate) {
            url = `${url}&birth_date=${searchFields.birthDate}`;
        }
        if (searchFields?.lesionClassification) {
            url = `${url}&lesion_classification=${searchFields.lesionClassification}`;
        }
        if (searchFields?.specialLesionClassification) {
            url = `${url}&special_lesion_classification=${searchFields.specialLesionClassification}`;
        }
        if (searchFields?.systemicDiseaseHistory) {
            url = `${url}&systemic_disease_history=${searchFields.systemicDiseaseHistory}`;
        }
        if (searchFields?.tobaccoUse) {
            url = `${url}&tobacco_use=${searchFields.tobaccoUse}`;
        } else {
            url = `${url}&tobacco_use=0`;
        }
        if (searchFields?.drugUse) {
            url = `${url}&drug_use=${searchFields.drugUse}`;
        } else {
            url = `${url}&drug_use=0`;
        }
        if (searchFields?.alcohol) {
            url = `${url}&alcohol=${searchFields.alcohol}`;
        } else {
            url = `${url}&alcohol=0`;
        }
        window.open(url, "_blank").focus();
    }

    onPrint() {
        let searchFields = this.pageState?.props?.searchFields;
        let url = `${BASE_PATH}/tfactors/print?weight_bridge=${searchFields?.weightBridge}&from_date=${searchFields?.fromDate}&to_date=${searchFields?.toDate}&goods_name=${searchFields?.goodsName}&driver=${searchFields?.driver}&buyers_name=${searchFields?.buyersName}&sellers_name=${searchFields?.sellersName}&users=${searchFields?.users}&factor_id=${searchFields?.factorId}&factor_description1=${searchFields?.factorDescription1}&repetition_type=${searchFields?.repetitionType}`;
        window.open(url, "_blank").focus();
    }

    onRemove(e, item) {
        e.stopPropagation();
        this.promptItem = item;
        this.dispatch(
            setShownModalAction("promptModal", {
                title: strings.removeMessageTitle,
                description: `${item.name} ${item.family} - ${item.nationalNo}`,
                submitTitle: general.yes,
                cancelTitle: general.no,
                onSubmit: this.handlePromptSubmit,
            })
        );
    }

    getPermittedWeightBridges() {
        return weightBridges.filter((weightBridge) => {
            if (
                weightBridge.id === WEIGHT_BRIDGES.ALL_WBS &&
                (this.userState?.user?.roles?.includes(ROLES.ADMIN) ||
                    this.userState?.user?.permissions?.includes(
                        PERMISSIONS.READ_ALL_WBS
                    ))
            ) {
                return true;
            } else if (
                weightBridge.id === WEIGHT_BRIDGES.WB_1 &&
                (this.userState?.user?.roles?.includes(ROLES.ADMIN) ||
                    this.userState?.user?.permissions?.includes(
                        PERMISSIONS.READ_WB_1
                    ))
            ) {
                return true;
            } else if (
                weightBridge.id === WEIGHT_BRIDGES.WB_2 &&
                (this.userState?.user?.roles?.includes(ROLES.ADMIN) ||
                    this.userState?.user?.permissions?.includes(
                        PERMISSIONS.READ_WB_2
                    ))
            ) {
                return true;
            } else {
                return false;
            }
        });
    }

    getSearchFields = () => {
        let searchFields = {
            weightBridge: this.useForm.getValues("weightBridge") ?? "",
            fromDate: this.useForm.getValues("fromDate") ?? "",
            toDate: this.useForm.getValues("toDate") ?? "",
            goodsName: this.useForm.getValues("goodsName") ?? "",
            driver: this.useForm.getValues("driver") ?? "",
            buyersName: this.useForm.getValues("buyersName") ?? "",
            sellersName: this.useForm.getValues("sellersName") ?? "",
            users: this.useForm.getValues("users") ?? "",
            factorId: this.useForm.getValues("factorId") ?? "",
            factorDescription1:
                this.useForm.getValues("factorDescription1") ?? "",
            repetitionType: this.useForm.getValues("repetitionType") ?? "",
        };
        if (searchFields.weightBridge === "") {
            const weightBridge = this.getPermittedWeightBridges()[0].id;
            searchFields.weightBridge = weightBridge;
            this.useForm.setValue("weightBridge", `${weightBridge}`);
        }
        searchFields.fromDate = searchFields.fromDate.replaceAll("/", "");
        searchFields.toDate = searchFields.toDate.replaceAll("/", "");
        if (searchFields.fromDate === "") {
            this.useForm.setValue(
                "fromDate",
                utils.toNumericLocaleDateString(Date.now(), general.locale)
            );
            searchFields.fromDate = this.useForm.getValues("fromDate");
        }
        if (searchFields.toDate === "") {
            this.useForm.setValue(
                "toDate",
                utils.toNumericLocaleDateString(Date.now(), general.locale)
            );
            searchFields.toDate = this.useForm.getValues("toDate");
        }
        if (searchFields.goodsName?.length > 0) {
            searchFields.goodsName = searchFields.goodsName
                .map((item) => item.value)
                .join(",");
        } else {
            searchFields.goodsName = "";
        }
        if (searchFields.buyersName?.length > 0) {
            searchFields.buyersName = searchFields.buyersName
                .map((item) => item.value)
                .join(",");
        } else {
            searchFields.buyersName = "";
        }
        if (searchFields.sellersName?.length > 0) {
            searchFields.sellersName = searchFields.sellersName
                .map((item) => item.value)
                .join(",");
        } else {
            searchFields.sellersName = "";
        }
        if (searchFields.users?.length > 0) {
            searchFields.users = searchFields.users
                .map((item) => item.value)
                .join(",");
        } else {
            searchFields.users = "";
        }
        return searchFields;
    };

    async fillForm(data, withProps = false) {
        this.withProps = withProps;
        const promise = withProps
            ? this.entity.getPaginateWithProps(
                  data.weightBridge,
                  data.fromDate,
                  data.toDate,
                  data.goodsName,
                  data.driver,
                  data.buyersName,
                  data.sellersName,
                  data.users,
                  data.factorId,
                  data.factorDescription1,
                  data.repetitionType,
                  this.pageState.props?.pageNumber ?? 1
              )
            : this.entity.getPaginate(
                  data.weightBridge,
                  data.fromDate,
                  data.toDate,
                  data.goodsName,
                  data.driver,
                  data.buyersName,
                  data.sellersName,
                  data.users,
                  data.factorId,
                  data.factorDescription1,
                  data.repetitionType,
                  this.pageState.props?.pageNumber ?? 1
              );
        this.dispatch(setPagePropsAction({ searchFields: { ...data } }));
        super.fillForm(promise);
    }

    propsIfOK(result) {
        try {
            return this.withProps
                ? {
                      prevPageNumber: this.pageState?.props?.pageNumber ?? 1,
                      items: result.items,
                      itemsCount: result.count,
                      currentWeightSum: result.currentWeightSum,
                      prevWeightSum: result.prevWeightSum,
                      goodsName: result.goodsName?.map((item) => ({
                          value: item.goods_name,
                          label: item.goods_name,
                      })),
                      drivers: result.drivers?.map((item) => ({
                          id: item.driver,
                          value: item.driver,
                      })),
                      buyersName: result.buyersName?.map((item) => ({
                          value: item.buyer_name,
                          label: item.buyer_name,
                      })),
                      sellersName: result.sellersName?.map((item) => ({
                          value: item.seller_name,
                          label: item.seller_name,
                      })),
                      users: result.users?.map((item) => ({
                          value: item.user_id,
                          label: `${item.user_name} ${item.user_family} [ ${item.user_id} ]`,
                      })),
                  }
                : {
                      prevPageNumber: this.pageState?.props?.pageNumber ?? 1,
                      items: result.items,
                      itemsCount: result.count,
                      currentWeightSum: result.currentWeightSum,
                      prevWeightSum: result.prevWeightSum,
                  };
        } catch {}
    }

    onSubmit() {
        this.onSendRequest();
        this.fillForm(this.getSearchFields());
    }

    handlePromptSubmit(result) {
        if (result === true) {
            const promise = this.entity.delete(this.promptItem?.id);
            super.onSelfSubmit(promise, {
                pageNumber: 1,
            });
        }
    }

    onReset() {
        super.onReset();
        this.useForm.setValue("repetitionType", REPETITION_TYPES.ALL);
    }
}
