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
        Schema::create('asset_audits', function (Blueprint $table) {
             $table->id();
            $table->foreignId('asset_id')->constrained()->cascadeOnDelete();
            $table->date('audit_date');
            $table->foreignId('auditor_id')->nullable()->constrained('employees')->nullOnDelete();
            $table->enum('condition', ['good','damaged','missing'])->default('good');
            $table->text('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asset_audits');
    }
};
