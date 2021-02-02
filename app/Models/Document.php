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
    public static function getDocCompanyById($id_company)
    {
        $list = Document::wherehas('service.company', function ($query) use ($id_company) {
            return $query->where('id', $id_company);
        })->wherehas('type', function ($query) {
            return $query->where('temporality_id', 1);
        })->select('validation_state_id')->get();
        $total = $list->count();
        $complete = $list->filter(function ($value, $key) {
            return $value->validation_state_id == 3;
        })->count();
        if ($complete == 0) {
            $percent = round(0, 1);
        } else {
            $percent = round(($complete / $total) * 100, 1);
        }
        return ['total' => $total, 'complete' => $complete, 'percent' => $percent];
    }
    public static function getDocEmployeeById($id_company)
    {
        $list = Document::wherehas('employee')
            ->wherehas('service.company', function ($query) use ($id_company) {
                return $query->where('id', $id_company);
            })
            ->wherehas('type', function ($query) {
                return $query->where('temporality_id', 1);
            })->select('validation_state_id')->get();
        $total = $list->count();
        $complete = $list->filter(function ($value, $key) {
            return $value->validation_state_id == 3;
        })->count();
        if ($complete == 0) {
            $percent = round(0, 1);
        } else {
            $percent = round(($complete / $total) * 100, 1);
        }
        return ['total' => $total, 'complete' => $complete, 'percent' => $percent];
    }
}
