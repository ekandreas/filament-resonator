<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resonator_contacts', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('unsubscribe_token', 64)->unique()->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->timestamps();
        });

        // Pivot table for thread-contact relationship
        Schema::create('resonator_thread_contact', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_id')->constrained('resonator_threads')->cascadeOnDelete();
            $table->foreignId('contact_id')->constrained('resonator_contacts')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['thread_id', 'contact_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resonator_thread_contact');
        Schema::dropIfExists('resonator_contacts');
    }
};
