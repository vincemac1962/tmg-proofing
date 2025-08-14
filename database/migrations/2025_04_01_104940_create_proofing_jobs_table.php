<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// previously job table in original code
class CreateProofingJobsTable extends Migration
{
    public function up()
    {
        Schema::create('proofing_jobs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('designer_id')->nullable()->constrained('designers')->onDelete('set null');
            $table->foreignId('proofing_company_id')->nullable()->constrained('proofing_companies')->onDelete('set null');
            $table->string('contract_reference', 20)->nullable();
            $table->string('title', 100)->nullable();
            $table->text('advert_location')->nullable();
            $table->text('description')->nullable();
            $table->string('status', 30)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('proofing_jobs');
    }
}