<?php

use App\Models\Rifa;
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
        Schema::table('rifas', function (Blueprint $table) {
            $table->timestamp('published_at')->after('expired_at');
        });

        foreach (Rifa::lazy() as $rifa) {
            $rifa->published_at = $rifa->created_at;
            $rifa->saveQuietly();
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rifas', function (Blueprint $table) {
            $table->dropColumn('published_at');
        });
    }
};
