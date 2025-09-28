<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    protected $fillable = [
        'company_id',
        'asset_code',
        'name',
        'category_id',
        'vendor_id',
        'department_id',
        'location_id',
        'employee_id',
        'purchase_date',
        'purchase_price',
        'depreciation_val',
        'status',
        'serial_number',
        'image',
        'notes',
    ];

     public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'active' => 'Aktif',
            'borrowed' => 'Dipinjam',
            'disposed' => 'Dihapus',
            'repair' => 'Perbaikan',
            default => $this->status,
        };
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
    public function department()
    {
        return $this->belongsTo(Department::class);
    }
    public function location()
    {
        return $this->belongsTo(Location::class);
    }
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    public function transactions()
    {
        return $this->hasMany(AssetTransaction::class);
    }

    public function maintenances()
    {
        return $this->hasMany(AssetMaintenance::class);
    }

    public function disposals()
    {
        return $this->hasOne(AssetDisposal::class);
    }

    public function audits()
    {
        return $this->hasMany(AssetAudit::class);
    }

    protected static function booted()
{
    static::creating(function ($asset) {
        if (!$asset->asset_code) {
            $prefix = strtoupper(substr($asset->category->name ?? 'GEN', 0, 3));
            $date = now()->format('Ymd');
            $last = static::where('asset_code', 'like', "$prefix-$date-%")
                ->latest('id')
                ->first();
            $number = $last ? ((int)substr($last->asset_code, -4)) + 1 : 1;
            $asset->asset_code = sprintf("%s-%s-%04d", $prefix, $date, $number);
        }
    });
}



}
