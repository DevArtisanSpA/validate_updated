<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentType extends Model
{
    use HasFactory;

    protected $fillable = ["temporality_id", "area_id", "name","service_type_id","optional"];

    public function temporality() {
        return $this->belongsTo(Temporality::class);
    }

    public function area() {
        return $this->belongsTo(Area::class);
    }

    public function documents() {
        return $this->hasMany(Document::class);
    }

    public function service_type() {
        return $this->belongsTo(ServiceType::class);
    }
}
