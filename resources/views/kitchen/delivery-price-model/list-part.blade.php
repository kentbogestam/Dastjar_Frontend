<div class="row">
    <div class="col-md-12">
        <table class="table table-bordered">
            <thead>
            <tr>
                <th>{{ __('messages.deliveryType') }}</th>
                <th>{{ __('messages.delivery_charge') }}</th>
                <th>{{ __('messages.threshold') }}</th>
                <th>{{ __('messages.action') }}</th>
            </tr>
            </thead>
            <tbody>
            @if( !$deliveryPriceModel->isEmpty() )
                @foreach($deliveryPriceModel as $row)
                    <tr>
                        <td>
                            @if($row->delivery_rule_id == 1)
                                {{ __('messages.ruleDeliveryType1') }}
                            @elseif($row->delivery_rule_id == 2)
                                {{ __('messages.ruleDeliveryType2') }}
                            @elseif($row->delivery_rule_id == 3)
                                {{ __('messages.ruleDeliveryType3') }}
                            @elseif($row->delivery_rule_id == 4)
                                {{ __('messages.ruleDeliveryType4') }}
                            @elseif($row->delivery_rule_id == 5)
                                {{ __('messages.ruleDeliveryType5') }}
                            @endif
                        </td>
                        <td>{{ $row->delivery_charge }}</td>
                        <td>{{ $row->threshold }}</td>
                        <td>
                            <snap class="btn-link" onclick="getDeliveryPrice('{{ $row->id }}', 1)"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></snap>
                            <a href="{{ url('kitchen/delivery-price-model/'.$row->id.'/delete') }}" onclick="return confirmDelete()" data-ajax="false">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </a>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="4" class="text-center">{{ __('messages.noRecordFound') }}</td>
                </tr>
            @endif
            </tbody>
        </table>
    </div>
</div>