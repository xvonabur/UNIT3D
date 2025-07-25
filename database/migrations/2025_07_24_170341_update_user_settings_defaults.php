<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        Schema::table('user_settings', function (Blueprint $table): void {
            $table->unsignedTinyInteger('chat_block_visible')->default(0)->change();
            $table->unsignedTinyInteger('random_media_block_visible')->default(0)->change();
            $table->unsignedTinyInteger('poll_block_visible')->default(0)->change();
            $table->unsignedTinyInteger('top_users_block_visible')->default(0)->change();
            $table->unsignedTinyInteger('latest_topics_block_visible')->default(0)->change();
            $table->unsignedTinyInteger('latest_comments_block_visible')->default(0)->change();
            $table->unsignedTinyInteger('online_block_visible')->default(0)->change();
            $table->unsignedTinyInteger('top_torrents_block_visible')->default(0)->change();
        });
    }

    public function down(): void
    {
        Schema::table('user_settings', function (Blueprint $table): void {
            $table->unsignedTinyInteger('chat_block_visible')->default(1)->change();
            $table->unsignedTinyInteger('random_media_block_visible')->default(1)->change();
            $table->unsignedTinyInteger('poll_block_visible')->default(1)->change();
            $table->unsignedTinyInteger('top_users_block_visible')->default(1)->change();
            $table->unsignedTinyInteger('latest_topics_block_visible')->default(1)->change();
            $table->unsignedTinyInteger('latest_comments_block_visible')->default(1)->change();
            $table->unsignedTinyInteger('online_block_visible')->default(1)->change();
            $table->unsignedTinyInteger('top_torrents_block_visible')->default(1)->change();
        });
    }
};
