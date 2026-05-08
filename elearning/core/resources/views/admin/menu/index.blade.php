@extends('admin.layouts.app')
@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body p-0">
                    <div class="table-responsive--sm table-responsive">
                        <table class="table table--light style--two bg-white">
                            <thead>
                                <tr>
                                    <th>@lang('Name')</th>
                                    <th>@lang('URL')</th>
                                    <th>@lang('Order')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($menus as $menu)
                                    <tr>
                                        <td>{{ __($menu->name) }}</td>
                                        <td>{{ $menu->url }}</td>
                                        <td>{{ $menu->order }}</td>
                                        <td>
                                            @php
                                                echo $menu->statusBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <button class="btn btn-sm btn-outline--primary editMenu" 
                                                    data-id="{{ $menu->id }}" 
                                                    data-name="{{ $menu->name }}" 
                                                    data-url="{{ $menu->url }}" 
                                                    data-order="{{ $menu->order }}" 
                                                    data-status="{{ $menu->status }}"
                                                >
                                                    <i class="las la-pen"></i>@lang('Edit')
                                                </button>
                                                
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn"
                                                    data-action="{{ route('admin.menu.delete', $menu->id) }}"
                                                    data-question="@lang('Are you sure you want to delete this menu?')">
                                                    <i class="las la-trash"></i>@lang('Delete')
                                                </button>
                                            </div>
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
            </div>
        </div>
    </div>

    <x-confirmation-modal />

    <div class="modal fade" id="menuModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modalTitle">@lang('Add Menu')</h4>
                    <button type="button" class="close" data-bs-dismiss="modal"><i class="las la-times"></i></button>
                </div>
                <form method="post" action="{{ route('admin.menu.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('URL')</label>
                            <input type="text" class="form-control" name="url" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Order')</label>
                            <input type="number" class="form-control" name="order" value="0">
                        </div>
                        <div class="form-group">
                            <label>@lang('Status')</label>
                            <input type="checkbox" data-width="100%" data-onstyle="-success" data-offstyle="-danger" data-bs-toggle="toggle" data-on="@lang('Active')" data-off="@lang('Inactive')" name="status">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('breadcrumb-plugins')
        <button class="btn btn-sm btn-outline--primary addMenu"><i class="las la-plus"></i> @lang('Add New')</button>
    @endpush

    @push('script')
        <script>
            (function($) {
                "use strict";
                $('.addMenu').on('click', function() {
                    var modal = $('#menuModal');
                    modal.find('.modal-title').text("@lang('Add Menu')");
                    modal.find('form')[0].reset();
                    modal.find('form').attr('action', "{{ route('admin.menu.store') }}");
                    modal.modal('show');
                });

                $('.editMenu').on('click', function() {
                    var modal = $('#menuModal');
                    var data = $(this).data();
                    modal.find('.modal-title').text("@lang('Edit Menu')");
                    modal.find('input[name=name]').val(data.name);
                    modal.find('input[name=url]').val(data.url);
                    modal.find('input[name=order]').val(data.order);
                    
                    if (data.status == 1) {
                        modal.find('input[name=status]').bootstrapToggle('on');
                    } else {
                        modal.find('input[name=status]').bootstrapToggle('off');
                    }

                    modal.find('form').attr('action', "{{ route('admin.menu.update', '') }}/" + data.id);
                    modal.modal('show');
                });
            })(jQuery);
        </script>
    @endpush
@endsection
