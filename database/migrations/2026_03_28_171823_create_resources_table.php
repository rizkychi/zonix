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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('route_name')->unique()->nullable();
            $table->string('uri')->nullable();
            $table->string('http_method')->default('GET');
            $table->string('controller_class')->nullable();
            $table->string('controller_action')->nullable();
            $table->string('group')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('description')->nullable();
            $table->timestamps();

            $table->index(['route_name', 'is_active']);  // Performance optimization for route lookups
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
