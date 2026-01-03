<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resonator_spam_filters', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->text('reason')->nullable();
            $table->foreignId('created_by')->nullable()->constrained(config('resonator.user_model', 'users'))->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resonator_spam_filters');
    }
};
