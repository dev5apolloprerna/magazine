<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MagazineMaster;
use Illuminate\Support\Facades\File;
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
            'title' => 'required|string|max:200',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'pdf'   => 'required|mimes:pdf|max:10240',
            'publish_date' => 'required',
            // 'year'  => 'required|numeric',
        ]);

            $month = (int) date('m', strtotime($request->publish_date));
            $year=date('Y',strtotime($request->publish_date));
            
        // ✅ upload into public_html/magazine/uploads/images and uploads/pdfs
        $imagePath = $this->uploadFile($request->file('image'), 'images');
        $pdfPath   = $this->uploadFile($request->file('pdf'), 'pdfs');

        MagazineMaster::create([
            'title'   => $request->title,
            'image'   => $imagePath, // ex: uploads/images/xxx.jpg
            'pdf'     => $pdfPath,   // ex: uploads/pdfs/xxx.pdf
            'month'   => $month,
            'year'    => $year,
            'publish_date' =>$request->publish_date,
            'iStatus' => $request->has('iStatus') ? 1 : 0,
            'isDelete'=> 0,
        ]);

        return redirect()->route('magazine.index')->with('success', 'Magazine added successfully.');
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
            'title' => 'required|string|max:200',
            'publish_date' => 'required',
            // 'year'  => 'required|numeric',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp,gif|max:2048',
            'pdf'   => 'nullable|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('image')) {
            $this->deleteFile($magazine->image);
            $magazine->image = $this->uploadFile($request->file('image'), 'images');
        }

        if ($request->hasFile('pdf')) {
            $this->deleteFile($magazine->pdf);
            $magazine->pdf = $this->uploadFile($request->file('pdf'), 'pdfs');
        }

            $month = (int) date('m', strtotime($request->publish_date));
            $year=date('Y',strtotime($request->publish_date));
            
        $magazine->title   = $request->title;
        $magazine->month   = $month;
        $magazine->year    = $year;
        $magazine->publish_date    = $request->publish_date;
        $magazine->iStatus = $request->has('iStatus') ? 1 : 0;
        $magazine->save();

        return redirect()->route('magazine.index')->with('success', 'Magazine updated successfully.');
    }

    public function destroy($id)
    {
        $magazine = MagazineMaster::findOrFail($id);

        $this->deleteFile($magazine->image);
        $this->deleteFile($magazine->pdf);

        // ✅ If you want soft delete:
        $magazine->isDelete = 1;
        $magazine->save();

        // ❌ If you want hard delete, use:
        // $magazine->delete();

        return response()->json(['success' => true, 'message' => 'Record deleted successfully.']);
    }

    public function bulkDelete(Request $request)
    {
        $ids = (array) $request->ids;

        $rows = MagazineMaster::whereIn('id', $ids)->get();
        foreach ($rows as $magazine) {
            $this->deleteFile($magazine->image);
            $this->deleteFile($magazine->pdf);

            $magazine->isDelete = 1;
            $magazine->save();
        }

        return redirect()->route('magazine.index')->with('success', 'Selected records deleted successfully.');
    }

    public function toggleStatus(Request $request)
    {
        $magazine = MagazineMaster::findOrFail($request->id);
        $magazine->iStatus = $magazine->iStatus ? 0 : 1;
        $magazine->save();

        return response()->json(['success' => true, 'message' => 'Status updated']);
    }

    /**
     * ✅ Upload file inside: public_html/magazine/uploads/{subdir}/
     * returns relative path for DB: uploads/{subdir}/filename.ext
     */
    private function uploadFile($file, $subdir)
    {
        $subdir = trim($subdir, '/'); // images / pdfs

        $ext = strtolower($file->getClientOriginalExtension());
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safe = Str::slug($name) ?: 'file';

        $fileName = time() . '_' . Str::random(8) . '_' . $safe . '.' . $ext;

        // ✅ absolute folder path in public_html/magazine/uploads/{subdir}
        $folderAbs = magazine_base_path("uploads/{$subdir}");
        ensure_dir($folderAbs);

        $file->move($folderAbs, $fileName);

        // ✅ store this in DB
        return "uploads/{$subdir}/{$fileName}";
    }

    /**
     * ✅ Delete file from public_html/magazine using stored relative path
     * ex: uploads/images/xxx.jpg
     */
    private function deleteFile($relativePath)
    {
        if (!$relativePath) return;

        $absPath = magazine_base_path($relativePath);

        if (File::exists($absPath)) {
            File::delete($absPath);
        }
    }
}
