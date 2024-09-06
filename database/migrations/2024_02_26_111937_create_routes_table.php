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
        Schema::create('routes', function (Blueprint $table) {
            $table->unsignedBigInteger("id")->primary();
            $table->string("name")->comment("this is the route name");
            $table->string("link")->comment("this is the route link");
            $table->text("dynamic_content")->comment("this holds dynamic content like customer_id customer_site_id etc");
            $table->boolean("status")->comment("this indicates if the route is active or not ");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('routes');
    }
};
