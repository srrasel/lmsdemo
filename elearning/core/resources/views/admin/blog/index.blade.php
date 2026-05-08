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
                                    <th>@lang('Image')</th>
                                    <th>@lang('Title')</th>
                                    <th>@lang('Category')</th>
                                    <th>@lang('Status')</th>
                                    <th>@lang('Actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($blogs as $blog)
                                    <tr>
                                        <td>
                                            <div class="user">
                                                <div class="thumb">
                                                    <img src="{{ getImage(getFilePath('blog') . '/' . $blog->image, getFileSize('blog')) }}" alt="@lang('image')">
                                                </div>
                                            </div>
                                        </td>
                                        <td>{{ __($blog->title) }}</td>
                                        <td>{{ __(@$blog->category->name) }}</td>
                                        <td>
                                            @php
                                                echo $blog->statusBadge;
                                            @endphp
                                        </td>
                                        <td>
                                            <div class="button--group">
                                                <a href="{{ route('admin.blog.edit', $blog->id) }}" class="btn btn-sm btn-outline--primary"><i class="las la-pen"></i>@lang('Edit')</a>
                                                @if($blog->status)
                                                <button class="btn btn-sm btn-outline--danger confirmationBtn" data-action="{{route('admin.blog.status', $blog->id)}}" data-question="@lang('Are you sure you want to disable this blog')" ><i class="las la-eye-slash"></i>@lang('Disable')</button>
                                                @else
                                                <button class="btn btn-sm btn-outline--success confirmationBtn" data-action="{{route('admin.blog.status', $blog->id)}}" data-question="@lang('Are you sure you want to enable this blog')" ><i class="las la-eye"></i>@lang('Enable')</button>
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
                @if ($blogs->hasPages())
                <div class="card-footer py-4">
                    {{ paginateLinks($blogs) }}
                </div>
                @endif
            </div>
        </div>
    </div>
    <x-confirmation-modal />
@endsection

@push('breadcrumb-plugins')
    <a href="{{ route('admin.blog.create') }}" class="btn btn-sm btn-outline--primary"><i class="las la-plus"></i>@lang('Add New')</a>
    <x-search-form />
@endpush
