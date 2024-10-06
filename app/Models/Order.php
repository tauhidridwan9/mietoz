<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Order extends Model
{
    use HasFactory, Notifiable;
   

    protected $fillable = [
        'user_id',
        'transaction_id',
        'total_amount',
        'status',
    ];

    // Relasi dengan User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi dengan Product (jika ada relasi)
    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
    public function customer()
    {
        return $this->belongsTo(User::class, 'user_id'); // Adjust 'user_id' if needed
    }
}
