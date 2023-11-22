@extends('_layout.index')

@section('style')
    @php
    try {
    $filename = 'assets/css/print.css';
    $fileModified = substr(md5(filemtime($filename)), 0, 6);
    } catch (\Exception) {
    $fileModified = '';
    }
    @endphp
    <link href="{{$THEME::CSS_PATH}}/print.css?v={{$fileModified}}" rel="stylesheet">
@endsection

@section('content')
    @php
    function getWeightBridgeText(string $weightBridge)
    {
        if (in_array($weightBridge, App\Constants\WeightBridge::toArray())) {
            return __('tfactor.weight_bridge_' . $weightBridge);
        }
        return __('tfactor.weight_bridge_undefined');
    }
    @endphp

    <h4 class="print">{{ __('tfactor.from_date') }} {{$fromDate}} {{ __('tfactor.to_date') }} {{$toDate}}</h4>
    <table class="print" cellpadding="5" cellspacing="5">
        <thead>
            <tr>
                <th>#</th>
                <th>{{ __('tfactor.weight_bridge') }}</th>
                <th>{{ __('tfactor.factor_id_print') }}</th>
                <th>{{ __('tfactor.car_number_print') }}</th>
                <th>{{ __('tfactor.driver') }}</th>
                <th>{{ __('tfactor.current_date') }}</th>
                <th>{{ __('tfactor.prev_weight') }}</th>
                <th>{{ __('tfactor.current_weight') }}</th>
                <th>{{ __('tfactor.net_weight') }}</th>
                <th>
                    @if (isset($wb) && $wb === App\Constants\WeightBridge::WB_1)
                    {{ __('tfactor.buyer2') }}
                    @else
                    {{ __('tfactor.buyer') }}
                    @endif
                </th>
                <th>
                    @if (isset($wb) && $wb === App\Constants\WeightBridge::WB_1)
                    {{ __('tfactor.seller2') }}
                    @else
                    {{ __('tfactor.seller') }}
                    @endif
                </th>
                <th>{{ __('tfactor.good_name') }}</th>
                <th>{{ __('tfactor.factor_description1_list') }}</th>
            </tr>
        </thead>
        <tbody>
            @php $i = 0; @endphp
            @foreach ($items as $item)
            @php $i++; @endphp
            <tr>
                <td>{{number_format($i)}}</td>
                <td>{{getWeightBridgeText($item->weight_bridge)}}</td>
                <td>{{number_format($item->factor_id)}}</td>
                <td>{{$item->car_number1.' - '.$item->car_number2}}</td>
                <td>{{mb_strlen($item->driver) > 0 ? $item->driver : '-'}}</td>
                <td>{{$item->current_time}} - {{$item->current_date}}</td>
                <td>{{number_format($item->prev_weight)}}</td>
                <td>{{number_format($item->current_weight)}}</td>
                <td>{{number_format($item->current_weight - $item->prev_weight)}}</td>
                <td>{{mb_strlen($item->buyer_name) > 0 ? $item->buyer_name : '-'}}</td>
                <td>{{mb_strlen($item->seller_name) > 0 ? $item->seller_name : '-'}}</td>
                <td>{{$item->goods_name}}</td>
                <td>{{mb_strlen($item->factor_description1) > 0 ? $item->factor_description1 : '-'}}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{number_format($prevWeightSum)}}</td>
                <td>{{number_format($currentWeightSum)}}</td>
                <td>{{number_format($netWeightSum)}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
        </tfoot>
    </table>
@endsection