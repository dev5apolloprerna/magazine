<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ArticleMaster;
use App\Models\MagazineMaster;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ArticleMasterController extends Controller
{
    public function index(Request $request)
    {
        $q = ArticleMaster::with('magazine')
            ->where('isDelete', 0);

        if ($request->filled('magazine_id')) {
            $q->where('magazine_id', (int) $request->magazine_id);
        }

        if ($request->filled('q')) {
            $search = trim($request->q);
            $q->where('article_title', 'like', "%{$search}%");
        }

        $articles = $q->orderByDesc('article_id')->paginate(15)->withQueryString();

        $magazines = MagazineMaster::where('isDelete', 0)
            ->where('iStatus', 1)
            ->orderBy('title')
            ->get(['id', 'title']);

        return view('admin.articles.index', compact('articles', 'magazines'));
    }

    public function create()
    {
        $magazines = MagazineMaster::where('isDelete', 0)
            ->where('iStatus', 1)
            ->orderBy('title')
            ->get(['id', 'title']);

        return view('admin.articles.create', compact('magazines'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'magazine_id'   => 'required|integer|min:1',
            'article_title' => 'required|string|max:255',
            // ✅ image NOT required
            'article_image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'article_pdf'   => 'required|file|mimes:pdf|max:10240',
            'isPaid'        => 'nullable|in:0,1',
            'iStatus'       => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // ✅ only upload image if exists
        $imgPath = null;
        if ($request->hasFile('article_image')) {
            $imgPath = $this->uploadToPublic($request->file('article_image'), 'uploads/articles/images');
        }

        $pdfPath = $this->uploadToPublic($request->file('article_pdf'), 'uploads/articles/pdfs');

        ArticleMaster::create([
            'magazine_id'   => (int) $request->magazine_id,
            'article_title' => $request->article_title,
            'article_image' => $imgPath,          // ✅ can be null
            'article_pdf'   => $pdfPath,
            'isPaid'        => $request->filled('isPaid') ? (int) $request->isPaid : 0,
            'iStatus'       => $request->filled('iStatus') ? (int) $request->iStatus : 1,
            'isDelete'      => 0,
        ]);

        return redirect()->route('admin.articles.index')->with('success', 'Article created successfully');
    }

    public function edit($id)
    {
        $article = ArticleMaster::where('article_id', (int) $id)
            ->where('isDelete', 0)
            ->firstOrFail();

        $magazines = MagazineMaster::where('isDelete', 0)
            ->where('iStatus', 1)
            ->orderBy('title')
            ->get(['id', 'title']);

        return view('admin.articles.edit', compact('article', 'magazines'));
    }

    public function update(Request $request, $id)
    {
        $article = ArticleMaster::where('article_id', (int) $id)
            ->where('isDelete', 0)
            ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'magazine_id'   => 'required|integer|min:1',
            'article_title' => 'required|string|max:255',
            // ✅ image NOT required
            'article_image' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:2048',
            'article_pdf'   => 'nullable|file|mimes:pdf|max:10240',
            'isPaid'        => 'nullable|in:0,1',
            'iStatus'       => 'nullable|in:0,1',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        if ($request->hasFile('article_image')) {
            $this->deleteFromPublic($article->article_image);
            $article->article_image = $this->uploadToPublic($request->file('article_image'), 'uploads/articles/images');
        }

        if ($request->hasFile('article_pdf')) {
            $this->deleteFromPublic($article->article_pdf);
            $article->article_pdf = $this->uploadToPublic($request->file('article_pdf'), 'uploads/articles/pdfs');
        }

        $article->magazine_id   = (int) $request->magazine_id;
        $article->article_title = $request->article_title;
        $article->isPaid        = $request->filled('isPaid') ? (int) $request->isPaid : 0;

        if ($request->filled('iStatus')) {
            $article->iStatus = (int) $request->iStatus;
        }

        $article->save();

        return redirect()->route('admin.articles.index')->with('success', 'Article updated successfully');
    }

    public function destroy($id)
    {
        $article = ArticleMaster::where('article_id', (int) $id)
            ->where('isDelete', 0)
            ->firstOrFail();

        $article->isDelete = 1;
        $article->save();

        return redirect()->route('admin.articles.index')->with('success', 'Article deleted successfully');
    }

    public function toggleStatus($id)
    {
        $article = ArticleMaster::where('article_id', (int) $id)
            ->where('isDelete', 0)
            ->firstOrFail();

        $article->iStatus = $article->iStatus == 1 ? 0 : 1;
        $article->save();

        return back()->with('success', 'Status updated');
    }

    // ✅ accepts null file
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
