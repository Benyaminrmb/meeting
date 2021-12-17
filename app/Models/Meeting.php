<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable=[
        'name',
        'body',
        'shamsi_created_at',
    ];

    public function uploads() {
        return $this->belongsToMany(Upload::class, 'meeting_uploads')->orderBy('created_at', 'desc');
    }
}
