<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
