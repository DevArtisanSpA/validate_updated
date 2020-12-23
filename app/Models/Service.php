<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        "service_type_id", "branch_office_id", "company_id", "description", "active", "start", "finished"
    ];

    public function company() {
        return $this->belongsTo(Company::class);
    }

    public function documents() {
        return $this->hasMany(Document::class);
    }

    public function serviceType() {
        return $this->belongsTo(ServiceType::class);
    }

    public function branchOffice() {
        return $this->belongsTo(BranchOffice::class);
    }

    /**
     * Scope a query to only include pending (unaccepted) services.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('active', '=', '0')->whereNull(['start', 'finished']);
    }

    /**
     * Scope a query to only include active (accepted) services.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('active', '=', '1');
    }

    /**
     * Scope a query to get all service relationships.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeComplete($query)
    {
        return $query->with([
            'company:id,business_name',
            'serviceType:id,name',
            'branchOffice:id,company_id,name',
            'branchOffice.company:id,business_name'
        ]);
    }
}
