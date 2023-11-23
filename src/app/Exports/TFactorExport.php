<?php

namespace App\Exports;

use App\Constants\WeightBridge;
use App\Facades\Helper;
use App\Services\TFactorService;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithDefaultStyles;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class TFactorExport implements FromArray, WithMapping, WithHeadings, ShouldAutoSize, WithDefaultStyles, WithStyles, WithEvents
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

    public function registerEvents(): array
    {
        return [
            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->getDelegate()->setRightToLeft(true);
            },
        ];
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
            '#',
            __('tfactor.weight_bridge'),
            __('tfactor.factor_id'),
            __('tfactor.car_number'),
            __('tfactor.driver'),
            __('tfactor.current_date'),
            __('tfactor.prev_weight'),
            __('tfactor.current_weight'),
            __('tfactor.net_weight'),
            $this->weightBridge === WeightBridge::WB_1 ? __('tfactor.buyer2') : __('tfactor.buyer'),
            $this->weightBridge === WeightBridge::WB_1 ? __('tfactor.seller2') : __('tfactor.seller'),
            __('tfactor.good_name'),
            __('tfactor.factor_description1'),
            __('tfactor.user'),
        ];
    }

    public function array(): array
    {
        $tfactorService = new TFactorService();
        $array = $tfactorService->getAll(
            $this->weightBridge,
            $this->fromDate,
            $this->toDate,
            $this->goodsName,
            $this->driver,
            $this->buyersName,
            $this->sellersName,
            $this->users,
            $this->factorId,
            $this->factorDescription1,
            $this->repetitionType
        );
        if (count($array) > 0) {
            $last = clone $array[0];
            $last->factor_id = '';
            $last->prev_weight = $last->prev_weight_sum;
            $last->current_weight = $last->current_weight_sum;
            array_push($array, $last);
        }
        return $array;
    }

    public function map($item): array
    {
        return strlen($item->factor_id) > 0 ?
            [
                $this->index++,
                Helper::getWeightBridgeText($item->weight_bridge),
                $item->factor_id,
                $item->car_number2 . ' - ' . $item->car_number1,
                $item->driver,
                $item->current_date . ' ' . $item->current_time,
                $item->prev_weight,
                $item->current_weight,
                $item->current_weight - $item->prev_weight,
                $item->buyer_name,
                $item->seller_name,
                $item->goods_name,
                $item->factor_description1,
                $item->user_name . ' ' . $item->user_family
            ] : [
                'ewr',
                '',
                '',
                '',
                '',
                '',
                $item->prev_weight,
                $item->current_weight,
                $item->current_weight - $item->prev_weight,
                '',
                '',
                '',
                '',
                ''
            ];
    }
}
