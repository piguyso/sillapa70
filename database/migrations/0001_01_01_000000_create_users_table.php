<?php

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
        if (! Schema::hasTable('users')) {
            Schema::create('users', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name', 191)->default('admin');
                $table->string('lastname', 191)->nullable()->default('spc');
                $table->string('phonenumber', 191)->nullable()->default('0822822283');
                $table->integer('position')->nullable()->default(0);
                $table->integer('branch')->nullable()->default(0);
                $table->integer('schoolgroup')->nullable()->default(0);
                $table->string('email', 191)->default('admin@abts.ac.th')->unique();
                $table->string('password', 191)->default('123456');
                $table->rememberToken();
                $table->timestamps();
                $table->integer('statu')->nullable();
                $table->integer('status')->nullable()->default(0);
                $table->integer('userstatus')->nullable()->default(1);
                $table->integer('school_id')->nullable();
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                if (! Schema::hasColumn('users', 'lastname')) {
                    $table->string('lastname', 191)->nullable()->default('spc')->after('name');
                }
                if (! Schema::hasColumn('users', 'phonenumber')) {
                    $table->string('phonenumber', 191)->nullable()->default('0822822283');
                }
                if (! Schema::hasColumn('users', 'position')) {
                    $table->integer('position')->nullable()->default(0);
                }
                if (! Schema::hasColumn('users', 'branch')) {
                    $table->integer('branch')->nullable()->default(0);
                }
                if (! Schema::hasColumn('users', 'schoolgroup')) {
                    $table->integer('schoolgroup')->nullable()->default(0);
                }
                if (! Schema::hasColumn('users', 'statu')) {
                    $table->integer('statu')->nullable();
                }
                if (! Schema::hasColumn('users', 'status')) {
                    $table->integer('status')->nullable()->default(0);
                }
                if (! Schema::hasColumn('users', 'userstatus')) {
                    $table->integer('userstatus')->nullable()->default(1);
                }
                if (! Schema::hasColumn('users', 'school_id')) {
                    $table->integer('school_id')->nullable();
                }
            });
        }

        if (! Schema::hasTable('password_reset_tokens')) {
            Schema::create('password_reset_tokens', function (Blueprint $table) {
                $table->string('email', 191)->primary();
                $table->string('token');
                $table->timestamp('created_at')->nullable();
            });
        }

        if (! Schema::hasTable('sessions')) {
            Schema::create('sessions', function (Blueprint $table) {
                $table->string('id', 191)->primary();
                $table->foreignId('user_id')->nullable()->index();
                $table->string('ip_address', 45)->nullable();
                $table->text('user_agent')->nullable();
                $table->longText('payload');
                $table->integer('last_activity')->index();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Do not drop tables containing user data in production
    }
};
