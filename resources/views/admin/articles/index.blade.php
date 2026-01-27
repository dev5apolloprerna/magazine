@extends('layouts.app')

@section('title', 'Magazine Article List')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="mb-0">Article Master</h4>
        <a href="{{ route('admin.articles.create') }}" class="btn btn-primary">+ Add Article</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form class="row g-2 mb-3" method="GET" action="{{ route('admin.articles.index') }}">
        <div class="col-md-4">
            <select name="magazine_id" class="form-select">
                <option value="">-- Select Magazine --</option>
                @foreach($magazines as $m)
                    <option value="{{ $m->id }}" {{ request('magazine_id') == $m->id ? 'selected' : '' }}>
                        {{ $m->title }}
                    </option>
                @endforeach
            </select>
        </div>
        <div class="col-md-4">
				<input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search article title...">
		</div>
        <div class="col-md-3">
            <button class="btn btn-dark">Filter</button>
            <a href="{{ route('admin.articles.index') }}" class="btn btn-light">Reset</a>
        </div>
    </form>

    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Magazine</th>
                        <th>Title</th>
                        <th>Image</th>
                        <th>PDF</th>
                        <th>Is Paid</th>
                        <th>Status</th>
                        <th width="160">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($articles as $k => $row)
                        <tr>
                            <td>{{ $articles->firstItem() + $k }}</td>
                            <td>{{ $row->magazine?->title ?? '-' }}</td>
                            <td>{{ $row->article_title }}</td>
                            <td>
                                @if($row->article_image)
                                <img src="{{ asset($row->article_image) }}" style="height:45px;border-radius:6px;">
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @if($row->article_pdf)
                                    <a href="{{ asset($row->article_pdf) }}" target="_blank">View PDF</a>
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                 {{ $row->isPaid==1 ? 'Paid' : 'Free' }}
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.articles.toggle-status', $row->article_id) }}">
                                    @csrf
                                    <button class="btn btn-sm {{ $row->iStatus==1 ? 'btn-success' : 'btn-secondary' }}">
                                        {{ $row->iStatus==1 ? 'Active' : 'Inactive' }}
                                    </button>
                                </form>
                            </td>
                            <td class="d-flex gap-2">
                                <a href="{{ route('admin.articles.edit', $row->article_id) }}" class="text-primary"><i class="fas fa-edit"></i></a>

                                <form method="POST" action="{{ route('admin.articles.destroy', $row->article_id) }}"
                                      onsubmit="return confirm('Delete this article?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="text-danger ms-2" style="text-decoration: none;border: none;background: none;"><i class="fas fa-trash-alt"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">No data found</td></tr>
                    @endforelse
                </tbody>
            </table>

            <div class="mt-2">
                {{ $articles->links() }}
            </div>
        </div>
    </div>
</div>
</div>
</div>

@endsection