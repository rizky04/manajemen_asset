<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConsumableTransaction extends Model
{
    protected $fillable = ['item_id','employee_id', 'department_id','date','qty','type','note'];

    public function item() { return $this->belongsTo(ConsumableItem::class, 'item_id'); }
    public function employee() { return $this->belongsTo(Employee::class); }

         public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function getTypeLabelAttribute()
    {
        return match ($this->type) {
            'in' => 'Barang Masuk',
            'out' => 'Barang Keluar',
            'adjust' => 'Penyesuaian Stok',
            default => $this->type,
        };
    }

    //  protected static function booted()
    // {
    //     static::saved(function ($transaction) {
    //         $item = $transaction->item;
    //         if (!$item) return;

    //         if ($transaction->transaction_type === 'in') {
    //             $item->stock_qty += $transaction->qty;
    //         } elseif ($transaction->transaction_type === 'out') {
    //             $item->stock_qty -= $transaction->qty;
    //             if ($item->stock_qty < 0) $item->stock_qty = 0;
    //         }
    //         $item->save();
    //     });
    // }

    protected static function booted()
{
    // Saat transaksi dibuat
    static::created(function ($transaction) {
        $item = $transaction->item;
        if (!$item) return;

        switch ($transaction->type) {
            case 'in':
                $item->stock_qty += $transaction->qty;
                break;
            case 'out':
                $item->stock_qty -= $transaction->qty;
                break;
            case 'adjust':
                $item->stock_qty = $transaction->qty;
                break;
        }
        $item->save();
    });

    // Saat transaksi diupdate
    static::updating(function ($transaction) {
        $item = $transaction->item;
        if (!$item) return;

        // Rollback stok lama
        $oldQty  = $transaction->getOriginal('qty');
        $oldType = $transaction->getOriginal('type');

        switch ($oldType) {
            case 'in':
                $item->stock_qty -= $oldQty;
                break;
            case 'out':
                $item->stock_qty += $oldQty;
                break;
            case 'adjust':
                // abaikan, anggap adjust lama sudah final
                break;
        }

        // Terapkan stok baru
        switch ($transaction->type) {
            case 'in':
                $item->stock_qty += $transaction->qty;
                break;
            case 'out':
                $item->stock_qty -= $transaction->qty;
                break;
            case 'adjust':
                $item->stock_qty = $transaction->qty;
                break;
        }

        $item->save();
    });

    // Saat transaksi dihapus
    static::deleted(function ($transaction) {
        $item = $transaction->item;
        if (!$item) return;

        switch ($transaction->type) {
            case 'in':
                $item->stock_qty -= $transaction->qty;
                break;
            case 'out':
                $item->stock_qty += $transaction->qty;
                break;
            case 'adjust':
                // sama, adjust tidak di-rollback
                break;
        }

        $item->save();
    });
}

}
