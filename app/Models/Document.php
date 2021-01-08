<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        "document_type_id", "service_id", "employee_id", "validation_state_id",
        "start", "finish", "month_year_registry", "path_data", "observations"
    ];

    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function validationState()
    {
        return $this->belongsTo(ValidationState::class);
    }
    public function type()
    {
        return $this->belongsTo(DocumentType::class,'document_type_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public static function validator($inputDocument){
        return Validator::make($inputDocument, [
            "document_type_id" => ['required', 'integer'],
            "service_id" => ['required', 'integer'],
            "employee_id" => ['required', 'integer'],
            "start" => ['required', 'date'],
            "finish" => ['required', 'date'],
            // "month_year_registry" => [],
            // "path_data" => [],
            "validation_state_id" => ['required', 'integer'],
            "id" => ['required', 'integer'],
          ]);
    }
}
