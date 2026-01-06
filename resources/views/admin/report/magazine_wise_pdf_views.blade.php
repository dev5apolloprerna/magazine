@extends('layouts.app')

@section('title', 'Magazine wise view list')

@section('content')

<div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="card"> 
                  <div class="card-header">
                    <h5> Magazine View Report
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" class="d-flex" action="{{ route('admin.reports.magazineWisePdfViews') }}">
                                <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control"
                                placeholder="Search Name / Mobile / Email">
                                
                                <button type="submit" class="btn btn-primary gap-2 mx-2">Search</button>
                                <a href="{{ route('admin.reports.magazineWisePdfViews') }}" class="btn btn-secondary mx-2">Reset</a>

                            </form>
                        </div>
                    </div>


    <div class="card">
        <div class="card-body table-responsive">
            <table class="table table-bordered align-middle">
    
            <thead>
            <tr>
                <th>Magazine ID</th>
                <th>Title</th>
                <th>Month/Year</th>
                <th>Total Views</th>
                <th>Unique Users</th>
                <th>Last View Time</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @forelse($magazines as $m)
                <tr>
                    <td>{{ $m->id }}</td>
                    <td>{{ $m->title }}</td>
                    <td>{{ $m->month }} / {{ $m->year }}</td>
                    <td>{{ $m->total_views ?? 0 }}</td>
                    <td>{{ $m->unique_users ?? 0 }}</td>
                    <td>
                        @if(!empty($m->last_view_time))
                            {{ \Carbon\Carbon::parse($m->last_view_time)->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        <a class="btn btn-sm btn-info"
                           href="{{ route('admin.reports.magazinePdfViewsDetail', $m->id) . (request()->getQueryString() ? '?' . request()->getQueryString() : '') }}">
                            View Detail
                        </a>
                    </td>
                </tr>
            @empty
                <tr><td colspan="7" class="text-center">No data found</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>

    {{ $magazines->links() }}
</div>
</div>
</div>
</div>


@endsection
