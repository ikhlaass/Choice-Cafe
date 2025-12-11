<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['customer_name', 'customer_email', 'customer_phone', 'notes', 'total_amount', 'status', 'items'];

    protected $casts = [
        'items' => 'array',
    ];
}