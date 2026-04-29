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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable()->index();
            $table->enum('type', ['title', 'item'])->default('item')
                ->comment('title = section header, item = menu link');
            $table->string('title');
            $table->string('icon')->nullable()
                ->comment('Remix Icon class, e.g. ri-dashboard-2-line');
            $table->string('route')->nullable()
                ->comment('Laravel route name');
            $table->string('url')->nullable()
                ->comment('Custom URL (used when route is empty)');
            $table->string('permission')->nullable()
                ->comment('Spatie permission name');
            $table->string('badge_text')->nullable();
            $table->string('badge_class')->nullable();
            $table->integer('sort_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('open_new_tab')->default(false);
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')->on('menu_items')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
