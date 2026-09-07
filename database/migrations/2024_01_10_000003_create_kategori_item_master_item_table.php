<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tabel pivot untuk relasi many to many antara Kategori Items dan Master Items.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kategori_item_master_item', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kategori_item_id')->constrained('kategori_items')->cascadeOnDelete();
            $table->foreignId('master_item_id')->constrained('master_items')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['kategori_item_id', 'master_item_id'], 'kategori_master_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('kategori_item_master_item');
    }
};
