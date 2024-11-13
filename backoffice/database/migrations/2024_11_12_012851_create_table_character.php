<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * @github.com/AlexandreOsovski
     *
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('characters', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('status');
            $table->string('species');
            $table->string('type')->nullable();
            $table->string('gender');
            $table->foreignId('origin_id')->nullable()->constrained('locations');
            $table->foreignId('location_id')->nullable()->constrained('locations');
            $table->string('image');
            $table->json('episode');
            $table->string('url');
            $table->timestamp('created')->nullable();
            $table->timestamps();
        });
    }
    /**
     * @github.com/AlexandreOsovski
     *
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('characters');
    }
};
