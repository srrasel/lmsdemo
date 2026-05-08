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
                                    <th>@lang('Question')</th>
                                    <th>@lang('Image')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($faqs as $faq)
                                    <tr>
                                        <td>{{ __($faq->question) }}</td>
                                        <td>
                                            @if($faq->image)
                                                <img src="{{ getImage(getFilePath('faq') . '/' . $faq->image) }}" alt="@lang('image')" style="width: 50px;">
                                            @else
                                                @lang('No Image')
                                            @endif
                                        </td>
                                        <td>
                                            @php
                                                echo $faq->statusBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <button class="btn btn-sm btn-outline--primary cuModalBtn" data-resource="{{ $faq }}" data-image="{{ $faq->image ? getImage(getFilePath('faq') . '/' . $faq->image) : '' }}" data-modal_title="@lang('Edit FAQ')" type="button">
                                                    <i class="la la-pencil"></i> @lang('Edit')
                                                </button>
                                                @if($faq->status == 1)
                                                    <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to disable this FAQ?')" data-action="{{ route('admin.faq.status', $faq->id) }}">
                                                        <i class="la la-eye-slash"></i> @lang('Disable')
                                                    </button>
                                                @else
                                                    <button class="btn btn-sm btn-outline--success confirmationBtn" data-question="@lang('Are you sure to enable this FAQ?')" data-action="{{ route('admin.faq.status', $faq->id) }}">
                                                        <i class="la la-eye"></i> @lang('Enable')
                                                    </button>
                                                @endif
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to delete this FAQ?')" data-action="{{ route('admin.faq.delete', $faq->id) }}">
                                                    <i class="la la-trash"></i> @lang('Delete')
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
                @if ($faqs->hasPages())
                    <div class="card-footer py-4">
                        {{ paginateLinks($faqs) }}
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div id="cuModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.faq.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Question')</label>
                            <input type="text" name="question" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Answer')</label>
                            <textarea name="answer" class="form-control" rows="5" required></textarea>
                        </div>
                        <div class="form-group">
                            <label>@lang('Image')</label>
                            <input type="file" name="image" class="form-control" accept="image/*">
                            <div class="mt-2" id="imagePreview"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <button class="btn btn-sm btn-outline--primary cuModalBtn" data-modal_title="@lang('Add FAQ')">
        <i class="las la-plus"></i> @lang('Add New')
    </button>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.cuModalBtn').on('click', function() {
                var modal = $('#cuModal');
                var data = $(this).data();
                modal.find('.modal-title').text(data.modal_title);
                modal.find('#imagePreview').html('');
                if (data.resource) {
                    modal.find('input[name=question]').val(data.resource.question);
                    modal.find('textarea[name=answer]').val(data.resource.answer);
                    if(data.image) {
                        modal.find('#imagePreview').html(`<img src="${data.image}" style="width: 100px;">`);
                    }
                    modal.find('form').attr('action', `{{ route('admin.faq.store') }}/${data.resource.id}`);
                } else {
                    modal.find('input[name=question]').val('');
                    modal.find('textarea[name=answer]').val('');
                    modal.find('form').attr('action', `{{ route('admin.faq.store') }}`);
                }
                modal.modal('show');
            });
        })(jQuery);
    </script>
@endpush
