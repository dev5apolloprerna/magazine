@extends('layouts.app')

@section('title', isset($customer) ? 'Edit Customer' : 'Add Customer')

@section('content')
<div class="container">
    <h3>Login History - {{ $customer->customer_name }} ({{ $customer->customer_id }})</h3>

    <a href="{{ route('admin.customers.index') }}" class="btn btn-secondary btn-sm mb-3">Back</a>

    <table class="table table-bordered">
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
@endsection
