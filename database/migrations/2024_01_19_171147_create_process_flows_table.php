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
        Schema::create('process_flows', function (Blueprint $table) {
            $table->bigInteger("id")->primary();
            $table->string("name")->comment("The process flow name ");
            $table->integer("start_step_id")->comment("The process flow step id ")->nullable();
            $table->integer("start_user_designation")->comment('next user designation ')->nullable();
            $table->integer("start_user_department")->comment('next user department')->nullable();
            $table->integer("start_user_unit")->comment('next user unit ')->nullable();
            $table->enum("frequency", ['daily', 'weekly', 'hourly', 'monthly', 'yearly', 'none'])->default('none');
            $table->boolean("status")->comment("process status eg 1 or true for active status and 0 or false for deactivated status ")->default(true);
            $table->enum("frequency_for", ['users', 'customers', 'suppliers', 'contractors', 'none'])->default('none');
            $table->string("day")->comment("day is for selecting a particular day for the frequency")->nullable();
            $table->string("week")->comment("week is for selecting a particular week for the frequency")->nullable();
            $table->timestamp('last_run')->nullable()->comment('The last time this process flow ran to assign tasks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('process_flows');
    }
};
