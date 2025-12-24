<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Phiki\Adapters\Laravel\Components\Code;

class Company extends Model
{
    protected $fillable = [
        'code', 'name', 'name_short', 'address', 'npwp', 'phone', 'email'
    ];

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function consumableItems()
    {
        return $this->hasMany(ConsumableItem::class);
    }
}
