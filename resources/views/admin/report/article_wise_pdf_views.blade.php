@extends('layouts.app')

@section('title', 'Article wise view list')

@section('content')
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      <div class="card">
        <div class="card-header">
          <h5>Article View Report</h5>
        </div>

        <div class="card-body">
          <div class="row mb-3">
            <div class="col-md-6">
              <form method="GET" class="d-flex" action="{{ route('admin.reports.articleWisePdfViews') }}">
                <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control"
                       placeholder="Search Article / Magazine / ID">
                <button type="submit" class="btn btn-primary gap-2 mx-2">Search</button>
                <a href="{{ route('admin.reports.articleWisePdfViews') }}" class="btn btn-secondary mx-2">Reset</a>
              </form>
            </div>
          </div>

          <div class="card">
            <div class="card-body table-responsive">
              <table class="table table-bordered align-middle">
                <thead>
                  <tr>
                    <th>Article ID</th>
                    <th>Article Title</th>
                    <th>Magazine</th>
                    <th>Total Views</th>
                    <th>Unique Users</th>
                    <th>Last View Time</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @forelse($articles as $a)
                    <tr>
                      <td>{{ $a->article_id }}</td>
                      <td>{{ $a->article_title }}</td>
                      <td>
                        {{ $a->magazine_title ?? '-' }}
                        @if(!empty($a->month) && !empty($a->year))
                          <br><small>{{ $a->month }} / {{ $a->year }}</small>
                        @endif
                      </td>
                      <td>{{ $a->total_views ?? 0 }}</td>
                      <td>{{ $a->unique_users ?? 0 }}</td>
                      <td>
                        @if(!empty($a->last_view_time))
                          {{ \Carbon\Carbon::parse($a->last_view_time)->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}
                        @else
                          -
                        @endif
                      </td>
                      <td>
                        <a class="btn btn-sm btn-info"
                           href="{{ route('admin.reports.articlePdfViewsDetail', $a->article_id) . (request()->getQueryString() ? '?' . request()->getQueryString() : '') }}">
                          View Detail
                        </a>
                      </td>
                    </tr>
                  @empty
                    <tr><td colspan="7" class="text-center">No data found</td></tr>
                  @endforelse
                </tbody>
              </table>

              {{ $articles->links() }}
            </div>
          </div>

        </div>
      </div>

    </div>
  </div>
</div>
@endsection
