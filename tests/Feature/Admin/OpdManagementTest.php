<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use App\Models\Opd;
use Tests\TestCase;

class OpdManagementTest extends TestCase
{
    private $admin;
    private $originalOpds = [];

    protected function setUp(): void
    {
        parent::setUp();

        // Gunakan admin yang sudah ada
        $this->admin = User::where('status', 1)->first();
        
        if (!$this->admin) {
            $this->markTestSkipped('Tidak ada user admin di database.');
        }

        // Simpan data OPD yang ada sebelum test
        $this->originalOpds = Opd::all()->toArray();

        $this->actingAs($this->admin);
    }

    public function test_admin_can_view_opd_list()
    {
        // Akses halaman daftar OPD
        $response = $this->get('/manage/opd');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.opd.index');
    }

    public function test_admin_can_create_opd()
    {
        $opdData = [
            'name' => 'OPD Test ' . time() // Tambah timestamp untuk menghindari duplikat
        ];

        // Test create OPD melalui API
        $response = $this->postJson('/api/opd', $opdData);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'OPD berhasil ditambahkan'
                ]);

        // Hapus OPD yang baru dibuat
        Opd::where('name', $opdData['name'])->delete();
    }

    public function test_admin_can_update_opd()
    {
        // Buat OPD sementara untuk testing
        $opd = Opd::create([
            'name' => 'OPD Test Update ' . time()
        ]);

        $updatedData = [
            'name' => 'OPD Updated ' . time()
        ];

        try {
            // Test update OPD melalui API
            $response = $this->putJson("/api/opd/{$opd->id}", $updatedData);

            $response->assertStatus(200)
                    ->assertJson([
                        'success' => true,
                        'message' => 'OPD berhasil diperbarui'
                    ]);
        } finally {
            // Hapus OPD test
            $opd->delete();
        }
    }

    public function test_admin_can_delete_opd()
    {
        // Buat OPD sementara untuk testing
        $opd = Opd::create([
            'name' => 'OPD Test Delete ' . time()
        ]);

        // Test delete OPD melalui API
        $response = $this->deleteJson("/api/opd/{$opd->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'OPD berhasil dihapus'
                ]);
    }

    public function test_admin_cannot_create_duplicate_opd()
    {
        // Buat OPD sementara untuk testing
        $opdName = 'OPD Test Duplicate ' . time();
        $opd = Opd::create([
            'name' => $opdName
        ]);

        try {
            // Coba buat OPD dengan nama yang sama
            $response = $this->postJson('/api/opd', [
                'name' => $opdName
            ]);

            $response->assertStatus(422)
                    ->assertJsonValidationErrors(['name']);
        } finally {
            // Hapus OPD test
            $opd->delete();
        }
    }

    protected function tearDown(): void
    {
        // Hapus semua OPD yang tidak ada di data original
        $originalIds = array_column($this->originalOpds, 'id');
        Opd::whereNotIn('id', $originalIds)->delete();

        parent::tearDown();
    }
} 