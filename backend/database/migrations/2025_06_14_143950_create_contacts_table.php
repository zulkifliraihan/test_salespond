<?php

use App\Models\Role;
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
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Role::class)->constrained()->cascadeOnDelete()->comment('Foreign key to roles table');
            $table->string('name')->comment('Name of the contact');
            $table->string('phone')->comment('Phone number of the contact');
            $table->string('company')->comment('Company of the contact');
            $table->boolean('is_favorite')->default(false)->comment('Indicates if the contact is a favorite');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
