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
        Schema::table('users', function (Blueprint $table) {
            $table->string('username')->nullable()->after('name');
            $table->date('date_of_birth')->nullable()->after('email');
            $table->string('present_address')->nullable()->after('date_of_birth');
            $table->string('permanent_address')->nullable()->after('present_address');
            $table->string('city')->nullable()->after('permanent_address');
            $table->string('postal_code', 20)->nullable()->after('city');
            $table->string('country')->nullable()->after('postal_code');
            $table->string('avatar')->nullable()->after('country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'username',
                'date_of_birth',
                'present_address',
                'permanent_address',
                'city',
                'postal_code',
                'country',
                'avatar',
            ]);
        });
    }
};
