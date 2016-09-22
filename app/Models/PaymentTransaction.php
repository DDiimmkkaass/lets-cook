<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PaymentTransaction
 * @package App\Models
 */
class PaymentTransaction extends Model
{

    /**
     * @var array
     */
    protected $fillable = ['order_id', 'amount', 'currency', 'status', 'description', 'data'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}