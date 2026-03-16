<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MagazineMaster;
use Illuminate\Support\Str;

class MagazineController extends Controller
{
    public function index()
    {
        $magazines = MagazineMaster::where('isDelete', 0)->latest()->paginate(10);
        return view('admin.magazine.index', compact('magazines'));
    }

    public function create()
    {
        return view('admin.magazine.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'        => 'required|string|max:200',
            'image'        => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'pdf'          => 'nullable|mimes:pdf|max:10240',
            'publish_date' => 'required',
        ]);

        $month = (int) date('m', strtotime($request->publish_date));
        $year  = date('Y', strtotime($request->publish_date));

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $this->uploadToPublic($request->file('image'), 'uploads/images');
        }

        $pdfPath = null;
        if ($request->hasFile('pdf')) {
            $pdfPath = $this->uploadToPublic($request->file('pdf'), 'uploads/pdfs');
        }

        MagazineMaster::create([
            'title'        => $request->title,
            'image'        => $imagePath,
            'pdf'          => $pdfPath,
            'month'        => $month,
            'year'         => $year,
            'publish_date' => $request->publish_date,
            'iStatus'      => $request->has('iStatus') ? 1 : 0,
            'isDelete'     => 0,
        ]);

        return redirect()->route('magazine.index')
            ->with('success', 'Magazine added successfully.');
    }

    public function edit($id)
    {
        $magazine = MagazineMaster::findOrFail($id);
        return view('admin.magazine.form', compact('magazine'));
    }

    public function update(Request $request, $id)
    {
        $magazine = MagazineMaster::findOrFail($id);

        $request->validate([
            'title'        => 'required|string|max:200',
            'publish_date' => 'required',
            'image'        => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'pdf'          => 'nullable|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('image')) {
            $this->deleteFromPublic($magazine->image);
            $magazine->image = $this->uploadToPublic($request->file('image'), 'uploads/images');
        }

        if ($request->hasFile('pdf')) {
            $this->deleteFromPublic($magazine->pdf);
            $magazine->pdf = $this->uploadToPublic($request->file('pdf'), 'uploads/pdfs');
        }

        $month = (int) date('m', strtotime($request->publish_date));
        $year  = date('Y', strtotime($request->publish_date));

        $magazine->title        = $request->title;
        $magazine->month        = $month;
        $magazine->year         = $year;
        $magazine->publish_date = $request->publish_date;
        $magazine->iStatus      = $request->has('iStatus') ? 1 : 0;
        $magazine->save();

        return redirect()->route('magazine.index')
            ->with('success', 'Magazine updated successfully.');
    }

    public function destroy($id)
    {
        $magazine = MagazineMaster::findOrFail($id);

        $this->deleteFromPublic($magazine->image);
        $this->deleteFromPublic($magazine->pdf);

        $magazine->isDelete = 1;
        $magazine->save();

        return response()->json([
            'success' => true,
            'message' => 'Record deleted successfully.'
        ]);
    }

    public function bulkDelete(Request $request)
    {
        $ids = (array) $request->ids;

        $rows = MagazineMaster::whereIn('id', $ids)->get();

        foreach ($rows as $magazine) {
            $this->deleteFromPublic($magazine->image);
            $this->deleteFromPublic($magazine->pdf);

            $magazine->isDelete = 1;
            $magazine->save();
        }

        return redirect()->route('magazine.index')
            ->with('success', 'Selected records deleted successfully.');
    }

    public function toggleStatus(Request $request)
    {
        $magazine = MagazineMaster::findOrFail($request->id);
        $magazine->iStatus = $magazine->iStatus ? 0 : 1;
        $magazine->save();

        return response()->json([
            'success' => true,
            'message' => 'Status updated'
        ]);
    }

    private function uploadToPublic($file, string $folder): ?string
    {
        if (!$file) return null;

        $absFolder = env('MAGAZINE_IMAGE_DIR') ? rtrim(env('MAGAZINE_IMAGE_DIR'), '/\\') : $folder;

        if (env('MAGAZINE_IMAGE_DIR')) {
            $absFolder = rtrim(env('MAGAZINE_IMAGE_DIR'), '/\\') . DIRECTORY_SEPARATOR . trim($folder, '/\\');
        }

        if (!is_dir($absFolder)) {
            mkdir($absFolder, 0777, true);
        }

        $ext  = strtolower($file->getClientOriginalExtension());
        $name = time() . '_' . uniqid() . '.' . $ext;

        $file->move($absFolder, $name);

        return trim($folder, '/\\') . '/' . $name;
    }

    private function deleteFromPublic(?string $relativePath): void
    {
        if (!$relativePath) return;

        $abs = public_path($relativePath);
        if (file_exists($abs)) {
            @unlink($abs);
        }
    }
}