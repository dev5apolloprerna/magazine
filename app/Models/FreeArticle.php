<?php
namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class FreeArticle extends Model
{
use HasFactory;


protected $table = 'free_article';

public $timestamps = false;

protected $fillable = [
'free_article',
'iStatus',
'isDelete',
'strIP',
'created_at',
'updated_at'
];


public function scopeActive($query)
{
return $query->where('iStatus', 1)->where('isDelete', 0);
}
}