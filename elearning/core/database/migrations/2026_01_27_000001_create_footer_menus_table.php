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
        if (!Schema::hasTable('footer_menus')) {
            Schema::create('footer_menus', function (Blueprint $table) {
                $table->id();
                $table->string('section_name')->nullable()->comment('e.g. Company, Support');
                $table->string('name');
                $table->string('url')->nullable();
                $table->integer('order')->default(0);
                $table->boolean('status')->default(1);
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
        Schema::dropIfExists('footer_menus');
    }
};
