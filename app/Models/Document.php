<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        "document_type_id", "service_id", "branch_office_id", "employee_id",
        "start", "finish", "month_year_registry", "path_data", "observations"
    ];

    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function validationState() {
        return $this->belongsTo(ValidationState::class);
    }

    public function type() {
        return $this->belongsTo(DocumentType::class);
    }

    public function employee() {
        return $this->belongsTo(Employee::class);
    }
}
