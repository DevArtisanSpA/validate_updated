<?php

namespace App\Models;

use App\Models\Scopes\ActiveServicesScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        "service_type_id", "branch_office_id", "company_id", "description", "active", "start", "finished"
    ];

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new ActiveServicesScope);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function serviceType()
    {
        return $this->belongsTo(ServiceType::class);
    }

    public function branchOffice()
    {
        return $this->belongsTo(BranchOffice::class);
    }
    public function employees()
    {
        return $this->belongsToMany(Employee::class, 'documents')->distinct();
    }

    // public function employeesBase()
    // {
    //     return $this->belongsToMany(Employee::class, 'documents')->distinct()->wherehas('documents.type', function ($query) {
    //         return $query->where('area_id', 1);
    //     });
    // }
    // public function employeesMonthly()
    // {
    //     return $this->belongsToMany(Employee::class, 'documents')->distinct()->wherehas('documents.type', function ($query) {
    //         return $query->where('area_id', 2);
    //     });
    // }
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
            'company:id,business_name,rut,contact_email',
            'serviceType:id,name',
            'branchOffice:id,company_id,name',
            'branchOffice.company:id,business_name,contact_email'
        ]);
    }
    public function scopeCountDocuments($query, $area, $temp, $monthYear = null, $employee = null)
    {
        return $query->withCount([
            'documents as pending' => function ($query) use ($area, $temp, $monthYear, $employee) {
                $Q = $query->where('validation_state_id', 2)
                    ->whereHas('type', function (Builder $query) use ($area, $temp, $monthYear) {
                        return $query->where('area_id', $area)->where('temporality_id', $temp);
                    });
                if (!is_null($monthYear)) {
                    $Q = $Q->where('month_year_registry', $monthYear);
                }
                if (!is_null($employee)) {
                    $Q = $Q->where('employee_id', $employee);
                }
                return $Q;
            }, 'documents as approved' => function ($query) use ($area, $temp, $monthYear, $employee) {
                $Q = $query->where('validation_state_id', 3)
                    ->whereHas('type', function (Builder $query) use ($area, $temp, $monthYear) {
                        return $query->where('area_id', $area)->where('temporality_id', $temp);
                    });
                if (!is_null($monthYear)) {
                    $Q = $Q->where('month_year_registry', $monthYear);
                }
                if (!is_null($employee)) {
                    $Q = $Q->where('employee_id', $employee);
                }
                return $Q;
            }, 'documents as rejected' => function ($query) use ($area, $temp, $monthYear, $employee) {
                $Q = $query->where('validation_state_id', 4)
                    ->whereHas('type', function (Builder $query) use ($area, $temp, $monthYear) {
                        return $query->where('area_id', $area)->where('temporality_id', $temp);
                    });
                if (!is_null($monthYear)) {
                    $Q = $Q->where('month_year_registry', $monthYear);
                }
                if (!is_null($employee)) {
                    $Q = $Q->where('employee_id', $employee);
                }
                return $Q;
            }
        ]);
    }
}
