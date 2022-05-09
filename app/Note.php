<?php

namespace App;

use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    protected $fillable = [
        'user_id',
        'title',
        'content',
        'status',
        'deleted',
    ];

    public function allList()
    {
        return $this->where('user_id', Auth::user()->id)
                    ->where('deleted', false)
                    ->orderBy('id', 'desc')
                    ->get();
    }
}
