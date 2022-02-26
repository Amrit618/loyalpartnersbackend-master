<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertyItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('property_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('property_list_id');
            $table->foreign('property_list_id')->references('id')->on('property_lists');
            $table->string('name');
            $table->boolean('clean')->default(false);
            $table->boolean('unclean')->default(false);
            $table->boolean('work_needed')->default(false);
            $table->mediumText('description')->nullable() ;
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('property_items');
    }
}
