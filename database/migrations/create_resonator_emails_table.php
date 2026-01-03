<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resonator_emails', function (Blueprint $table) {
            $table->id();
            $table->foreignId('thread_id')->constrained('resonator_threads')->cascadeOnDelete();
            $table->string('resend_id')->unique()->nullable();
            $table->string('message_id')->index()->nullable();
            $table->string('in_reply_to')->nullable();
            $table->text('references')->nullable();
            $table->boolean('is_inbound')->default(true);
            $table->string('from_email');
            $table->string('from_name')->nullable();
            $table->json('to');
            $table->json('cc')->nullable();
            $table->json('bcc')->nullable();
            $table->string('reply_to')->nullable();
            $table->string('subject');
            $table->longText('html')->nullable();
            $table->longText('text')->nullable();
            $table->json('headers')->nullable();
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index(['thread_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resonator_emails');
    }
};
