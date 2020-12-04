<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchOffice extends Model
{
    use HasFactory;

    protected $fillable = ["company_id", "commune_id", "name", "address", "phone1", "phone2"];

    public function company() {
        $this->belongsTo(Company::class);
    }

    public function commune() {
        $this->belongsTo(Commune::class);
    }

    public function services() {
        $this->hasMany(Service::class);
    }
}
