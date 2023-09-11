<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTimeOffRequestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('time_off_requests')){
            Schema::create('time_off_requests', function (Blueprint $table) {
                $table->id();
                $table->string('start_date_time');
                $table->string('end_date_time');
                $table->string('is_all_day');
                $table->integer('period');
                $table->text('note')->nullable();
                $table->string('status')->default('PENDING');
                $table->foreignId('applicant_id')->constrained('employees')->onUpdate('cascade')->onDelete('cascade');
                $table->unsignedBigInteger('action_by')->nullable();
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
        Schema::dropIfExists('time_off_requests');
    }
}
