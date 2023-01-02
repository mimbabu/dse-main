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
    Schema::create('latest_share_prices', function (Blueprint $table) {
      $table->id();
      $table->foreignId('company_id')->nullable()->constrained()->onDelete('cascade');
      $table->double('ltp', 8, 2);
      $table->double('high', 8, 2);
      $table->double('low', 8, 2);
      $table->double('close_price', 8, 2);
      $table->double('ycp', 8, 2);
      $table->double('change', 8, 2);
      $table->bigInteger('trade');
      $table->double('value_mn', 8, 4);
      $table->bigInteger('volume');
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
    Schema::dropIfExists('latest_share_prices');
  }
};
