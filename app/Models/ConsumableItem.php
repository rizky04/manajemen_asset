<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsumableItem extends Model
{
    protected $fillable = ['company_id','item_code','name','category_id','stock_qty','unit','location_id'];

    public function company() { return $this->belongsTo(Company::class); }
    public function category() { return $this->belongsTo(Category::class); }
    public function location() { return $this->belongsTo(Location::class); }

    public function transactions()
    {
        return $this->hasMany(ConsumableTransaction::class, 'item_id');
    }

      // Auto-generate item_code
    protected static function booted()
    {
        static::creating(function ($item) {
            if (!$item->item_code) {
                $latest = self::latest('id')->first();
                $nextId = $latest ? $latest->id + 1 : 1;
                $item->item_code = 'CON-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }
        });
    }


    public function getStockStatusAttribute()
{
    return $this->stock_qty > 0 ? 'Tersedia' : 'Habis';
}
}
