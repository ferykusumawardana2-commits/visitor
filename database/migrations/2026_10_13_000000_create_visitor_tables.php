<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations to create the PT ABCD Visitor tables merged from visitor.sql.
     */
    public function up(): void
    {
        if (! Schema::hasTable('jabatan')) {
            Schema::create('jabatan', function (Blueprint $table): void {
                $table->increments('id_jabatan');
                $table->string('nama_jabatan', 100);
            });
        }

        if (! Schema::hasTable('penduduk')) {
            Schema::create('penduduk', function (Blueprint $table): void {
                $table->increments('id_penduduk');
                $table->string('nama_penduduk', 100);
                $table->char('nik', 16);
                $table->char('kk', 16);
                $table->enum('jenis_kelamin', ['L', 'P']);
                $table->string('alamat', 255);
                $table->string('username', 255);
                $table->string('password', 255);
                $table->enum('status', ['Admin', 'Penduduk']);
            });
        }

        if (! Schema::hasTable('jabatan_penduduk')) {
            Schema::create('jabatan_penduduk', function (Blueprint $table): void {
                $table->increments('id_jabatan_penduduk');
                $table->unsignedInteger('id_jabatan');
                $table->unsignedInteger('id_penduduk');
                $table->enum('status', ['Y', 'T']);
            });
        }

        if (! Schema::hasTable('pemohon')) {
            Schema::create('pemohon', function (Blueprint $table): void {
                $table->increments('id_pemohon');
                $table->string('nama', 255);
                $table->char('telepon', 15);
                $table->string('email', 255);
                $table->string('password', 255);
            });
        }

        if (! Schema::hasTable('settings_kegiatan')) {
            Schema::create('settings_kegiatan', function (Blueprint $table): void {
                $table->increments('id_settings_kegiatan');
                $table->date('tanggal_awal');
                $table->date('tanggal_akhir');
                $table->unsignedInteger('id_jabatan_penduduk');
                $table->string('kegiatan', 255);
            });
        }

        if (! Schema::hasTable('settings_kunjungan')) {
            Schema::create('settings_kunjungan', function (Blueprint $table): void {
                $table->increments('id_settings_kunjungan');
                $table->unsignedInteger('id_jabatan_penduduk');
                $table->date('tanggal');
                $table->time('jam_aktif');
                $table->time('jam_non_aktif');
                $table->enum('status', ['Y', 'T']);
            });
        }

        if (! Schema::hasTable('kunjungan')) {
            Schema::create('kunjungan', function (Blueprint $table): void {
                $table->increments('id_kunjungan');
                $table->unsignedInteger('id_settings_kunjungan');
                $table->unsignedInteger('id_pemohon')->nullable();
                $table->enum('konfrimasi_admin', ['Y', 'T'])->nullable();
                $table->dateTime('tanggal_waktu_admin')->nullable();
                $table->enum('konfrimasi_penduduk', ['Y', 'T'])->nullable();
                $table->dateTime('tanggal_waktu_penduduk')->nullable();
                $table->string('catatan_admin', 255)->nullable();
                $table->string('catatan_penduduk', 255)->nullable();
                $table->string('tujuannya', 255);
                $table->dateTime('tanggal_waktu');
            });
        } elseif (! Schema::hasColumn('kunjungan', 'id_pemohon')) {
            Schema::table('kunjungan', function (Blueprint $table): void {
                $table->unsignedInteger('id_pemohon')->nullable()->after('id_settings_kunjungan');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kunjungan');
        Schema::dropIfExists('settings_kunjungan');
        Schema::dropIfExists('settings_kegiatan');
        Schema::dropIfExists('pemohon');
        Schema::dropIfExists('jabatan_penduduk');
        Schema::dropIfExists('penduduk');
        Schema::dropIfExists('jabatan');
    }
};
