<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('process_flow_histories', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->integer("task_id")->comment()->nullable();
            $table->integer("step_id")->comment();
            $table->integer("process_flow_id")->comment();
            $table->integer("user_id")->comment();
            $table->integer("for")->comment();
            $table->integer("for_id")->comment();
            $table->integer("form_builder_id")->comment();
            $table->integer("approval")->comment()->default(0);
            $table->integer("status")->comment()->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('process_flow_histories');
    }
};
