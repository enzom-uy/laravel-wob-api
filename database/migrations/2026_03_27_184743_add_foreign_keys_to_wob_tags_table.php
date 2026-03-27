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
        Schema::table('wob_tags', function (Blueprint $table) {
            $table->foreign(['tag_id'], 'wob_tags_tag_id_fkey')->references(['id'])->on('tags')->onUpdate('no action')->onDelete('cascade');
            $table->foreign(['wob_id'], 'wob_tags_wob_id_fkey')->references(['id'])->on('wob')->onUpdate('no action')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wob_tags', function (Blueprint $table) {
            $table->dropForeign('wob_tags_tag_id_fkey');
            $table->dropForeign('wob_tags_wob_id_fkey');
        });
    }
};
