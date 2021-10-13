<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DsProbsCategory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ds_probs_category', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->string('category_name', 200);
            $table->integer('parent_id');
            $table->string('category_image', 200);
            $table->enum('status',['Active','Inactive'])->default('Active');
            $table->enum('is_deleted', ['0', '1'])->default('0');
            $table->integer('created_by');
            $table->integer('updated_by');
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
        Schema::dropIfExists('ds_probs_category');
    }
}


