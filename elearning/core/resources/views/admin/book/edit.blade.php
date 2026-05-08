@extends('admin.layouts.app')

@section('panel')
    <div class="row">
        <div class="col-lg-12">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">@lang('Book Information')</h5>
                </div>
                <form action="{{ route('admin.book.update', $book->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>@lang('Image')</label>
                                    <x-image-uploader :imagePath="getImage(getFilePath('book').'/'.$book->image, getFileSize('book'))" :size="getFileSize('book')" class="w-100" id="uploadImage" :required="false" />
                                </div>
                                <div class="form-group">
                                    <label>@lang('Upload PDF')</label>
                                    <input type="file" name="file" class="form-control" accept=".pdf">
                                    <small class="text-muted">@lang('Supported file: .pdf')</small>
                                    @if($book->file)
                                        <div class="mt-2">
                                            <a href="{{ $book->file_url }}" target="_blank" class="text--primary"><i class="las la-file-pdf"></i> @lang('View Current PDF')</a>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label>@lang('Category')</label>
                                    <select name="book_category_id" class="form-control" required>
                                        <option value="">@lang('Select Category')</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" @selected($book->book_category_id == $category->id)>{{ __($category->name) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Title')</label>
                                    <input type="text" name="title" class="form-control" placeholder="@lang('Book Title')" value="{{ $book->title }}" required>
                                </div>
                                <div class="form-group">
                                    <label>@lang('Description')</label>
                                    <textarea name="description" rows="10" class="form-control nicEdit" placeholder="@lang('Book Description')">{{ $book->description }}</textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn--primary w-100 h-45">@lang('Update')</button>
                    </div>
                </form>
            </div>

            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">@lang('Chapters & Lessons')</h5>
                    <button type="button" class="btn btn-sm btn--primary addChapterBtn"><i class="las la-plus"></i> @lang('Add Chapter')</button>
                </div>
                <div class="card-body">
                    <div class="accordion" id="accordionExample">
                        @foreach($book->chapters()->orderBy('sort_order')->get() as $chapter)
                            <div class="card mb-2">
                                <div class="card-header p-2" id="heading{{ $chapter->id }}">
                                    <h2 class="mb-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <button class="btn btn-link btn-block text-left text-dark text-decoration-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse{{ $chapter->id }}" aria-expanded="true" aria-controls="collapse{{ $chapter->id }}">
                                                {{ __($chapter->title) }}
                                            </button>
                                            <div class="button-group">
                                                <button type="button" class="btn btn-sm btn-outline--primary editChapterBtn" data-id="{{ $chapter->id }}" data-title="{{ $chapter->title }}" data-sort_order="{{ $chapter->sort_order }}">
                                                    <i class="las la-pen"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to delete this chapter?')" data-action="{{ route('admin.book.chapter.delete', $chapter->id) }}">
                                                    <i class="las la-trash"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline--info addLessonBtn" data-chapter_id="{{ $chapter->id }}">
                                                    <i class="las la-plus"></i> @lang('Add Lesson')
                                                </button>
                                            </div>
                                        </div>
                                    </h2>
                                </div>

                                <div id="collapse{{ $chapter->id }}" class="collapse" aria-labelledby="heading{{ $chapter->id }}" data-parent="#accordionExample">
                                    <div class="card-body">
                                        <ul class="list-group">
                                            @foreach($chapter->lessons()->orderBy('sort_order')->get() as $lesson)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    {{ __($lesson->title) }}
                                                    <div class="button-group">
                                                        <button type="button" class="btn btn-sm btn-outline--primary editLessonBtn" 
                                                            data-id="{{ $lesson->id }}" 
                                                            data-chapter_id="{{ $chapter->id }}"
                                                            data-title="{{ $lesson->title }}" 
                                                            data-sort_order="{{ $lesson->sort_order }}"
                                                            data-content="{{ $lesson->content }}">
                                                            <i class="las la-pen"></i>
                                                        </button>
                                                        <button type="button" class="btn btn-sm btn-outline--danger confirmationBtn" data-question="@lang('Are you sure to delete this lesson?')" data-action="{{ route('admin.book.lesson.delete', $lesson->id) }}">
                                                            <i class="las la-trash"></i>
                                                        </button>
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chapter Modal -->
    <div id="chapterModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Chapter')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('admin.book.chapter.store', $book->id) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Title')</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Sort Order')</label>
                            <input type="number" name="sort_order" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn--primary">@lang('Submit')</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Lesson Modal -->
    <div id="lessonModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">@lang('Add Lesson')</h5>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>@lang('Title')</label>
                            <input type="text" name="title" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Sort Order')</label>
                            <input type="number" name="sort_order" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>@lang('Content')</label>
                            <textarea name="content" class="form-control nicEdit" rows="5" id="content"></textarea>
                        </div>
                        <div class="form-group">
                            <label>@lang('PDF File')</label>
                            <input type="file" name="pdf_file" class="form-control" accept=".pdf">
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

@push('style')
    <style>
        .nicEdit-main {
            width: 100% !important;
            height: 300px !important;
            overflow-y: scroll !important;
        }
        .nicEdit-panelContain {
            width: 100% !important;
        }
        div[style*="width: 100px"] {
            width: 100% !important;
        }
    </style>
@endpush

@push('script')
    <script>
        (function($) {
            "use strict";
            $('.addChapterBtn').on('click', function() {
                var modal = $('#chapterModal');
                modal.find('.modal-title').text('@lang("Add Chapter")');
                modal.find('form').attr('action', '{{ route("admin.book.chapter.store", $book->id) }}');
                modal.find('input[name=title]').val('');
                modal.find('input[name=sort_order]').val('');
                modal.modal('show');
            });

            $('.editChapterBtn').on('click', function() {
                var modal = $('#chapterModal');
                var data = $(this).data();
                modal.find('.modal-title').text('@lang("Edit Chapter")');
                modal.find('form').attr('action', '{{ route("admin.book.chapter.store", $book->id) }}/' + data.id);
                modal.find('input[name=title]').val(data.title);
                modal.find('input[name=sort_order]').val(data.sort_order);
                modal.modal('show');
            });

            $('.addLessonBtn').on('click', function() {
                var modal = $('#lessonModal');
                var chapterId = $(this).data('chapter_id');
                
                // Auto-expand this chapter on reload
                localStorage.setItem('activeChapter', 'collapse' + chapterId);
                
                modal.find('.modal-title').text('@lang("Add Lesson")');
                modal.find('form').attr('action', '{{ route("admin.book.lesson.store", "") }}/' + chapterId);
                modal.find('input[name=title]').val('');
                modal.find('input[name=sort_order]').val('');
                modal.find('textarea[name=content]').val('');
                
                // Clear nicEdit content
                var textarea = modal.find('textarea[name=content]');
                var editorId = textarea.attr('id');
                if (editorId && nicEditors.findEditor(editorId)) {
                    nicEditors.findEditor(editorId).setContent('');
                }
                
                modal.modal('show');
            });

            $('.editLessonBtn').on('click', function() {
                var modal = $('#lessonModal');
                var data = $(this).data();
                modal.find('.modal-title').text('@lang("Edit Lesson")');
                modal.find('form').attr('action', '{{ route("admin.book.lesson.store", "") }}/' + data.chapter_id + '/' + data.id);
                modal.find('input[name=title]').val(data.title);
                modal.find('input[name=sort_order]').val(data.sort_order);
                modal.find('textarea[name=content]').val(data.content);
                
                // Set nicEdit content
                var textarea = modal.find('textarea[name=content]');
                var editorId = textarea.attr('id');
                if (editorId && nicEditors.findEditor(editorId)) {
                    nicEditors.findEditor(editorId).setContent(data.content);
                }
                
                modal.modal('show');
            });

            // Keep accordion open after reload
            $('.collapse').on('shown.bs.collapse', function () {
                localStorage.setItem('activeChapter', $(this).attr('id'));
            });

            $('.collapse').on('hidden.bs.collapse', function () {
                if (localStorage.getItem('activeChapter') == $(this).attr('id')) {
                    localStorage.removeItem('activeChapter');
                }
            });

            var activeChapter = localStorage.getItem('activeChapter');
            if (activeChapter) {
                $('#' + activeChapter).collapse('show');
            }

        })(jQuery);
    </script>
@endpush
