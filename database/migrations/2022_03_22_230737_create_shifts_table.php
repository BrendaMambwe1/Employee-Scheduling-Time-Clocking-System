<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShiftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('shifts')){
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();
            $table->string('date');
            $table->string('isRecurring')->default('FALSE');
            $table->string('recurring_period')->nullable();
            $table->time('scheduled_start_time');
            $table->time('scheduled_end_time');
            $table->string('longitude')->nullable();
            $table->string('latitude')->nullable();
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->foreignId('posted_by')->constrained('employees')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('department_id')->constrained('departments')->onUpdate('cascade')->onDelete('cascade');
            $table->foreignId('company_id')->constrained('companies')->onUpdate('cascade')->onDelete('cascade');
            $table->string('status')->default('UNAVALIABLE');
            $table->text('note')->nullable();
            $table->string('is_acknowledged')->default('FALSE');
            $table->string('hasOvertime')->default('FALSE');
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
        Schema::dropIfExists('shifts');
    }
}
