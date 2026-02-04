<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleMaster;
use App\Models\MagazineMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ArticleMasterController extends Controller
{
    public function index($magazineId)
    {
        $magazine=MagazineMaster::select('title')->where(['id'=>$magazineId])->first();

        $articles = ArticleMaster::where('magazine_id', $magazineId)
            ->where('isDelete', 0)
            ->orderByDesc('article_id')
            ->paginate(15);

        return view('admin.article_master.index', compact('articles', 'magazineId','magazine'));
    }

    public function store(Request $request, $magazineId)
    {
        $request->validate([
            'article_title' => 'required|string|max:255',
            'isPaid'        => 'required|in:0,1',
            'iStatus'       => 'required|in:0,1',
            'article_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048', // 2MB
            'article_pdf'   => 'required|mimes:pdf|max:10240', // 10MB
        ]);

         $imgPath = null;
        if ($request->hasFile('article_image')) {
            $imgPath = $this->uploadToPublic($request->file('article_image'), 'uploads/articles/images');
        }

        $pdfPath = $this->uploadToPublic($request->file('article_pdf'), 'uploads/articles/pdfs');

        ArticleMaster::create([
            'magazine_id'   => (int) $magazineId,
            'article_title' => $request->article_title,
            'article_image' => $imgPath,
            'article_pdf'   => $pdfPath,
            'isPaid'        => (int) $request->isPaid,
            'view_count'    => 0,
            'iStatus'       => (int) $request->iStatus,
            'isDelete'      => 0,
            'created_at'    => now(),
            'updated_at'    => now(),
        ]);

        return redirect()->route('admin.magazines.articles.index', $magazineId)
            ->with('success', 'Article added successfully.');
    }

    public function update(Request $request, $magazineId, $articleId)
    {
        $article = ArticleMaster::where('magazine_id', $magazineId)
            ->where('article_id', $articleId)
            ->where('isDelete', 0)
            ->firstOrFail();

        $request->validate([
            'article_title' => 'required|string|max:255',
            'isPaid'        => 'required|in:0,1',
            'iStatus'       => 'required|in:0,1',
            'article_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'article_pdf'   => 'nullable|mimes:pdf|max:10240', // optional in edit
        ]);

        $imagePath = $article->article_image;
        $pdfPath = $article->article_pdf;
        if ($request->hasFile('article_image')) {
            $this->deleteFromPublic($article->article_image);
            $article->article_image = $this->uploadToPublic($request->file('article_image'), 'uploads/articles/images');
        }

        if ($request->hasFile('article_pdf')) {
            $this->deleteFromPublic($article->article_pdf);
            $article->article_pdf = $this->uploadToPublic($request->file('article_pdf'), 'uploads/articles/pdfs');
        }

        $article->article_title = $request->article_title;
        $article->isPaid        = $request->filled('isPaid') ? (int) $request->isPaid : 0;

        if ($request->filled('iStatus')) {
            $article->iStatus = (int) $request->iStatus;
        }

        $article->save();


        return redirect()->route('admin.magazines.articles.index', $magazineId)
            ->with('success', 'Article updated successfully.');
    }
    public function toggleStatus(Request $request)
    {
        $article = ArticleMaster::findOrFail($request->id);
        $article->iStatus = $article->iStatus ? 0 : 1;
        $article->save();

        return response()->json(['success' => true, 'message' => 'Status updated']);
    }

    
    public function bulkDelete(Request $request, $magazineId)
    {
        $ids = $request->input('ids', []);
        if (!is_array($ids) || count($ids) === 0) {
            return redirect()->route('admin.magazines.articles.index', $magazineId)
                ->with('error', 'Please select at least one article.');
        }

        ArticleMaster::where('magazine_id', $magazineId)
            ->whereIn('article_id', $ids)
            ->where('isDelete', 0)
            ->update([
                'isDelete'   => 1,
                'updated_at' => now(),
            ]);

        return redirect()->route('admin.magazines.articles.index', $magazineId)
            ->with('success', 'Selected articles deleted successfully.');
    }


    public function destroy($magazineId, $articleId)
    {
        $article = ArticleMaster::where('magazine_id', $magazineId)
            ->where('article_id', $articleId)
            ->where('isDelete', 0)
            ->firstOrFail();

        $article->update([
            'isDelete'   => 1,
            'updated_at' => now(),
        ]);

        return redirect()->route('admin.magazines.articles.index', $magazineId)
            ->with('success', 'Article deleted successfully.');
    }
    private function uploadToPublic($file, string $folder): ?string
    {
        if (!$file) return null;

        // If you set MAGAZINE_IMAGE_DIR, it should be absolute path.
        // Otherwise, use public_path($folder)
        $absFolder = env('MAGAZINE_IMAGE_DIR') ? rtrim(env('MAGAZINE_IMAGE_DIR'), '/\\') : $folder;

        // If MAGAZINE_IMAGE_DIR is set, append folder only if it's not already included
        if (env('MAGAZINE_IMAGE_DIR')) {
            $absFolder = rtrim(env('MAGAZINE_IMAGE_DIR'), '/\\') . DIRECTORY_SEPARATOR . trim($folder, '/\\');
        }

        if (!is_dir($absFolder)) {
            mkdir($absFolder, 0777, true);
        }

        $ext  = strtolower($file->getClientOriginalExtension());
        $name = time() . '_' . uniqid() . '.' . $ext;

        $file->move($absFolder, $name);

        // DB path (relative to public)
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
