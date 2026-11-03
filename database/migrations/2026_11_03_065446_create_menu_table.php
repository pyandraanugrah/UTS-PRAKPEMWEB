<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
{
    Schema::create('menu', function (Blueprint $table) {
        $table->id();

        $table->char('kode_menu', 10)->unique();

        $table->string('nama_menu', 100);

        $table->string('barcode', 30)->unique();

        $table->unsignedInteger('harga');

        $table->unsignedBigInteger('kategori_menu_id');

        $table->timestamps();

        $table->foreign('kategori_menu_id')
            ->references('id')
            ->on('kategori_menu')
            ->cascadeOnUpdate()
            ->restrictOnDelete();
    });

    DB::statement(
        'ALTER TABLE menu ADD CONSTRAINT chk_menu_harga
        CHECK (harga BETWEEN 1000 AND 100000)'
    );
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
{
    Schema::dropIfExists('menu');
}
};
