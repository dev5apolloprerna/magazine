@extends('layouts.app')

@section('title', 'Customer List')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            @include('common.alert')
            <div class="row mb-3">
                <div class="col-md-6">
                    <form method="GET" action="{{ route('customer.index') }}">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" placeholder="Search Customer" value="{{ request('search') }}">
                            <button class="btn btn-primary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="col-md-6 text-end">
                    <a href="{{ route('customer.create') }}" class="btn btn-sm btn-success">
                        <i class="fas fa-plus"></i> Add Customer
                    </a>
                </div>
            </div>

            <form id="bulkDeleteForm" method="POST">
                @csrf
                <button type="button" class="btn btn-danger btn-sm mb-2" id="deleteAllSelected">
                    <i class="fas fa-trash"></i> Delete Selected
                </button>
                <div class="card">
                    <div class="card-body table-responsive">
                        <table class="table table-bordered align-middle">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="checkAll"></th>
                                    <th>Name</th>
                                    <th>Mobile</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($customers as $customer)
                                <tr>
                                    <td><input type="checkbox" name="ids[]" value="{{ $customer->customer_id }}"></td>
                                    <td>{{ $customer->customer_name }}</td>
                                    <td>{{ $customer->customer_mobile }}</td>
                                    <td>{{ $customer->customer_email }}</td>
                                    <!--<td>
                                        <input type="checkbox" class="toggle-status" data-id="{{ $customer->customer_id }}" {{ $customer->iStatus ? 'checked' : '' }}>
                                    </td>-->
                                    <td>
                                        <div class="form-check form-switch">
                                            <input type="checkbox"
                                                   class="form-check-input toggle-status"
                                                   data-id="{{ $customer->customer_id }}"
                                                   {{ $customer->iStatus ? 'checked' : '' }}>
                                        </div>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($customer->created_at)->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('customer.edit', $customer->customer_id) }}" class="text-primary"><i class="fas fa-edit"></i></a>
                                        <a href="javascript:void(0);" onclick="deleteRecord('{{ $customer->customer_id }}')" class="text-danger ms-2"><i class="fas fa-trash-alt"></i></a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <div class="d-flex justify-content-center mt-3">
                            {!! $customers->links() !!}
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    $('#checkAll').click(function () {
        $('input[name="ids[]"]').prop('checked', this.checked);
    });

    $('#deleteAllSelected').click(function () {
        if(confirm("Are you sure to delete selected records?")) {
            $('#bulkDeleteForm').attr('action', '{{ route("customer.bulk-delete") }}').submit();
        }
    });

    function deleteRecord(id) {
        if(confirm("Are you sure you want to delete this record?")) {
            $.post("{{ url('admin/customer') }}/" + id, {
                _method: 'DELETE',
                _token: '{{ csrf_token() }}'
            }, function(data) {
                location.reload();
            });
        }
    }
</script>
<script>
$(document).on('change', '.toggle-status', function () {
    let checkbox = $(this);
    let id = checkbox.data('id');
    let status = checkbox.is(':checked') ? 1 : 0;

    $.ajax({
        url: "{{ route('customer.toggle-status') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            id: id,
            status: status
        },
        success: function (response) {
            if (!response.success) {
                alert('Failed to update status');
                checkbox.prop('checked', !status);
            }
            window.location.href="";
            
        },
        error: function () {
            alert('Something went wrong');
            checkbox.prop('checked', !status);
        }
    });
});
</script>

@endsection
