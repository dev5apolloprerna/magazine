<?php
namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FreeArticle;
use Illuminate\Support\Facades\DB;


class FreeArticleController extends Controller
{
    public function index()
    {
    $free_articles = FreeArticle::orderBy('id', 'desc')->paginate(10);
    return view('admin.free_article.index', compact('free_articles'));
    }
    
    
    public function store(Request $request)
    {
    $request->validate([
    'free_article' => 'required|numeric',
    ]);
    
    
    FreeArticle::create([
    'free_article' => $request->free_article,
    ]);
    
    
    return redirect()->back()->with('success', 'Free Article Added Successfully.');
    }
    
    
    public function edit($id)
    {
    $data = FreeArticle::findOrFail($id);
    return response()->json($data);
    }
    
    
    public function update(Request $request, $id)
    {
    $request->validate([
    'free_article' => 'required|numeric',
    ]);
    
    
    $data = FreeArticle::findOrFail($id);
    $data->update([
    'free_article' => $request->free_article,
    ]);
    
    
    return redirect()->back()->with('success', 'Free Article Updated Successfully.');
    }
    
    
    public function destroy($id)
    {
    $data = FreeArticle::findOrFail($id);
    $data->delete();
    
    
    return redirect()->back()->with('success', 'Free Article Deleted Successfully.');
    }


public function bulkDelete(Request $request)
{
$ids = $request->ids;
if (!empty($ids)) {
FreeArticle::whereIn('id', $ids)->update(['isDelete' => 1]);
return response()->json(['status' => true, 'message' => 'Selected records deleted.']);
}
return response()->json(['status' => false, 'message' => 'No records selected.']);
}
}