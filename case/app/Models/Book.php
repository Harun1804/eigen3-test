<?php

namespace App\Models;

use App\Models\Member;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'title',
        'author',
        'stock'
    ];

    public function members()
    {
        return $this->belongsToMany(Member::class,'book_member','book_id','member_id');
    }
}
