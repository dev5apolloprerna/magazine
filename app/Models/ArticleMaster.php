<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ArticleMaster extends Model
{
    protected $table = 'article_master';
    protected $primaryKey = 'article_id';
    public $timestamps = true;


    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';


    protected $fillable = [
    'magazine_id',
    'strGuid',
    'article_title',
    'article_image',
    'article_pdf',
    'isPaid',
    'iStatus',
    'isDelete',
    ];


    public function magazine()
    {
    return $this->belongsTo(MagazineMaster::class, 'magazine_id', 'id');
    }
}