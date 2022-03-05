<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->string('code');
            //personal information
            $table->string('photo')->nullable();
            $table->string('firstName')->nullable();
            $table->string('middleName')->nullable();
            $table->string('lastName')->nullable();
            $table->string('phoneNumberMobile')->nullable();
            $table->string('phoneNumberWork')->nullable();
            $table->string('email')->nullable();
            $table->json('physicalAddress')->nullable();

            //Workplace information
            $table->string('position')->nullable();
            $table->json('workAddress')->nullable();
            $table->string('contractDuration')->nullable();
            $table->integer('payDay')->nullable();
            $table->double('gross')->nullable();
            $table->double('net')->nullable();
            $table->string('contract')->nullable();
            $table->string('paySlip')->nullable();
//            $table->string('referenceLetter')->nullable();
//            $table->json('co_workers')->nullable();


            //bank information
//            $table->string('bank_statement')->nullable();
//            $table->string('bank_name')->nullable();
//            $table->string('bank_service_center')->nullable();
//            $table->string('bank_account_name')->nullable();
//            $table->integer('bank_account_number')->nullable();

            //identifications
            $table->string('nationalId')->nullable();
            $table->string('nationalIdFile')->nullable();
//            $table->string('passport_id')->nullable();
//            $table->string('licence_id')->nullable();
//            $table->string('other_id')->nullable();

//            $table->string('consent')->nullable();
            $table->string('progress')->nullable();
            $table->string('score')->nullable();
            // $table->integer('subscription')->nullable();
            $table->text('termsAndConditions')->nullable();
            $table->text('termsAndConditionsGuarantor')->nullable();
            $table->double('amount');
            $table->double('approvedDate')->nullable();
            $table->double('dueDate')->nullable();
            $table->double('appliedDate')->nullable();
            $table->double('guarantorDate')->nullable();
            $table->double('closedDate')->nullable();

            $table->integer('guarantor_id')->nullable();
            $table->integer('user_id');
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
        Schema::dropIfExists('loans');
    }
}
