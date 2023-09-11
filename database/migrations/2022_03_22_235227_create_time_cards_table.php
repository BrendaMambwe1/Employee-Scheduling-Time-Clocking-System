<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeCardsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('time_cards')){
        Schema::create('time_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shift_id')->constrained('shifts')->onUpdate('cascade')->onDelete('cascade');
            $table->string('actual_start_time')->nullable();
            $table->string('actual_end_time')->nullable();
            $table->string('status')->default('UNCOMPLETED');
            $table->string('standing')->default('ORDINARY');
            $table->timestamps();
        });
       }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('time_cards');
    }
}
