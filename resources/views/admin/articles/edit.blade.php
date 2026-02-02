@extends('layouts.app')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

    <h4 class="mb-3">Edit Article</h4>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.articles.update', $article->article_id) }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Magazine <span class="text-danger">*</span></label>
                    <select name="magazine_id" class="form-select">
                        <option value="">-- Select Magazine --</option>
                        @foreach($magazines as $m)
                            <option value="{{ $m->id }}" {{ (old('magazine_id', $article->magazine_id) == $m->id) ? 'selected' : '' }}>
                                {{ $m->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('magazine_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Article Title <span class="text-danger">*</span></label>
                    <input type="text" name="article_title" class="form-control" value="{{ old('article_title', $article->article_title) }}">
                    @error('article_title')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Current Image</label><br>
                    @if($article->article_image)
                        <img src="{{ asset($article->article_image) }}" style="height:45px;border-radius:6px;">
                    @else
                        <span>-</span>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Change Image</label>
                    <input type="file" name="article_image" class="form-control">
                    @error('article_image')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Current PDF</label><br>
                    @if($article->article_pdf)
                        <a href="{{ asset('storage/'.$article->article_pdf) }}" target="_blank">View PDF</a>
                    @else
                        <span>-</span>
                    @endif
                </div>

                <div class="mb-3">
                    <label class="form-label">Change PDF</label>
                    <input type="file" name="article_pdf" class="form-control">
                    @error('article_pdf')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Is Paid</label>
                
                    {{-- hidden makes sure 0 is sent when checkbox is unchecked --}}
                    <input type="hidden" name="isPaid" value="0">
                
                    <input type="checkbox" name="isPaid" value="1"
                        {{ old('isPaid', $article->isPaid ?? 0) == 1 ? 'checked' : '' }}>
                
                    @error('isPaid')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="iStatus" class="form-select">
                        <option value="1" {{ old('iStatus', $article->iStatus)==1 ? 'selected' : '' }}>Active</option>
                        <option value="0" {{ old('iStatus', $article->iStatus)==0 ? 'selected' : '' }}>Inactive</option>
                    </select>
                </div>

                <button class="btn btn-primary">Update</button>
                <a href="{{ route('admin.articles.index') }}" class="btn btn-light">Back</a>
            </form>
        </div>
    </div>
</div>
</div>
</div>

@endsection