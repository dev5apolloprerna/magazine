@extends('layouts.app')

@section('title', 'Free Article')

@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">
            @include('common.alert')
            <div class="row">
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">Add Free Article</div>
                        <div class="card-body">
                            <form action="{{ route('admin.free_article.store') }}" method="POST">
                                @csrf
                                <div class="mb-3">
                                    <label class="form-label">Free Article <span style="color:red;">*</span></label>
                                    <input type="number" name="free_article" class="form-control" value="{{ old('free_article') }}">
                                    @if($errors->has('free_article'))
                                        <span class="text-danger">
                                            {{ $errors->first('free_article') }}
                                        </span>
                                    @endif
                                </div>
                               
                                <button type="submit" class="btn btn-success">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Free Article List</h5>
                            <button class="btn btn-sm btn-danger" id="bulkDeleteBtn">Bulk Delete</button>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th><input type="checkbox" id="selectAll"></th>
                                            <th>Free Article</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($free_articles as $row)
                                            <tr>
                                                <td><input type="checkbox" class="record-checkbox" value="{{ $row->id }}"></td>
                                                <td>{{ $row->free_article }}</td>
                                                <td>
                                                    <a href="javascript:void(0);" class="btn btn-sm btn-primary editBtn" data-id="{{ $row->id }}"><i class="fas fa-edit"></i></a>
                                                    <a href="javascript:void(0);" onclick="confirmDelete('{{ route('admin.free_article.destroy', $row->id) }}')" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-center mt-3">
                                {{ $free_articles->links() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Edit Modal -->
            <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <form id="editForm" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="modal-header">
                                <h5 class="modal-title" id="editModalLabel">Edit Free Article</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="mb-3">
                                    <label class="form-label">Free Article <span style="color:red;">*</span></label>
                                    <input type="number" name="free_article" id="edit_free_article" class="form-control">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status <span style="color:red;">*</span></label>
                                    <select name="iStatus" id="edit_iStatus" class="form-control">
                                        <option value="1">Active</option>
                                        <option value="0">Inactive</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Update</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
