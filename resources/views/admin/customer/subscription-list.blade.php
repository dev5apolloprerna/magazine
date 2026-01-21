<div class="card">
    <div class="card-body table-responsive">
        <h5>{{ $type }} Customers</h5>
        @if($type !== 'Unsubscribed')
            <div class="mb-2 " style="float: right;">
                <strong>Total Amount:</strong>
                {{ $customers->sum('amount') }}
            </div>
        @endif
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    
                    @if($type !== 'Unsubscribed')
                        <th>Plan Name</th>
                        <th>Days</th>
                        <th>Amount</th>
                        <th>Start Date</th>
                        <th>End Date</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse($customers as $customer)
                    <tr>
                        <td>{{ $customer->customer_name }}</td>
                        <td>{{ $customer->customer_mobile }}</td>
                        <td>{{ $customer->customer_email }}</td>
                        
                        @if($type !== 'Unsubscribed')
                            <td>{{ $customer->plan_name ?? '-' }}</td>
                            <td>{{ $customer->days ?? '-' }}</td>
                            <td>{{ $customer->amount ?? '-' }}</td>
                            <td>{{ date('d-m-Y',strtotime($customer->start_date)) }}</td>
                            <td>{{ date('d-m-Y',strtotime($customer->end_date)) }}</td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No {{ strtolower($type) }} customers found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
