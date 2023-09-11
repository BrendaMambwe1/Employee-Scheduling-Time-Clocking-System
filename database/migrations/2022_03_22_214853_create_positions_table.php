<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePositionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('positions')){
        Schema::create('positions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('work_hours')->nullable();
            $table->integer('breaks')->nullable();
            $table->float('hourly_rate')->nullable();
            $table->float('overtime_rate')->nullable();
            $table->float('leave_rate')->nullable();
            $table->foreignId('company_id')->constrained('companies')->onUpdate('cascade')->onDelete('cascade');
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
        Schema::dropIfExists('positions');
    }
}
