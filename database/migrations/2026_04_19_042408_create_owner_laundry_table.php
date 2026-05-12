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
        Schema::create('owner_laundry', function (Blueprint $table) {
            $table->id();
            // $table->string('email')->unique();
            $table->string('nama')->unique();
            $table->string('foto')->nullable();
            $table->string('jenis_kelamin', 1);
            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');
            $table->string('telepon');
            $table->text('alamat');
            $table->date('mulai_kerja')->nullable();
            $table->date('selesai_kerja')->nullable();
            $table->foreignId('user_id')->constrained('users', 'id');
            $table->timestamps();
            $table->rememberToken();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('owner_laundry');
    }
};
