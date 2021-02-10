<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceType extends Model
{
    use HasFactory;

    protected $fillable = [
        "name"
    ];

    public function services()
    {
        return $this->hasMany(Service::class);
    }

    public function documents()
    {
        return $this->hasMany(ServiceType::class);
    }
    public function requiredCompanyBase()
    {
        return $this->hasMany(DocumentType::class)
            ->where('optional', false)
            ->where('area_id', 2)
            ->where('temporality_id', 1);
    }
    public function requiredEmployeeBase()
    {
        return $this->hasMany(DocumentType::class)
            ->where('optional', false)
            ->where('area_id', 1)
            ->where('temporality_id', 1);
    }
}
