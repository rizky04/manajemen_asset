<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetMaintenance extends Model
{
    protected $fillable = [
        'asset_id','maintenance_date','next_maintenance',
        'cost','vendor_id','description','status'
    ];

    public function asset() { return $this->belongsTo(Asset::class); }
    public function vendor() { return $this->belongsTo(Vendor::class); }

    // Event untuk update status asset
   protected static function booted()
{
    static::saved(function ($maintenance) {
        $asset = $maintenance->asset;
        if (!$asset) return;

        if ($maintenance->status === 'in_progress') {
            // Saat maintenance berjalan, asset jadi repair
            $asset->status = 'repair';
        } elseif ($maintenance->status === 'done') {
            // Saat selesai, asset kembali aktif
            $asset->status = 'active';
        }

        $asset->save();
    });
}


public function getStatusLabelAttribute()
{
    return match($this->status) {
        'scheduled' => 'Dijadwalkan',
        'in_progress' => 'Sedang Perbaikan',
        'done' => 'Selesai',
        default => $this->status,
    };
}



}
