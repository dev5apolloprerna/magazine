@extends('layouts.app')

@section('title', 'Article wise view detail')

@section('content')
<div class="main-content">
  <div class="page-content">
    <div class="container-fluid">

      <h4>Article Views Detail</h4>

      <div class="row mb-3">
        <div class="col-md-6">
          <div class="mb-3">
            <b>{{ $article->article_title }}</b> (ID: {{ $article->article_id }})<br>
            Magazine: {{ $article->magazine_title ?? '-' }}
            @if(!empty($article->month) && !empty($article->year))
              ({{ $article->month }} - {{ $article->year }})
            @endif
          </div>
        </div>
        <div class="col-md-6 text-end">
          <a href="{{ route('admin.reports.articleWisePdfViews') . (request()->getQueryString() ? '?' . request()->getQueryString() : '') }}"
             class="btn btn-secondary btn-sm mb-3">
            Back
          </a>
        </div>
      </div>

      <div class="card">
        <div class="card-body table-responsive">
          <table class="table table-bordered align-middle">
            <thead>
              <tr>
                <th>#</th>
                <th>Customer ID</th>
                <th>Name</th>
                <th>Mobile</th>
                <th>Email</th>
                <th>View Date & Time</th>
              </tr>
            </thead>
            <tbody>
              @forelse($rows as $i => $r)
                <tr>
                  <td>{{ $rows->firstItem() + $i }}</td>
                  <td>{{ $r->customer_id }}</td>
                  <td>{{ $r->customer_name }}</td>
                  <td>{{ $r->customer_mobile }}</td>
                  <td>{{ $r->customer_email }}</td>
                  <td>
                    @if(!empty($r->date_time))
                      {{ \Carbon\Carbon::parse($r->date_time)->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}
                    @else
                      -
                    @endif
                  </td>
                </tr>
              @empty
                <tr><td colspan="6" class="text-center">No views found</td></tr>
              @endforelse
            </tbody>
          </table>

          {{ $rows->links() }}
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
