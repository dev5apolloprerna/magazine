<div class="card">
    <div class="card-body table-responsive">
        <h5>{{ $type }} Customers</h5>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Customer Name</th>
                    <th>Mobile</th>
                    <th>Email</th>
                    @if($type !== 'Unsubscribed')
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
                            <td>{{ $customer->start_date }}</td>
                            <td>{{ $customer->end_date }}</td>
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
