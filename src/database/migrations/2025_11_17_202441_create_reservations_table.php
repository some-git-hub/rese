<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReservationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('time');
            $table->tinyInteger('people');
            $table->tinyInteger('status')->default(0); // 0:予約中, 1:来店済み, 2:予約キャンセル,
            $table->tinyInteger('rating')->default(0); // 0:未評価, 1-5:評価, -1:評価しない,
            $table->text('comment')->nullable();
            $table->timestamps();
            $table->timestamp('canceled_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reservations');
    }
}
