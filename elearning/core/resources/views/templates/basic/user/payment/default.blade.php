@extends($activeTemplate.'layouts.frontend')
@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card custom--card">
                <div class="card-header">
                    <h5 class="card-title">@lang('Payment Confirm')</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush text-center">
                        <li class="list-group-item d-flex justify-content-between">
                            @lang('Amount'):
                            <strong>{{showAmount($deposit->amount)}} {{$deposit->method_currency}}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            @lang('Charge'):
                            <strong>{{showAmount($deposit->charge)}} {{$deposit->method_currency}}</strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            @lang('Payable'):
                            <strong>{{showAmount($deposit->final_amount)}} {{$deposit->method_currency}}</strong>
                        </li>
                    </ul>
                    @if($deposit->method_code < 1000)
                        <form action="{{route('ipn.'.$deposit->gateway->alias)}}" method="POST" class="text-center mt-4">
                            @csrf
                            <input type="hidden" name="track" value="{{$deposit->trx}}">
                            <button type="submit" class="btn btn--base w-100">@lang('Pay Now')</button>
                        </form>
                    @else
                        <div class="text-center mt-4">
                            <a href="{{route('donation.payment.manual.confirm')}}" class="btn btn--base w-100">@lang('Pay Now')</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
