<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wob', function (Blueprint $table) {
            $table->uuid('id')->default('uuid_generate_v4()')->primary();
            $table->text('text');
            $table->text('source_url')->nullable()->unique('idx_wob_source_url');
            $table->timestampTz('date')->nullable();
            $table->timestampTz('created_at')->default(DB::raw("now()"));
            $table->timestampTz('updated_at')->default(DB::raw("now()"));
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wob');
    }
};
