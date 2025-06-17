<?php

use App\Enums\StatusCallLogEnum;
use App\Models\Contact;
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
        Schema::create('call_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Contact::class)->constrained()->cascadeOnDelete()->comment('Foreign key to contacts table');
            $table->integer('duration')->comment('Duration of the call in seconds');
            $table->enum('status', array_column(StatusCallLogEnum::cases(), 'value'))->comment('Status of the call');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('call_logs');
    }
};
