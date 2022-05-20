<?php

namespace App\Models;

use App\Models\Book;
use App\Models\Penalty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name'
    ];

    public function books()
    {
        return $this->belongsToMany(Book::class,'book_member','member_id','book_id')
        ->withPivot('transaction_status','borrowed_at','due_date')
        ->withTimestamps();
    }

    public function penalty()
    {
        return $this->hasOne(Penalty::class);
    }
}
