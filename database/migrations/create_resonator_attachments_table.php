<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resonator_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_id')->constrained('resonator_emails')->cascadeOnDelete();
            $table->string('resend_id')->nullable();
            $table->string('filename');
            $table->string('content_type')->nullable();
            $table->string('content_disposition')->nullable();
            $table->string('content_id')->nullable();
            $table->unsignedBigInteger('size')->nullable();
            $table->string('local_path')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resonator_attachments');
    }
};
