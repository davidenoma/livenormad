<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up(): void
    {
        if (! Schema::hasColumn('teams', 'phone')) {
            Schema::table('teams', function (Blueprint $table) {
                $table->longText('content')->nullable();
                $table->string('phone', 15)->nullable();
                $table->string('email', 255)->nullable();
                $table->string('address', 255)->nullable();
                $table->string('website', 255)->nullable();
            });
        }
    }

    public function down(): void
    {
        Schema::table('teams', function (Blueprint $table) {;
            $table->dropColumn('website');
            $table->dropColumn('address');
            $table->dropColumn('email');
            $table->dropColumn('phone');
            $table->dropColumn('content');
        });
    }
};
