<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temporality extends Model
{
    use HasFactory;

    protected $fillable = [
        "name"
    ];

    public function documentTypes() {
        return $this->hasMany(DocumentTypes::class);
    }
}
