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
      Schema::create('borrowing_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('borrowing_id');
            $table->unsignedBigInteger('tool_id');
            $table->integer('jumlah_pinjam');
            $table->string('kondisi_awal')->nullable();
            $table->string('kondisi_akhir')->nullable();
            $table->timestamps();

            $table->foreign('borrowing_id')->references('id')->on('borrowings')->onDelete('cascade');
            $table->foreign('tool_id')->references('id')->on('tools')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowing_details');
    }
};
