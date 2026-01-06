@extends('layouts.app')

@section('title', isset($customer) ? 'Edit Customer' : 'Add Customer')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

    <h3>Login History </h3>
    <div class="row mb-3">
        <div class="col-md-6">
            <div class="mb-3">
                
                    <b>{{ $customer->customer_name }}</b> ({{ $customer->customer_id }}) <br>
                    {{ $customer->customer_mobile }} | {{ $customer->customer_email }}

                  </div>
        </div>
        <div class="col-md-6 text-end">
            <a href="{{ route('admin.customers.index') . (request()->getQueryString() ? '?' . request()->getQueryString() : '') }}"
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
                <th>Login Date Time</th>
            </tr>
        </thead>
        <tbody>
        @foreach($logs as $i => $log)
            <tr>
                <td>{{ $logs->firstItem() + $i }}</td>
                <td>{{ $log->login_date_time->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{ $logs->links() }}
</div>
</div>
<div>
</div>
</div>
</div>
</div>


@endsection

