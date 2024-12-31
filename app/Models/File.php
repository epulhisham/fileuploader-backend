<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    use HasFactory;

    protected $table = 'files';

    protected $fillable = [
        'name',
        'path',
        'extension',
        'size',
        'folder_id',
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }
}
