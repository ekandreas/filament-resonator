<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resonator_snippets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('shortcut')->unique()->nullable();
            $table->string('subject')->nullable();
            $table->longText('body');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resonator_snippets');
    }
};
