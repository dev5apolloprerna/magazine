@extends('layouts.app')

@section('title', 'Customer Magazine View Detail')

@section('content')

<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

    <h4>Customer Magazine Views Detail</h4>

    <div class="row mb-3">
        <div class="col-md-6">
            <div class="mb-3">
                    <b>{{ $customer->customer_name }}</b> ({{ $customer->customer_id }}) <br>
                    {{ $customer->customer_mobile }} | {{ $customer->customer_email }}
                </div>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.reports.userWisePdfViews') . (request()->getQueryString() ? '?' . request()->getQueryString() : '') }}"
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
                    <th>Magazine</th>
                    <th>Month/Year</th>
                    <th>View Date Time</th>
                    <!-- <th>PDF</th> -->
                </tr>
            </thead>
            <tbody>
            @forelse($rows as $i => $r)
                <tr>
                    <td>{{ $rows->firstItem() + $i }}</td>
                    <td>{{ $r->title }}</td>
                    <td>{{ $r->month }} / {{ $r->year }}</td>
                    <td>
                        @if(!empty($r->date_time))
                            {{ \Carbon\Carbon::parse($r->date_time)->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}
                        @else
                            -
                        @endif
                    </td>
                    <!-- <td>
                        <a href="{{ magazine_base_url($r->pdf) }}" target="_blank" class="btn btn-sm btn-primary">
                            Open PDF
                        </a>
                    </td> -->
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No history found</td>
                </tr>
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
