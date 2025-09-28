<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetTransaction extends Model
{
    protected $fillable = [
        'asset_id','transaction_type',
        'from_company_id','to_company_id',
        'from_employee_id','to_employee_id',
        'from_location_id','to_location_id',
        'date','note'
    ];

    public function asset() { return $this->belongsTo(Asset::class); }
    public function fromCompany() { return $this->belongsTo(Company::class, 'from_company_id'); }
    public function toCompany() { return $this->belongsTo(Company::class, 'to_company_id'); }
    public function fromEmployee() { return $this->belongsTo(Employee::class, 'from_employee_id'); }
    public function toEmployee() { return $this->belongsTo(Employee::class, 'to_employee_id'); }
    public function fromLocation() { return $this->belongsTo(Location::class, 'from_location_id'); }
    public function toLocation() { return $this->belongsTo(Location::class, 'to_location_id'); }


      public function getStatusLabelAttribute()
    {
        return match($this->transaction_type) {
            'borrow' => 'Dipinjam',
            'return' => 'Dikembalikan',
            'transfer' => 'Transfer',
            default => $this->transaction_type,
        };
    }


   protected static function booted()
{
    static::saved(function ($transaction) {
        $asset = $transaction->asset;

        if ($transaction->transaction_type == 'borrow') {
            $asset->status = 'borrowed';
        } elseif ($transaction->transaction_type == 'return') {
            $asset->status = 'active';
        } elseif ($transaction->transaction_type == 'transfer') {
            $asset->status = 'active';
            $asset->company_id = $transaction->to_company_id ?? $asset->company_id;
            $asset->location_id = $transaction->to_location_id ?? $asset->location_id;
            $asset->employee_id = $transaction->to_employee_id ?? $asset->employee_id;
        }

        $asset->save();
    });
}



}
