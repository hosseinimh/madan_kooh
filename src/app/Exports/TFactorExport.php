<?php

namespace App\Exports;

use App\Services\PatientFileService;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TFactorExport implements FromQuery, WithMapping, WithHeadings, ShouldAutoSize, WithDefaultStyles, WithStyles
{
    private int $index;

    public function __construct(
        private ?string $weightBridge,
        private string $fromDate,
        private string $toDate,
        private ?string $goodsName,
        private ?string $driver,
        private ?string $buyersName,
        private ?string $sellersName,
        private ?string $users,
        private ?string $factorId,
        private ?string $factorDescription1,
        private string $repetitionType
    ) {
        $this->index = 1;
    }

    public function defaultStyles(Style $defaultStyle)
    {
        return [
            'alignment' => [
                'vertical' => Alignment::VERTICAL_TOP,
            ],
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            'Z'  => ['alignment' => ['wrapText' => true]],
        ];
    }

    public function headings(): array
    {
        return [
            __('tfactor.weight_bridge'),
            __('tfactor.tfactor_id'),
            __('tfactor.car_number'),
            __('tfactor.driver'),
            __('tfactor.current_date'),
            __('tfactor.prev_weight'),
            __('tfactor.current_weight'),
            __('tfactor.net_weight'),
            __('tfactor.buyer'),
            __('tfactor.buyer2'),
            __('tfactor.seller'),
            __('tfactor.seller2'),
            __('tfactor.good_name'),
            __('tfactor.factor_description1'),
            __('tfactor.user'),
        ];
    }

    public function query()
    {
        $patientFileService = new PatientFileService();
        return $patientFileService->paginatedQuery(
            $this->fileNo,
            $this->name,
            $this->family,
            $this->birthDate,
            $this->lesionClassification,
            $this->specialLesionClassification,
            $this->systemicDiseaseHistory,
            $this->bloodDiseaseType,
            $this->hospitalizationReason,
            $this->continuingDrug,
            $this->weeklyDrug,
            $this->cancerType,
            $this->radiationPlace,
            $this->pregnancyWeek,
            $this->pregnancyNum,
            $this->pregnancyRank,
            $this->adExplanation,
            $this->sleepStatus,
            $this->functionalCapacity,
            $this->tobaccoUse,
            $this->useTobaccoDuration,
            $this->useTobaccoType,
            $this->drugUse,
            $this->useDrugDuration,
            $this->useDrugType,
            $this->alcohol,
            $this->retromolarArea,
            $this->gums,
            $this->toothlessRidge,
            $this->hardSoftPalate,
            $this->tongueDorsal,
            $this->tongueVentral,
            $this->tonguePharyngeal,
            $this->neurologicalChanges,
            $this->salivaryGrandExamination,
            $this->dentalChangesExamination,
            $this->probableDiagnosis,
            $this->difinitiveDiagnosis,
            $this->finalTreatmentPlan,
            $this->assistant,
            $this->master,
        );
    }

    public function prepareRows($rows)
    {
        return $rows->transform(function ($item) {
            return $item;
        });
    }

    public function map($item): array
    {
        return [
            $this->index++,
            $item->file_no,
            $item->factor_id,
            $item->car_number,
            $item->driver,
            $item->current_date,
            $item->prev_weight,
            $item->current_weight,
            $item->net_weight,
            $item->buyer,
            $item->buyer2,
            $item->seller,
            $item->seller2,
            $item->good_name,
            $item->factor_description1,
            $item->user
        ];
    }
}
