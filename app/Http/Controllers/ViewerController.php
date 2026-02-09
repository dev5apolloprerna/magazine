<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ArticleMaster;
use App\Models\MagazineMaster;

class ViewerController extends Controller
{
    public function index($guid)
    {
        if (empty($guid)) {
            abort(404, 'Invalid article link');
        }
        $article = ArticleMaster::where('strGuid',$guid)->where('isDelete', 0)->first();
        $magazine = MagazineMaster::where(['id'=>$article->magazine_id])->first();
        if (!$article || empty($article->article_pdf)) {
            abort(404, 'Article not found');
        }
        return view('pdf-viewer',compact('article','magazine'));
    }
}
