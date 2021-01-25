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
        return $this->belongsTo(DocumentType::class, 'document_type_id');
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
    public function scopeBasic($query)
    {
        $query->with([
            'type:id,temporality_id,area_id,name',
            'type.temporality:id,name',
            'type.area:id,name',
            'validationState:id,name'
        ]);
    }

    public static function validator($inputDocument)
    {
        return Validator::make($inputDocument, [
            "document_type_id" => ['required', 'integer'],
            "service_id" => ['required', 'integer'],
            // "start" => ['required_if:month_year_registry,==,null', 'date'],
            // "month_year_registry"=> ['required_if:start,==,null','date_format:Y-M'],
            "validation_state_id" => ['required', 'integer'],
          ]);
        
    }
}
