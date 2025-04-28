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

        $this->admin = User::where('status', 1)->first();
        
        if (!$this->admin) {
            $this->markTestSkipped('Tidak ada user admin di database.');
        }

        $this->originalOpds = Opd::all()->toArray();

        $this->actingAs($this->admin);
    }

    public function test_admin_can_view_opd_list()
    {
        $response = $this->get('/manage/opd');
        
        $response->assertStatus(200);
        $response->assertViewIs('admin.opd.index');
    }

    public function test_admin_can_create_opd()
    {
        $opdData = [
            'name' => 'OPD Test ' . time()
        ];

        $response = $this->postJson('/api/opd', $opdData);

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'OPD berhasil ditambahkan'
                ]);

        Opd::where('name', $opdData['name'])->delete();
    }

    public function test_admin_can_update_opd()
    {
        $opd = Opd::create([
            'name' => 'OPD Test Update ' . time()
        ]);

        $updatedData = [
            'name' => 'OPD Updated ' . time()
        ];

        try {
            $response = $this->putJson("/api/opd/{$opd->id}", $updatedData);

            $response->assertStatus(200)
                    ->assertJson([
                        'success' => true,
                        'message' => 'OPD berhasil diperbarui'
                    ]);
        } finally {
            $opd->delete();
        }
    }

    public function test_admin_can_delete_opd()
    {
        $opd = Opd::create([
            'name' => 'OPD Test Delete ' . time()
        ]);

        $response = $this->deleteJson("/api/opd/{$opd->id}");

        $response->assertStatus(200)
                ->assertJson([
                    'success' => true,
                    'message' => 'OPD berhasil dihapus'
                ]);
    }

    public function test_admin_cannot_create_duplicate_opd()
    {
        $opdName = 'OPD Test Duplicate ' . time();
        $opd = Opd::create([
            'name' => $opdName
        ]);

        try {
            $response = $this->postJson('/api/opd', [
                'name' => $opdName
            ]);

            $response->assertStatus(422)
                    ->assertJsonValidationErrors(['name']);
        } finally {
            $opd->delete();
        }
    }

    protected function tearDown(): void
    {
        $originalIds = array_column($this->originalOpds, 'id');
        Opd::whereNotIn('id', $originalIds)->delete();

        parent::tearDown();
    }
} 