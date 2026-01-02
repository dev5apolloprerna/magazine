@extends('layouts.app')
@section('title', 'Magazine List')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            {{-- Alert Messages --}}
            @include('common.alert')
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Magazine List</h5>
                            <a href="{{ route('magazine.create') }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-plus"></i> Add Magazine
                            </a>
                        </div>
                        <div class="card-body">
                            <form id="bulkDeleteForm" method="POST">
                                @csrf
                                <button type="button" class="btn btn-danger btn-sm mb-3" id="deleteAllSelected">
                                    <i class="fas fa-trash"></i> Delete Selected
                                </button>
                                <div class="table-responsive">
                                    <table class="table table-bordered align-middle">
                                        <thead>
                                            <tr>
                                                <th><input type="checkbox" id="checkAll"></th>
                                                <th>Title</th>
                                                <th>Image</th>
                                                <th>PDF</th>
                                                <th>Month</th>
                                                <th>Year</th>
                                                <th>Created Date</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($magazines as $magazine)
                                            <tr>
                                                <td><input type="checkbox" name="ids[]" value="{{ $magazine->id }}"></td>
                                                <td>{{ $magazine->title }}</td>
                                                <td><img src="{{ asset($magazine->image) }}" alt="" width="60"></td>
                                                <td><a href="{{ asset($magazine->pdf) }}" target="_blank">View PDF</a></td>
                                                <td>{{ $magazine->month }}</td>
                                                <td>{{ $magazine->year }}</td>
                                                <td>{{ \Carbon\Carbon::parse($magazine->created_at)->format('d M Y') }}</td>
                                                <!--<td>
                                                    <input type="checkbox" class="toggle-status" data-id="{{ $magazine->id }}" {{ $magazine->iStatus ? 'checked' : '' }}>
                                                </td>-->
                                                <td>
                                                    <div class="form-check form-switch">
                                                        <input class="form-check-input toggle-status"
                                                               type="checkbox"
                                                               data-id="{{ $magazine->id }}"
                                                               {{ $magazine->iStatus ? 'checked' : '' }}>
                                                    </div>
                                                </td>
                                                <td>
                                                    <a href="{{ route('magazine.edit', $magazine->id) }}" class="text-primary"><i class="fas fa-edit"></i></a>
                                                    <a href="javascript:void(0);" onclick="deleteRecord('{{ $magazine->id }}')" class="text-danger ms-2"><i class="fas fa-trash-alt"></i></a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </form>
                            <div class="d-flex justify-content-center mt-3">
                                {!! $magazines->links() !!}
                            </div>
                        </div>
                    </div>
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
        if(confirm("Are you sure you want to delete selected records?")) {
            $('#bulkDeleteForm').attr('action', '{{ route("magazine.bulk-delete") }}').submit();
        }
    });

    function deleteRecord(id) {
        if(confirm("Are you sure you want to delete this record?")) {
            $.post("{{ url('admin/magazine') }}/" + id, {
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
        let id = $(this).data('id');
        let checkbox = $(this);

        $.ajax({
            url: "{{ route('magazine.toggle-status') }}",
            type: "POST",
            data: {
                _token: "{{ csrf_token() }}",
                id: id
            },
            success: function (res) {
                if (!res.success) {
                    alert('Status update failed');
                    checkbox.prop('checked', !checkbox.prop('checked'));
                }
                window.location.href="";
            },
            error: function () {
                alert('Something went wrong');
                checkbox.prop('checked', !checkbox.prop('checked'));
            }
        });
    });
</script>

@endsection
