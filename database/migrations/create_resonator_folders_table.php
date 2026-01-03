<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('resonator_folders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('icon')->nullable();
            $table->string('color')->nullable();
            $table->boolean('is_system')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });

        // Seed default system folders
        $folders = [
            ['name' => 'Inbox', 'slug' => 'inbox', 'icon' => 'heroicon-o-inbox', 'is_system' => true, 'sort_order' => 1],
            ['name' => 'Sent', 'slug' => 'sent', 'icon' => 'heroicon-o-paper-airplane', 'is_system' => true, 'sort_order' => 2],
            ['name' => 'Archive', 'slug' => 'archive', 'icon' => 'heroicon-o-archive-box', 'is_system' => true, 'sort_order' => 3],
            ['name' => 'Spam', 'slug' => 'spam', 'icon' => 'heroicon-o-shield-exclamation', 'is_system' => true, 'sort_order' => 4],
            ['name' => 'Trash', 'slug' => 'trash', 'icon' => 'heroicon-o-trash', 'is_system' => true, 'sort_order' => 5],
        ];

        foreach ($folders as $folder) {
            DB::table('resonator_folders')->insert(array_merge($folder, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('resonator_folders');
    }
};
