<?php

namespace App\Models;

use App\Models\Scopes\ActiveBranchOfficesScope;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchOffice extends Model
{
    use HasFactory;

    protected $fillable = ["company_id", "commune_id", "name", "address", "phone1", "phone2", "active"];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new ActiveBranchOfficesScope);
    }

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function commune() {
        return $this->belongsTo(Commune::class);
    }

    public function services() {
        return $this->hasMany(Service::class);
    }

    public function childCompanies()
    {
        return $this->belongToMany(Company::class, Service::class);
    }
}
