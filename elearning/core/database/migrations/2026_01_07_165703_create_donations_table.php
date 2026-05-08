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
        if (!Schema::hasTable('donations')) {
            Schema::create('donations', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable();
                $table->decimal('amount', 28, 8)->default(0);
                $table->string('full_name')->nullable();
                $table->string('email')->nullable();
                $table->string('mobile')->nullable();
                $table->text('message')->nullable();
                $table->tinyInteger('payment_status')->default(0)->comment('0: Pending, 1: Success, 2: Rejected');
                $table->string('trx')->nullable();
                $table->timestamps();
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
        Schema::dropIfExists('donations');
    }
};
