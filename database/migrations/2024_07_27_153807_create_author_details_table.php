<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('author_details', function (Blueprint $table) {
            // $table->id();
            $table->foreignId('author_id')->constrained();
            $table->string('email', 100)->nullable()->unique();
            $table->string('address')->nullable();
            $table->timestamps();

            $table->primary('author_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('author_details');
    }
};
