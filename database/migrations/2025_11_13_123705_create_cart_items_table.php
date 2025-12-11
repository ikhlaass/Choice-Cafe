<?php
// database/migrations/xxxx_create_cart_items_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('menu_id');
            $table->integer('quantity');
            $table->decimal('price', 10, 2);
            $table->string('session_id');  // Untuk anonymous cart
            $table->timestamps();

            $table->foreign('menu_id')->references('id')->on('menus');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cart_items');
    }
};