@extends('layouts.app')

@section('title', 'Plan Master')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

            @include('common.alert')

            <div class="row">
                {{-- Add Form --}}
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header"><h5 class="mb-0">Add Plan</h5></div>
                        <div class="card-body">
                            <form action="{{ route('plan.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Plan Name <span style="color:red;">*</span></label>
                                    <input type="text" name="plan_name" class="form-control" value="{{ old('plan_name') }}">
                                    @if($errors->has('plan_name'))
                                        <span class="text-danger">{{ $errors->first('plan_name') }}</span>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Amount <span style="color:red;">*</span></label>
                                    <input type="text" name="plan_amount" class="form-control" value="{{ old('plan_amount') }}">
                                    @if($errors->has('plan_amount'))
                                        <span class="text-danger">{{ $errors->first('plan_amount') }}</span>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Days <span style="color:red;">*</span></label>
                                    <input type="text" name="days" class="form-control" value="{{ old('days') }}">
                                    @if($errors->has('days'))
                                        <span class="text-danger">{{ $errors->first('days') }}</span>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status</label><br>
                                    <input type="checkbox" name="iStatus" value="1" checked> Active
                                </div>
                                <button type="submit" class="btn btn-success">Save</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Listing --}}
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Plan List</h5>
                            <form method="GET" action="{{ route('plan.index') }}" class="d-flex">
                                <input type="text" name="search" class="form-control form-control-sm me-2" placeholder="Search Plan" value="{{ request('search') }}">
                                <button class="btn btn-sm btn-primary"><i class="fas fa-search"></i></button>
                            </form>
                        </div>
                        <form id="bulkDeleteForm" method="POST">
                            @csrf
                            <div class="card-body table-responsive">
                                <button type="button" class="btn btn-danger btn-sm mb-2" id="deleteAllSelected">
                                    <i class="fas fa-trash"></i> Delete Selected
                                </button>
                                <table class="table table-bordered align-middle">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="checkAll"></th>
                                            <th>Plan Name</th>
                                            <th>Amount</th>
                                            <th>Days</th>
                                            <th>Status</th>
                                            <th>Created</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($plans as $plan)
                                        <tr>
                                            <td><input type="checkbox" name="ids[]" value="{{ $plan->plan_id }}"></td>
                                            <td>{{ $plan->plan_name }}</td>
                                            <td>{{ $plan->plan_amount }}</td>
                                            <td>{{ $plan->days }}</td>
                                            <!--<td><input type="checkbox" class="toggle-status" data-id="{{ $plan->plan_id }}" {{ $plan->iStatus ? 'checked' : '' }}></td>-->
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input type="checkbox"
                                                       class="form-check-input toggle-status"
                                                       data-id="{{ $plan->plan_id }}"
                                                       {{ $plan->iStatus ? 'checked' : '' }}>
                                                </div>
                                            </td>
                                            <td>{{ \Carbon\Carbon::parse($plan->created_at)->format('d M Y') }}</td>
                                            <td>
                                                <a href="javascript:void(0);" onclick="editPlan({{ $plan->plan_id }})" class="text-primary"><i class="fas fa-edit"></i></a>
                                                <a href="javascript:void(0);" onclick="deleteRecord({{ $plan->plan_id }})" class="text-danger ms-2"><i class="fas fa-trash-alt"></i></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                                <div class="d-flex justify-content-center mt-3">
                                    {!! $plans->links() !!}
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Edit Modal --}}
            <div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog">
                    <form method="POST" id="editForm">
                        @csrf
                        @method('PUT')
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Plan</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body" id="editModalBody">
                                {{-- content loaded via JS --}}
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Update</button>
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

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
        if(confirm("Are you sure?")) {
            $('#bulkDeleteForm').attr('action', '{{ route("plan.bulk-delete") }}').submit();
        }
    });

    function deleteRecord(id) {
        if(confirm("Are you sure?")) {
            $.post("{{ url('admin/plan') }}/" + id, {
                _method: 'DELETE',
                _token: '{{ csrf_token() }}'
            }, function() {
                location.reload();
            });
        }
    }

    function editPlan(id) {
        $.get("{{ url('admin/plan') }}/" + id + "/edit", function(data) {
            $('#editModalBody').html(data);
            $('#editForm').attr('action', "{{ url('admin/plan') }}/" + id);
            $('#editModal').modal('show');
        });
    }
</script>
<script>
$(document).on('change', '.toggle-status', function () {
    let checkbox = $(this);
    let id = checkbox.data('id');
    let status = checkbox.is(':checked') ? 1 : 0;

    $.ajax({
        url: "{{ route('plan.toggle-status') }}",
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
