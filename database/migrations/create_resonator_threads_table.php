<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resonator_threads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('folder_id')->constrained('resonator_folders')->cascadeOnDelete();
            $table->string('subject');
            $table->string('participant_email')->index();
            $table->string('participant_name')->nullable();
            $table->boolean('is_starred')->default(false);
            $table->boolean('is_read')->default(false);
            $table->timestamp('last_message_at')->nullable();
            $table->foreignId('handled_by')->nullable()->constrained(config('resonator.user_model', 'users'))->nullOnDelete();
            $table->timestamp('handled_at')->nullable();
            $table->timestamps();

            $table->index(['folder_id', 'last_message_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resonator_threads');
    }
};
