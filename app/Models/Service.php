<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        "service_type_id", "branch_office_id", "company_id", "description"
    ];

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function documents() {
        return $this->hasMany(Document::class);
    }

    public function type() {
        return $this->belongsTo(ServiceType::class);
    }

    public function branchOffice() {
        return $this->belongsTo(BranchOffice::class);
    }
}
