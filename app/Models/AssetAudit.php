<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetAudit extends Model
{
    protected $fillable = ['asset_id', 'audit_date', 'auditor_id', 'condition', 'remarks'];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }
    public function auditor()
    {
        return $this->belongsTo(Employee::class, 'auditor_id');
    }

      // Accessor untuk label kondisi audit
    public function getConditionLabelAttribute()
    {
        return match($this->condition) {
            'good' => 'Baik',
            'damaged' => 'Rusak',
            'missing' => 'Hilang',
            default => $this->condition,
        };
    }

    // Event untuk update status asset berdasarkan audit
    protected static function booted()
    {
        static::saved(function ($audit) {
            $asset = $audit->asset;
            if (!$asset) return;

            if ($audit->condition === 'damaged') {
                $asset->status = 'repair';
            } elseif ($audit->condition === 'missing') {
                $asset->status = 'disposed';
            } elseif ($audit->condition === 'good') {
                $asset->status = 'active';
            }

            $asset->save();
        });
    }
}
