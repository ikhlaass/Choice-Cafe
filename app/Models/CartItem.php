<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = ['menu_id', 'quantity', 'price', 'session_id'];

    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}