<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileUploadHelper;
use App\Http\Controllers\Controller;
use App\Models\Magazine;
use Illuminate\Http\Request;

class MagazineController extends Controller
{
    public function index()
    {
        $magazines = Magazine::where('isDelete', 0)
            ->orderByDesc('id')
            ->paginate(20);

        return view('admin.magazines.index', compact('magazines'));
    }

    public function create()
    {
        $magazine = new Magazine();
        return view('admin.magazines.create', compact('magazine'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:200',
            'month' => 'required|string|max:100',
            'year'  => 'required|integer|min:1900|max:2100',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
            'pdf'   => 'required|mimes:pdf|max:10240',
            'iStatus' => 'nullable|in:0,1',
        ]);

        $imageDir = env('MAGAZINE_IMAGE_DIR', 'uploads/magazine/images');
        $pdfDir   = env('MAGAZINE_PDF_DIR', 'uploads/magazine/pdfs');

        $imagePath = FileUploadHelper::upload($request->file('image'), $imageDir, null, $data['title']);
        $pdfPath   = FileUploadHelper::upload($request->file('pdf'), $pdfDir, null, $data['title']);

        Magazine::create([
            'title'   => $data['title'],
            'month'   => $data['month'],
            'year'    => $data['year'],
            'image'   => $imagePath ?? '',
            'pdf'     => $pdfPath ?? '',
            'iStatus' => (int)($data['iStatus'] ?? 1),
            'isDelete'=> 0,
        ]);

        return redirect()->route('admin.magazines.index')->with('success', 'Magazine added successfully.');
    }

    public function edit($id)
    {
        $magazine = Magazine::where('isDelete', 0)->findOrFail($id);
        return view('admin.magazines.edit', compact('magazine'));
    }

    public function update(Request $request, $id)
    {
        $magazine = Magazine::where('isDelete', 0)->findOrFail($id);

        $data = $request->validate([
            'title' => 'required|string|max:200',
            'month' => 'required|string|max:100',
            'year'  => 'required|integer|min:1900|max:2100',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'pdf'   => 'nullable|mimes:pdf|max:10240',
            'iStatus' => 'nullable|in:0,1',
        ]);

        $imageDir = env('MAGAZINE_IMAGE_DIR', 'uploads/magazine/images');
        $pdfDir   = env('MAGAZINE_PDF_DIR', 'uploads/magazine/pdfs');

        if ($request->hasFile('image')) {
            $magazine->image = FileUploadHelper::upload(
                $request->file('image'),
                $imageDir,
                $magazine->image,
                $data['title']
            );
        }

        if ($request->hasFile('pdf')) {
            $magazine->pdf = FileUploadHelper::upload(
                $request->file('pdf'),
                $pdfDir,
                $magazine->pdf,
                $data['title']
            );
        }

        $magazine->title = $data['title'];
        $magazine->month = $data['month'];
        $magazine->year  = $data['year'];
        $magazine->iStatus = (int)($data['iStatus'] ?? $magazine->iStatus);
        $magazine->save();

        return redirect()->route('admin.magazines.index')->with('success', 'Magazine updated successfully.');
    }

    public function destroy($id)
    {
        $magazine = Magazine::where('isDelete', 0)->findOrFail($id);

        // delete files
        FileUploadHelper::delete($magazine->image);
        FileUploadHelper::delete($magazine->pdf);

        // soft delete flag
        $magazine->isDelete = 1;
        $magazine->save();

        return redirect()->route('admin.magazines.index')->with('success', 'Magazine deleted successfully.');
    }

    public function toggleStatus($id)
    {
        $magazine = Magazine::where('isDelete', 0)->findOrFail($id);
        $magazine->iStatus = $magazine->iStatus ? 0 : 1;
        $magazine->save();

        return redirect()->route('admin.magazines.index')->with('success', 'Status updated.');
    }
}
