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
                                    <th>@lang('Status')</th>
                                    <th>@lang('Actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categories as $category)
                                    <tr>
                                        <td>{{ __($category->name) }}</td>
                                        <td>
                                            @php
                                                echo $category->statusBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <button class="btn btn-sm btn-outline--primary editCategory" data-action="{{route('admin.blog.category.store', $category->id)}}" data-category="{{$category}}" ><i class="las la-pen"></i>@lang('Edit')</button>
                                                @if($category->status)
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{route('admin.blog.category.status', $category->id)}}" data-question="@lang('Are you sure you want to disable this category')" ><i class="las la-eye-slash"></i>@lang('Disable')</button>
                                                @else
                                                <button class="btn btn-sm btn-outline--success confirmationBtn" data-action="{{route('admin.blog.category.status', $category->id)}}" data-question="@lang('Are you sure you want to enable this category')" ><i class="las la-eye"></i>@lang('Enable')</button>
                                                @endif
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
                @if ($categories->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($categories) }}
                </div>
                @endif
            </div>
        </div>
    </div>

    <x-confirmation-modal />
    
    <div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">@lang('Add Category')</h4>
                    <button type="button" class="close" data-bs-dismiss="modal"><i class="las la-times"></i></button>
                </div>
                <form method="post" action="{{ route('admin.blog.category.store') }}">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Name')</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('breadcrumb-plugins')
    <button class="btn btn-sm btn-outline--primary addCategory"><i class="las la-plus"></i>@lang('Add New')</button>
    <x-search-form />
@endpush

@push('script')
    <script>
        (function($){
            "use strict";
            $('.addCategory').on('click', function() {
                var modal = $('#categoryModal');
                modal.find('.modal-title').text('@lang("Add Category")');
                modal.find('form')[0].reset();
                modal.find('form').attr('action', '{{ route("admin.blog.category.store") }}');
                modal.modal('show');
            });

            $('.editCategory').on('click', function() {
                var modal = $('#categoryModal');
                var category = $(this).data('category');
                var action = $(this).data('action');

                modal.find('.modal-title').text('@lang("Edit Category")');
                modal.find('input[name=name]').val(category.name);
                modal.find('form').attr('action', action);
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
