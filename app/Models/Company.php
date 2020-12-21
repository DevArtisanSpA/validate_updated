<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        "commercial_business_id", "rut", "business_name", "contact_name", "contact_email", "affiliation", "affiliation_date", "active"
    ];

    public function commercialBusiness() {
        return $this->belongsTo(CommercialBusiness::class);
    }

    public function services() {
        return $this->hasMany(Service::class);
    }

    public function branchOffices() {
        return $this->hasMany(BranchOffice::class);
    }

    public function parentBranchOffices()
    {
        return $this->belongsToMany(BranchOffice::class, Service::class);
    }
}
