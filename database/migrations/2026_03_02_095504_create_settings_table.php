<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create("settings", function (Blueprint $table) {
            $table->id();
            $table->string("company_name")->nullable();
            $table->string("company_name_arabic")->nullable();
            $table->string("trn")->nullable();
            $table->text("address")->nullable();
            $table->string("phone")->nullable();
            $table->string("email")->nullable();
            $table->string("logo_path")->nullable();
            $table->string("header_image_path")->nullable();
            $table->string("currency")->default("AED");
            $table->decimal("vat_rate", 5, 2)->default(5.00);
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists("settings");
    }
};