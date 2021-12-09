<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('products');
        Schema::create('products', function (Blueprint $table) {
            $table->increments('product_id')->autoIncrement()->unique();
           $table->foreignId('User_id')->references('User_id')->on('users');
            //$table->foreignId('category_id')->references('cate_id')->on('categories')->nullOnDelete();
            $table->string('product_name');
            $table->text('product_image');
            $table->string('product_type');
            $table->integer('product_quantity');
            $table->date('product_expire_date');
            $table->decimal('product_price');
            $table->text('product_desc');
            $table->integer('product_likes');
            $table->integer('product_dis_likes');
            $table->tinyInteger('product_action');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
