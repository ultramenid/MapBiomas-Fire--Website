<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Empat angka kunci di landing; diisi dengan nilai yang sebelumnya
        // ditulis langsung di Blade.
        Schema::create('highlights', function (Blueprint $table) {
            $table->id();
            $table->decimal('value', 15, 2);
            $table->unsignedTinyInteger('decimals')->default(0);
            $table->string('unitEN')->nullable();
            $table->string('unitID')->nullable();
            $table->string('labelEN');
            $table->string('labelID');
            $table->timestamps();
        });

        DB::table('highlights')->insert([
            ['value' => 9.5, 'decimals' => 1, 'unitEN' => 'Mha', 'unitID' => 'juta ha', 'labelEN' => '2000-2024 burned areas', 'labelID' => 'area terbakar 2000-2024'],
            ['value' => 40, 'decimals' => 0, 'unitEN' => '%', 'unitID' => '%', 'labelEN' => '2000-2024 burned areas are on peat land', 'labelID' => 'area terbakar 2000-2024 berada di lahan gambut'],
            ['value' => 637011, 'decimals' => 0, 'unitEN' => 'ha', 'unitID' => 'ha', 'labelEN' => 'January-August 2026 burned areas', 'labelID' => 'area terbakar Januari-Agustus 2026'],
            ['value' => 27, 'decimals' => 0, 'unitEN' => '%', 'unitID' => '%', 'labelEN' => 'January-August 2026 burned areas are in Papua', 'labelID' => 'area terbakar Januari-Agustus 2026 berada di Papua'],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('highlights');
    }
};
