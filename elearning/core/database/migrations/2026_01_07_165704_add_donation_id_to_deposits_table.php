<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('deposits') && !Schema::hasColumn('deposits', 'donation_id')) {
            Schema::table('deposits', function (Blueprint $table) {
                $table->unsignedBigInteger('donation_id')->nullable()->after('course_purchased_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('deposits') && Schema::hasColumn('deposits', 'donation_id')) {
            Schema::table('deposits', function (Blueprint $table) {
                $table->dropColumn('donation_id');
            });
        }
    }
};
