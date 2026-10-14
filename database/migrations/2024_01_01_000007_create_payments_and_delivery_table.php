<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('tontine_id')->nullable()->constrained()->nullOnDelete();
            $table->decimal('amount', 12, 2);
            $table->enum('method', ['mtn_money', 'orange_money', 'blue_money']);
            $table->enum('status', ['pending', 'confirmed', 'failed', 'suspicious'])->default('pending');
            $table->string('phone')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        Schema::create('receipts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payment_id')->constrained()->cascadeOnDelete();
            $table->string('receipt_number')->unique();
            $table->json('details');
            $table->timestamps();
        });

        Schema::create('delivery_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email');
            $table->string('phone');
            $table->string('quartier');
            $table->string('ville');
            $table->string('emergency_contact');
            $table->string('cv_path')->nullable();
            $table->string('photo_path')->nullable();
            $table->string('id_card_path')->nullable();
            $table->text('cover_letter')->nullable();
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->foreignId('reviewed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('livreur_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('livreur_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->decimal('base_amount', 12, 2);
            $table->decimal('punctuality_bonus', 12, 2)->default(0);
            $table->decimal('satisfaction_bonus', 12, 2)->default(0);
            $table->decimal('total_commission', 12, 2);
            $table->integer('client_rating')->nullable();
            $table->boolean('was_punctual')->default(false);
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->morphs('notifiable');
            $table->text('data');
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('livreur_commissions');
        Schema::dropIfExists('delivery_applications');
        Schema::dropIfExists('receipts');
        Schema::dropIfExists('payments');
    }
};
