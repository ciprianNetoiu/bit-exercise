<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVariantsGroups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('variants_groups', function (Blueprint $table) {
            $table->id();
            $table->integer('id_variant');
            $table->integer('id_color');
            $table->integer('color_count');
            $table->timestamps();

            $table->foreign('id_variant')
                ->references('id')
                ->on('exercise_variants')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('variants_groups');
    }
}
