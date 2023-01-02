<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('price_earnings', function (Blueprint $table) {
      $table->id();
      $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
      $table->double('close_price', 8, 2);
      $table->double('ycp', 8, 2);
      $table->string('pe_1', 171)->nullable();
      $table->string('pe_2', 171)->nullable();
      $table->string('pe_3', 171)->nullable();
      $table->string('pe_4', 171)->nullable();
      $table->string('pe_5', 171)->nullable();
      $table->string('pe_6', 171)->nullable();
      $table->date('data_updated_at_date');
      $table->time('data_updated_at_time');
      $table->timestamps();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('price_earnings');
  }
};
