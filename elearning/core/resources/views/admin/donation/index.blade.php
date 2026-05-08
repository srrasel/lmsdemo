@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card b-radius--10 ">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two">
                            <thead>
                                <tr>
                                    <th>@lang('Date')</th>
                                    <th>@lang('Donor')</th>
                                    <th>@lang('Amount')</th>
                                    <th>@lang('Contact')</th>
                                    <th>@lang('Message')</th>
                                    <th>@lang('Status')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($donations as $donation)
                                    <tr>
                                        <td>{{ showDateTime($donation->created_at) }}</td>
                                        <td>
                                            @if($donation->user_id)
                                                <span class="font-weight-bold">{{ @$donation->user->fullname }}</span>
                                                <br>
                                                <span class="small">
                                                    <a href="{{ route('admin.users.detail', $donation->user_id) }}"><span>@</span>{{ @$donation->user->username }}</a>
                                                </span>
                                            @else
                                                <span class="font-weight-bold">{{ $donation->full_name ?? 'Guest' }}</span>
                                            @endif
                                        </td>
                                        <td>{{ showAmount($donation->amount) }} {{ __($general->cur_text) }}</td>
                                        <td>
                                            {{ $donation->email }}<br>
                                            {{ $donation->mobile }}
                                        </td>
                                        <td>{{ Str::limit($donation->message, 50) }}</td>
                                        <td>
                                            @php
                                                echo $donation->statusBadge;
                                            @endphp
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td class="text-muted text-center" colspan="100%">{{ __($emptyMessage) }}</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if ($donations->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($donations) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
