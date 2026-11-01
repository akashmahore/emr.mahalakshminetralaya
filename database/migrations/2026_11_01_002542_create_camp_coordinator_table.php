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
        Schema::create('camp_coordinator', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->text('address')->nullable();
            $table->string('img_url')->nullable();
            $table->string('qualification')->nullable();
            $table->string('registration_number')->nullable()->unique();
            $table->string('specialization')->nullable();
            $table->integer('experience_years')->default(0);
            $table->decimal('consultation_fee', 10, 2)->nullable();
            $table->string('x')->nullable();
            $table->string('y')->nullable();
            $table->string('z')->nullable();
            $table->string('ion_user_id')->nullable();
            $table->timestamps();

            $table->index(['registration_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('camp_coordinator');
    }
};
