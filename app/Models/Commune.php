<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Commune extends Model
{
    use HasFactory;

    public function branchOffices() {
        return $this->hasMany(BranchOficce::class);
    }

    public function employees() {
        return $this->hasMany(Employee::class);
    }

    public function region() {
        return $this->belongsTo(Region::class);
    }
}
