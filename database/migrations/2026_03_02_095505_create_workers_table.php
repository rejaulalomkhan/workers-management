<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("workers", function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->string("worker_id_number")->unique()->nullable();
            $table->string("trade");
            $table->decimal("internal_pay_rate", 8, 2)->default(0);
            $table->boolean("is_active")->default(true);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists("workers");
    }
};