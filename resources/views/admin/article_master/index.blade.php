@extends('layouts.app')
@section('title', 'Magazine Article List')


@section('content')
<div class="main-content">
    <div class="page-content">
        <div class="container-fluid">

    <div class="d-flex align-items-center justify-content-between mb-3">
        <h4 class="mb-0">Articles - {{ $magazine->title }}</h4>
        <a href="{{ route('magazine.index') }}" class="btn btn-secondary btn-sm">Back</a>
    </div>

               @include('common.alert')


    <div class="row">

        {{-- ✅ LEFT SIDE : ADD FORM --}}
        <div class="col-lg-4 mb-3">
            <div class="card">
                <div class="card-header fw-semibold">Add Article</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.magazines.articles.store', $magazineId) }}" enctype="multipart/form-data">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label">Article Title</label>
                            <input type="text" name="article_title" value="{{ old('article_title') }}" class="form-control" required>
                            @error('article_title') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Article Image</label>
                            <input type="file" name="article_image" class="form-control" accept="image/*">
                            @error('article_image') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Article PDF</label>
                            <input type="file" name="article_pdf" class="form-control" accept="application/pdf" required>
                            @error('article_pdf') <div class="text-danger small">{{ $message }}</div> @enderror
                        </div>

                        <div class="row">
                            <div class="col-6 mb-3">
                                <label class="form-label">Paid?</label>
                                <select name="isPaid" class="form-control" required>
                                    <option value="0" {{ old('isPaid')==='0' ? 'selected' : '' }}>Free</option>
                                    <option value="1" {{ old('isPaid')==='1' ? 'selected' : '' }}>Paid</option>
                                </select>
                            </div>

                            <div class="col-6 mb-3">
                                <label class="form-label">Status</label>
                                <select name="iStatus" class="form-control" required>
                                    <option value="1" selected>Active</option>
                                    <option value="0">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <button class="btn btn-primary w-100">Save</button>
                    </form>

                </div>
            </div>
        </div>

        {{-- ✅ RIGHT SIDE : LISTING --}}
        <div class="col-lg-8 mb-3">
            <div class="card">

                <div class="card-header fw-semibold d-flex justify-content-between align-items-center">
                    <span>Articles List</span>

                    <span class="text-muted small">Total: {{ $articles->total() }}</span>

                    <form method="POST" action="{{ route('admin.magazines.articles.bulkDelete', $magazineId) }}" id="bulkDeleteForm">
                        @csrf
                        <input type="hidden" name="ids_json" id="ids_json" value="">
                        <div class="d-flex gap-2 align-items-center">
                             <button type="button" class="btn btn-danger btn-sm mb-3" onclick="bulkDelete()">
                                    <i class="fas fa-trash"></i> Delete Selected
                                </button>

                        </div>
                        </form>
                </div>

                <div class="card-body table-responsive">
                    <table class="table table-bordered align-middle">
                        <thead>
                            <tr>
                                <th style="width:45px;">
                                    <input type="checkbox" id="selectAll">
                                </th>
                                <th>Title</th>
                                <th>Paid</th>
                                <th>Views</th>
                                <th>Image</th>
                                <th>PDF</th>
                                <th>Status</th>
                                <th >Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($articles as $k => $a)
                                <tr>
                                    <td>
                                    <input type="checkbox" class="rowCheck" value="{{ $a->article_id }}">
                                </td>

                                    <td>
                                        <div class="fw-semibold">{{ $a->article_title }}</div>
                                        
                                    </td>

                                    <td>
                                        @if((int)$a->isPaid === 1)
                                            <span class="badge bg-warning text-dark">Paid</span>
                                        @else
                                            <span class="badge bg-success">Free</span>
                                        @endif
                                    </td>

                                    <td>{{ (int)$a->view_count }}</td>
                                    <td>
                                        <div class="d-flex gap-2 align-items-start">
                                            <div style="width:52px;">
                                                @if($a->article_image)
                                                    <img src="{{ asset($a->article_image) }}"
                                                         style="width:52px;height:52px;object-fit:cover;border-radius:8px;border:1px solid #eee;">
                                                @else
                                                    <div style="width:52px;height:52px;border-radius:8px;border:1px dashed #ccc;"
                                                         class="d-flex align-items-center justify-content-center text-muted small">
                                                        N/A
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <td>
                                            <div class="flex-grow-1">
                                                @if($a->article_pdf)
                                                    <a href="{{ asset($a->article_pdf) }}" target="_blank" class="small">
                                                        View PDF
                                                    </a>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch m-0">
                                            <input class="form-check-input statusToggle"
                                                   type="checkbox"
                                                   data-id="{{ $a->article_id }}" data-status="{{ (int)$a->iStatus }}"

                                                   {{ (int)$a->iStatus === 1 ? 'checked' : '' }}>
                                        </div>
                                    </td>


                                    <!-- <td>
                                        @if((int)$a->iStatus === 1)
                                            <span class="badge bg-primary">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td> -->

                                    <td>
                                        {{-- ✅ Edit Modal button (data attributes) --}}
                                            <a href="javascript:void(0)"
                                               class="text-info me-3"
                                               style="font-size:16px;"
                                               title="Edit"
                                               data-bs-toggle="modal"
                                               data-bs-target="#editArticleModal"
                                               data-id="{{ $a->article_id }}"
                                               data-title="{{ e($a->article_title) }}"
                                               data-paid="{{ (int)$a->isPaid }}"
                                               data-status="{{ (int)$a->iStatus }}"
                                               data-image-url="{{ $a->article_image ? asset('storage/'.$a->article_image) : '' }}"
                                               data-pdf-url="{{ $a->article_pdf ? asset('storage/'.$a->article_pdf) : '' }}"
                                            >
                                                <i class="fa fa-edit"></i>
                                            </a>


                                        <form id="deleteForm{{ $a->article_id }}"
                                              action="{{ route('admin.magazines.articles.destroy', [$magazineId, $a->article_id]) }}"
                                              method="POST"
                                              class="d-inline">
                                            @csrf
                                            @method('DELETE')

                                            <a href="javascript:void(0)"
                                               class="text-danger"
                                               style="font-size:16px;"
                                               title="Delete"
                                               onclick="confirmDelete({{ $a->article_id }})">
                                                <i class="fa fa-trash"></i>
                                            </a>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        No articles found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-3">
                        {{ $articles->links() }}
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>


{{-- ✅ EDIT POPUP MODAL --}}
<div class="modal fade" id="editArticleModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
        <form method="POST" id="editArticleForm" action="" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="modal-header">
          <h5 class="modal-title">Edit Article</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>

        <div class="modal-body">

            <div class="mb-3">
                <label class="form-label">Article Title</label>
                <input type="text" name="article_title" id="edit_article_title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Article Image (optional)</label>
                <input type="file" name="article_image" class="form-control" accept="image/*">
                <div class="small mt-1">
                    <a href="#" target="_blank" id="edit_image_preview_link" style="display:none;">Current Image</a>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Article PDF (optional)</label>
                <input type="file" name="article_pdf" class="form-control" accept="application/pdf">
                <div class="small mt-1">
                    <a href="#" target="_blank" id="edit_pdf_preview_link" style="display:none;">Current PDF</a>
                </div>
            </div>


            <div class="row">
                <div class="col-6 mb-3">
                    <label class="form-label">Paid?</label>
                    <select name="isPaid" id="edit_isPaid" class="form-control" required>
                        <option value="0">Free</option>
                        <option value="1">Paid</option>
                    </select>
                </div>

                <div class="col-6 mb-3">
                    <label class="form-label">Status</label>
                    <select name="iStatus" id="edit_iStatus" class="form-control" required>
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" data-image-url="{{ $a->article_image ? asset('storage/'.$a->article_image) : '' }}"
 data-pdf-url="{{ $a->article_pdf ? asset('storage/'.$a->article_pdf) : '' }}"
>Close</button>
          <button class="btn btn-primary">Update</button>
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

function confirmDelete(id) {
    if (confirm("Are you sure you want to delete this article?")) {
        document.getElementById("deleteForm" + id).submit();
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('editArticleModal');
    if (!modal) return;

    modal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;

        const id = button.getAttribute('data-id');
        const title = button.getAttribute('data-title') || '';
        const paid = button.getAttribute('data-paid') || '0';
        const status = button.getAttribute('data-status') || '1';

        const imageUrl = button.getAttribute('data-image-url') || '';
        const pdfUrl = button.getAttribute('data-pdf-url') || '';

        // form action
        const form = document.getElementById('editArticleForm');
        form.action = "{{ url('admin/magazines/'.$magazineId.'/articles') }}/" + id;

        // fill simple fields
        document.getElementById('edit_article_title').value = title;
        document.getElementById('edit_isPaid').value = paid;
        document.getElementById('edit_iStatus').value = status;

        // preview links
        const imgLink = document.getElementById('edit_image_preview_link');
        if (imageUrl) {
            imgLink.href = imageUrl;
            imgLink.style.display = "inline";
        } else {
            imgLink.href = "#";
            imgLink.style.display = "none";
        }

        const pdfLink = document.getElementById('edit_pdf_preview_link');
        if (pdfUrl) {
            pdfLink.href = pdfUrl;
            pdfLink.style.display = "inline";
        } else {
            pdfLink.href = "#";
            pdfLink.style.display = "none";
        }
    });
});

</script>

<script>

const addToggle = document.getElementById('addStatusToggle');
const addHidden = document.getElementById('add_iStatus');
if (addToggle && addHidden) {
    addToggle.addEventListener('change', () => {
        addHidden.value = addToggle.checked ? "1" : "0";
        addToggle.nextElementSibling.textContent = addToggle.checked ? "Active" : "Inactive";
    });
}

/** ✅ Edit modal toggle -> hidden iStatus */
const editToggle = document.getElementById('editStatusToggle');
const editHidden = document.getElementById('edit_iStatus');
const editLabel = document.getElementById('editStatusLabel');
if (editToggle && editHidden && editLabel) {
    editToggle.addEventListener('change', () => {
        editHidden.value = editToggle.checked ? "1" : "0";
        editLabel.textContent = editToggle.checked ? "Active" : "Inactive";
    });
}

/** ✅ select all */
const selectAll = document.getElementById('selectAll');
if (selectAll) {
    selectAll.addEventListener('change', function() {
        document.querySelectorAll('.rowCheck').forEach(ch => ch.checked = selectAll.checked);
    });
}

/** ✅ bulk delete */
function bulkDelete() {
    const ids = Array.from(document.querySelectorAll('.rowCheck:checked')).map(x => x.value);
    if (!ids.length) {
        alert("Please select at least one article.");
        return;
    }
    if (!confirm("Delete selected articles?")) return;

    // build hidden inputs dynamically for Laravel
    const form = document.getElementById('bulkDeleteForm');
    // remove old hidden inputs
    form.querySelectorAll('input[name="ids[]"]').forEach(i => i.remove());
    ids.forEach(id => {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'ids[]';
        input.value = id;
        form.appendChild(input);
    });
    form.submit();
}


$(document).on('change', '.statusToggle', function () {
        let id = $(this).data('id');
        let checkbox = $(this);

        $.ajax({
            url: "{{ url('admin/magazines/'.$magazineId.'/articles') }}/" + id + "/toggle-status",
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
