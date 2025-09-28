<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetDisposal extends Model
{
    protected $fillable = ['asset_id','disposal_date','method','value','reason'];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

     // Accessor untuk label metode disposisi
    public function getMethodLabelAttribute()
    {
        return match($this->method) {
            'sold' => 'Dijual',
            'scrapped' => 'Dibuang',
            'donated' => 'Disumbangkan',
            default => $this->method,
        };
    }

     protected static function booted()
    {
        static::saved(function ($disposal) {
            $asset = $disposal->asset;
            if ($asset) {
                $asset->status = 'disposed';
                $asset->save();
            }
        });
    }
}
