@extends('layouts.app')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

    <h4 class="mb-3">Add Article</h4>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="mb-3">
                    <label class="form-label">Magazine <span class="text-danger">*</span></label>
                    <select name="magazine_id" class="form-select">
                        <option value="">-- Select Magazine --</option>
                        @foreach($magazines as $m)
                            <option value="{{ $m->id }}" {{ old('magazine_id') == $m->id ? 'selected' : '' }}>
                                {{ $m->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('magazine_id')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Article Title <span class="text-danger">*</span></label>
                    <input type="text" name="article_title" class="form-control" value="{{ old('article_title') }}">
                    @error('article_title')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Article Image <span class="text-danger">*</span></label>
                    <input type="file" name="article_image" class="form-control">
                    @error('article_image')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Article PDF <span class="text-danger">*</span></label>
                    <input type="file" name="article_pdf" class="form-control">
                    @error('article_pdf')<small class="text-danger">{{ $message }}</small>@enderror
                </div>

                <div class="mb-3">
                    <label class="form-label">Is Paid <span class="text-danger">*</span></label>
                    <input type="checkbox" name="isPaid" class="form-control" value="1">
                    @error('isPaid')<small class="text-danger">{{ $message }}</small>@enderror
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Status</label>
                    <select name="iStatus" class="form-select">
                        <option value="1" selected>Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>

                <button class="btn btn-primary">Save</button>
                <a href="{{ route('admin.articles.index') }}" class="btn btn-light">Back</a>
            </form>
        </div>
    </div>
</div>
</div>
</div>

@endsection