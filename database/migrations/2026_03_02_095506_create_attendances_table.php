<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("attendances", function (Blueprint $table) {
            $table->id();
            $table->foreignId("worker_id")->constrained()->cascadeOnDelete();
            $table->foreignId("project_id")->nullable()->constrained()->nullOnDelete();
            $table->date("date");
            $table->string("hours");
            $table->timestamps();
            
            $table->unique(["worker_id", "project_id", "date"]);
        });
    }
    public function down(): void {
        Schema::dropIfExists("attendances");
    }
};