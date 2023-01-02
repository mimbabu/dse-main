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
        Schema::create('circuit_breaks', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->foreignId('tradeCode')->nullable()->constrained()->onDelete('cascade');
            $table->float('breaker');
            $table->float('tickSize');
            $table->float('openAdjPrice');
            $table->float('floorPrice');
            $table->float('lowerLimit');
            $table->float('upperLimit');
            $table->double('floorPriceBlockMarket');




            // $circuitBreak->data_updated_at_time = $data_updated_at_time;


            // $circuitBreak->tradeCode = $row['tradeCode'];
            // $circuitBreak->breaker =  (float)$row['breaker'];
            // $circuitBreak->tickSize =  (float)$row['tickSize'];
            // $circuitBreak->openAdjPrice = (float)$row['openAdjPrice'];
            // $circuitBreak->floorPrice =  $floorPrice;
            // $circuitBreak->lowerLimit =  (float)$row['lowerLimit'];
            // $circuitBreak->upperLimit =  (float)$row['upperLimit'];
            // $circuitBreak->floorPriceBlockMarket =  (float)$row['floorPriceBlockMarket'];
            // $circuitBreak->data_updated_at_date = $data_updated_at_date;
            // $circuitBreak->data_updated_at_time = $data_updated_at_time;


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('circuit_breaks');
    }
};