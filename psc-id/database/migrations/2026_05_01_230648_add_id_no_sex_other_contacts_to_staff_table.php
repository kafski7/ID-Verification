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
        Schema::table('staff', function (Blueprint $table) {
            // Card ID number — the number printed on the card face (distinct from staff_id / employee number)
            $table->string('id_no')->nullable()->unique()->after('staff_id');
            // Sex: M = Male, F = Female
            $table->enum('sex', ['M', 'F'])->nullable()->after('email');
            // Flexible additional contact info (phone, email, or both combined)
            $table->string('other_contacts')->nullable()->after('sex');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('staff', function (Blueprint $table) {
            $table->dropUnique(['id_no']);
            $table->dropColumn(['id_no', 'sex', 'other_contacts']);
        });
    }
};
