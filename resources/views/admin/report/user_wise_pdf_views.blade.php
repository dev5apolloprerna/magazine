    @extends('layouts.app')

    @section('title', 'Customer Magazine Report')

    @section('content')
    <div class="main-content">
        <div class="page-content">
            <div class="container-fluid">

                <div class="card"> 
                  <div class="card-header">
                    <h5> Customer Magazine View Report
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <form method="GET" class="d-flex" action="{{ route('admin.reports.userWisePdfViews') }}">
                                <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control"
                                placeholder="Search Name / Mobile / Email">
                                
                                <button type="submit" class="btn btn-primary gap-2 mx-2">Search</button>
                                <a href="{{ route('admin.reports.userWisePdfViews') }}" class="btn btn-secondary mx-2">Reset</a>

                            </form>
                        </div>
                    </div>

                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th>Customer ID</th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Total Magazine Views</th>
                                    <th>Last View Time</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($customers as $c)
                                <tr>
                                    <td>{{ $c->customer_id }}</td>
                                    <td>{{ $c->customer_name }}</td>
                                    <td>{{ $c->customer_mobile }}</td>
                                    <td>{{ $c->customer_email }}</td>

                                    <td>{{ $c->magazine_count ?? 0 }}</td>

                                    <td>
                                        @if(!empty($c->last_view_time))
                                        {{ \Carbon\Carbon::parse($c->last_view_time)->timezone('Asia/Kolkata')->format('d-m-Y h:i A') }}
                                        @else
                                        -
                                        @endif
                                    </td>

                                    <td>
                                        <a class="btn btn-sm btn-info"
                                        href="{{ route('admin.reports.userPdfViewsDetail', $c->customer_id) . '?' . request()->getQueryString() }}">
                                        View Detail
                                    </a>

                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center">No data found</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>

                    {{ $customers->links() }}
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>



@endsection
