<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['name', 'description'];

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function consumableItems()
    {
        return $this->hasMany(ConsumableItem::class);
    }
}
