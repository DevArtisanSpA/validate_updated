<?php

namespace App\Models;

use App\Models\Scopes\ActiveBranchOfficesScope;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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
        return $this->belongsToMany(Company::class, Service::class);
    }

    public static function edit($form_data)
    {
        return DB::table('branch_offices')
            ->where('id', $form_data['id'])
            ->update([
                'company_id' => $form_data['company_id'],
                'commune_id' => $form_data['commune_id'],
                'name' => $form_data['name'],
                'address' => $form_data['address'],
                'phone1' => $form_data['phone1'],
                'phone2' => $form_data['phone2'],
                'updated_at' => Carbon::now()
            ]);
    }
}
