<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        "job_type_id", "commune_id", "name", "surname", "second_surname", "birthday", "address", "email",
        "phone", "gender", "nationality", "working_day", "disability", "identification_type", "identification_id",
        "payment"
    ];

    public function commune() {
        return $this->belongsTo(Commune::class);
    }

    public function jobType() {
        return $this->belongsTo(JobType::class);
    }

    public function documents() {
        return $this->hasMany(Document::class);
    }
   
    public function scopeComplete($query)
    {
        return $query->with([
            'jobType:id,name',
            'commune:id,name,region_id',
            'commune.region:id,name',
            'documents' => function ($query) {
              return $query->where('document_type_id', 3);
            },
            'documents.service'=> function($query){
              return $query->with([
                'company:id,business_name,rut',
                'serviceType:id,name',
                'branchOffice:id,company_id,name',
                'branchOffice.company:id,business_name']);
            },
            'documents.type:id,name'
          ]);
    }

    public function scopeService($query,$id_service){
        return $query->with([
            'jobType:id,name',
            'commune:id,name,region_id',
            'commune.region:id,name',
            'documents' => function ($query) use ($id_service) {
              return $query->where('document_type_id', 3)->where('service_id',$id_service);
            },
            'documents.service'=> function($query){
              return $query->with([
                'company:id,business_name,rut',
                'serviceType:id,name',
                'branchOffice:id,company_id,name',
                'branchOffice.company:id,business_name']);
            },
            'documents.type:id,name'
          ]);
    }

    public static function validator($inputEmployee){
      return Validator::make($inputEmployee, [
        'commune_id' => ['required', 'integer'],
        'job_type_id' => ['nullable', 'integer'],
        'identification_id' => ['required', 'min:3', 'max:20'],
        'identification_type' => ['required', 'integer'],
        'name' => ['required', 'string', 'max:30'],
        'surname' => ['required', 'string', 'max:30'],
        'second_surname' => ['nullable', 'string', 'max:20'],
        'birthday' => ['required', 'date'],
        'address' => ['required', 'string', 'max:100'],
        'email' => ['required', 'regex:/^.+@.+$/i'],
        'phone' => ['required', 'integer'],
        // 'contract_start' => ['required', 'date'],
        // 'contract_finished' => ['nullable', 'date'],
        'gender' => ['required', 'integer'],
        // 'valid_document' => ['required', 'date'],
        'nationality' => ['required', 'string', 'max:50'],
        'working_day' => ['nullable', 'string', 'max:50'],
        'disability' => ['nullable', 'boolean'],
        'payment' => ['required', 'integer'],
      ]);
    }
}
