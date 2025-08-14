<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProofingCompaniesTableNewMigration extends Migration
{
    public function up()
    {
        Schema::create('proofing_companies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('address', 100)->nullable();
            $table->string('telephone_1', 75);
            $table->string('telephone_2', 75)->nullable();
            $table->string('telephone_3', 75)->nullable();
            $table->string('email_address', 50);
            $table->string('web_url', 250);
            $table->string('email_signatory', 50)->nullable();
            $table->string('signatory_role', 50)->nullable();
            $table->string('company_logo_url', 100)->nullable();
            $table->string('colour_split', 25)->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('proofing_companies');
    }
}