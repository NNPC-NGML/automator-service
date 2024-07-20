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
        Schema::create('process_flow_steps', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string("name")->comment('name of the step ');
            $table->string("step_route")->comment('step route ');
            $table->string("assignee_user_route")->comment('assignee user route ');
            $table->integer("next_user_designation")->comment('next user designation ')->nullable();
            $table->integer("next_user_department")->comment('next user department')->nullable();
            $table->integer("next_user_unit")->comment('next user unit ')->nullable();
            $table->integer("process_flow_id")->comment('process flow id ')->nullable();
            $table->integer("next_user_location")->comment('next user location ')->nullable();
            $table->enum("step_type", ['create', 'delete', 'update', 'approve_auto_assign', 'approve_manual_assign'])->comment('step type ')->default("approve_auto_assign");
            $table->enum("user_type", ['user', 'supplier', 'customer', 'contractor'])->comment('user type ')->default("customer");
            $table->integer("next_step_id")->comment('next step id column')->nullable();
            $table->boolean("status")->comment('')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('process_flow_steps');
    }
};
