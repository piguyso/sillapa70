<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Create base application tables if they do not exist.
     * Safe for both fresh installations and existing databases.
     */
    public function up(): void
    {
        if (! Schema::hasTable('years')) {
            Schema::create('years', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
                $table->string('years', 191);
                $table->tinyInteger('active')->default(0);
            });
        }

        if (! Schema::hasTable('projects')) {
            Schema::create('projects', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
                $table->integer('projectId');
                $table->string('projectName', 191);
                $table->string('projectBudget', 191);
                $table->string('projectYear', 191);
                $table->string('projectTerm', 191);
                $table->string('Workgroups', 191);
                $table->string('whois', 191);
            });
        }

        if (! Schema::hasTable('branches')) {
            Schema::create('branches', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedBigInteger('event_id')->nullable()->index();
                $table->string('year', 4)->default('2569')->index();
                $table->timestamps();
                $table->string('branch_name', 191);
                $table->string('branch_detail', 191);
            });
        }

        if (! Schema::hasTable('schoolgroups')) {
            Schema::create('schoolgroups', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
                $table->string('schoolgroup_name', 191);
                $table->text('schoolgroup_detail');
            });
        }

        if (! Schema::hasTable('schools')) {
            Schema::create('schools', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
                $table->string('school_name', 191);
                $table->text('school_detail')->nullable();
                $table->integer('school_group')->nullable();
            });
        }

        if (! Schema::hasTable('subjectgroups')) {
            Schema::create('subjectgroups', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedBigInteger('event_id')->nullable()->index();
                $table->string('year', 4)->default('2569')->index();
                $table->timestamps();
                $table->string('subjectgroup_name', 191);
                $table->string('subjectgroup_detail', 191)->nullable();
                $table->integer('in_dex')->nullable()->default(0);
            });
        }

        if (! Schema::hasTable('classes')) {
            Schema::create('classes', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
                $table->string('class_name', 191)->nullable();
                $table->string('class_detail', 191)->nullable();
            });
        }

        if (! Schema::hasTable('activities')) {
            Schema::create('activities', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedBigInteger('event_id')->nullable()->index();
                $table->string('year', 4)->default('2569')->index();
                $table->integer('in_dex')->nullable()->default(0);
                $table->timestamps();
                $table->string('activity_name', 191);
                $table->integer('activity_subject');
                $table->integer('activity_class')->nullable();
                $table->integer('activity_studennumber')->nullable();
                $table->integer('activity_teachernumber')->nullable();
                $table->integer('activity_teamnumber')->nullable();
                $table->integer('activity_schoolteamnumber')->nullable();
                $table->integer('activity_score')->nullable();
                $table->integer('activity_branch')->nullable();
                $table->string('activity_branchroom', 191)->nullable();
                $table->date('activity_day')->nullable();
                $table->string('activity_time', 191)->nullable();
                $table->string('activity_timestart', 191)->nullable();
                $table->integer('activity_status')->nullable()->default(0);
            });
        }

        if (! Schema::hasTable('activities_areas')) {
            Schema::create('activities_areas', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedBigInteger('event_id')->nullable()->index();
                $table->string('year', 4)->default('2569')->index();
                $table->integer('in_dex')->nullable()->default(0);
                $table->timestamps();
                $table->string('activity_name', 191);
                $table->integer('activity_subject');
                $table->integer('activity_class')->nullable();
                $table->integer('activity_studennumber')->nullable();
                $table->integer('activity_teachernumber')->nullable();
                $table->integer('activity_teamnumber')->nullable();
                $table->integer('activity_schoolteamnumber')->nullable();
                $table->integer('activity_score')->nullable();
                $table->integer('activity_branch')->nullable();
                $table->string('activity_branchroom', 191)->nullable();
                $table->date('activity_day')->nullable();
                $table->string('activity_time', 191)->nullable();
                $table->string('activity_timestart', 191)->nullable();
                $table->integer('activity_status')->nullable()->default(0);
            });
        }

        if (! Schema::hasTable('addschools')) {
            Schema::create('addschools', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedBigInteger('event_id')->nullable()->index();
                $table->string('year', 4)->default('2570')->index();
                $table->timestamps();
                $table->integer('school')->nullable();
                $table->integer('school_name');
                $table->integer('school_schoolgroup');
                $table->integer('school_activity');
                $table->float('student_score')->nullable()->default(0);
                $table->integer('activity_rank')->nullable()->default(0);
                $table->integer('activity_status')->default(0);
            });
        }

        if (! Schema::hasTable('addschools_areas')) {
            Schema::create('addschools_areas', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedBigInteger('event_id')->nullable()->index();
                $table->string('year', 4)->default('2570')->index();
                $table->timestamps();
                $table->integer('school')->nullable();
                $table->integer('school_id')->nullable();
                $table->integer('school_name');
                $table->integer('school_schoolgroup');
                $table->integer('school_activity');
                $table->float('student_score')->nullable()->default(0);
                $table->integer('activity_rank')->nullable()->default(0);
                $table->integer('activity_status')->default(0);
            });
        }

        if (! Schema::hasTable('addstudents')) {
            Schema::create('addstudents', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedBigInteger('event_id')->nullable()->index();
                $table->string('year', 4)->default('2570')->index();
                $table->timestamps();
                $table->integer('title')->nullable()->default(0);
                $table->string('name', 191);
                $table->string('lastname', 191);
                $table->integer('activity');
                $table->integer('school');
                $table->integer('type')->nullable()->default(0);
                $table->integer('medel')->nullable()->default(0);
                $table->string('student_image', 254)->nullable();
            });
        }

        if (! Schema::hasTable('addstudents_areas')) {
            Schema::create('addstudents_areas', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedBigInteger('event_id')->nullable()->index();
                $table->string('year', 4)->default('2570')->index();
                $table->timestamps();
                $table->integer('title')->nullable()->default(0);
                $table->string('name', 191);
                $table->string('lastname', 191);
                $table->integer('activity');
                $table->integer('school');
                $table->integer('school_id')->nullable();
                $table->integer('type')->nullable()->default(0);
                $table->integer('medel')->nullable()->default(0);
                $table->integer('schoolgroup')->nullable()->default(0);
                $table->string('student_image', 254)->nullable();
            });
        }

        if (! Schema::hasTable('dayofsillapas')) {
            Schema::create('dayofsillapas', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedBigInteger('event_id')->nullable()->index();
                $table->string('year', 4)->default('2569')->index();
                $table->timestamps();
                $table->date('dayofsillapa');
                $table->integer('schoolgroup')->nullable()->default(0);
            });
        }

        if (! Schema::hasTable('systemactives')) {
            Schema::create('systemactives', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
                $table->string('systemname', 191);
                $table->integer('status');
                $table->string('detail', 254);
                $table->integer('schoolgroup')->nullable()->default(0);
            });
        }

        if (! Schema::hasTable('titles')) {
            Schema::create('titles', function (Blueprint $table) {
                $table->increments('id');
                $table->timestamps();
                $table->integer('title_id');
                $table->string('title', 191);
            });
        }

        if (! Schema::hasTable('schoolranks')) {
            Schema::create('schoolranks', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedBigInteger('event_id')->nullable()->index();
                $table->string('year', 4)->default('2570')->index();
                $table->timestamps();
                $table->integer('school');
                $table->string('school_name', 191);
                $table->string('school_gold', 191);
                $table->string('school_silver', 191);
                $table->string('school_brown', 191);
                $table->integer('school_point');
            });
        }

        if (! Schema::hasTable('jows')) {
            Schema::create('jows', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedBigInteger('event_id')->nullable()->index();
                $table->string('year', 4)->default('2570')->index();
                $table->timestamps();
                $table->integer('jow_title');
                $table->string('jow_name', 191);
                $table->string('jow_lastname', 191);
                $table->string('jow_activity', 191)->nullable()->default('0');
                $table->integer('jow_type')->nullable()->default(0);
                $table->integer('jow_branch')->nullable();
                $table->string('jowclass', 125)->nullable();
            });
        }

        if (! Schema::hasTable('jows_areas')) {
            Schema::create('jows_areas', function (Blueprint $table) {
                $table->increments('id');
                $table->unsignedBigInteger('event_id')->nullable()->index();
                $table->string('year', 4)->default('2570')->index();
                $table->timestamps();
                $table->integer('jow_title');
                $table->string('jow_name', 191);
                $table->string('jow_lastname', 191);
                $table->string('jow_activity', 191)->nullable()->default('0');
                $table->integer('jow_type')->nullable()->default(0);
                $table->integer('jow_branch')->nullable();
                $table->string('jowclass', 125)->nullable();
                $table->integer('jow_area')->nullable();
            });
        }

        if (! Schema::hasTable('configs')) {
            Schema::create('configs', function (Blueprint $table) {
                $table->integer('id', true);
                $table->unsignedBigInteger('event_id')->nullable()->index();
                $table->string('app_name', 244)->nullable();
                $table->text('app_slogan')->nullable();
                $table->string('theme_primary', 7)->default('#059669');
                $table->string('theme_secondary', 7)->default('#0f172a');
                $table->string('app_group', 244)->nullable();
                $table->string('app_area', 244)->nullable();
                $table->string('app_year', 64)->nullable();
                $table->string('app_certificate', 244)->nullable();
                $table->timestamps();
                $table->integer('schoolgroup')->default(0);
                $table->char('app_logo', 254)->nullable();
                $table->text('link_criteria')->nullable();
                $table->text('link_ai')->nullable();
                $table->text('link_survey')->nullable();
                $table->string('survey_qr', 255)->nullable();
            });
        }

        if (! Schema::hasTable('notices')) {
            Schema::create('notices', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('event_id')->nullable()->index();
                $table->string('year', 4)->default('2569')->index();
                $table->timestamps();
                $table->string('notice_text', 255);
                $table->text('notice_detail')->nullable();
                $table->integer('notice_type')->nullable()->default(0);
                $table->text('notice_file')->nullable();
                $table->string('notice_owner', 200)->nullable();
                $table->integer('owner_id')->nullable();
                $table->integer('schoolgroup')->nullable()->default(0);
            });
        }

        if (! Schema::hasTable('pictures')) {
            Schema::create('pictures', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('event_id')->nullable()->index();
                $table->string('year', 4)->default('2569')->index();
                $table->timestamps();
                $table->string('notice_text', 255);
                $table->text('notice_detail')->nullable();
                $table->integer('notice_type')->nullable()->default(0);
                $table->string('notice_file', 254)->nullable();
                $table->string('notice_owner', 200)->nullable();
                $table->integer('owner_id')->nullable();
            });
        }
    }

    public function down(): void
    {
        // Do not drop base tables to prevent accidental data loss
    }
};
