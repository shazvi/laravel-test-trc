<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\Helper;
use Tests\TestCase;
use Illuminate\Support\Facades\Artisan;

class ResourceTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp(): void
    {
        parent::setUp();
        Artisan::call( 'db:seed' );
    }

    public function test_get_all_resources_succeeds()
    {
        $response = $this->getJson('/api/resources');
        $response->assertStatus(200);
    }

    public function test_get_resource_by_id_succeeds()
    {
        $response = $this->getJson('/api/resources/1000');
        $response->assertStatus(200);
        $response->assertJsonStructure(["id", "type", "title", "created_at", "updated_at", "description", "html", "link", "open_new_tab", "filename" ]);
    }

    public function test_get_resource_by_id_fails_with_invalid_id()
    {
        $response = $this->getJson('/api/resources/0');
        $response->assertStatus(404);
        $response->assertExactJson(['error' => "Resource not found"]);
    }

    public function test_create_resource_succeeds()
    {
        $response = $this->withHeaders(Helper::FORM_HEADERS)->post('/api/resources', Helper::TEST_FORM_DATA);
        $response->assertStatus(200);
        $response->assertExactJson(['success' => "Resource saved"]);
    }

    public function test_create_resource_with_file_upload_succeeds()
    {
        $file = UploadedFile::fake()->create('testfile.pdf', 100, 'application/pdf');
        $response = $this->withHeaders(Helper::FORM_HEADERS)->post('/api/resources', [
            'title' => "test title",
            'type' => 3,
            'filename' => 'testfile.pdf',
            'file' => $file
        ]);
        $response->assertStatus(200);
        $response->assertExactJson(['success' => "Resource saved"]);
        Storage::assertExists('public/'.$file->hashName());
        Storage::delete('public/'.$file->hashName());
    }

    public function test_create_resource_fails_with_invalid_type_id()
    {
        $response = $this->withHeaders(Helper::FORM_HEADERS)->post('/api/resources', [
            'title' => "test title",
            'type' => 0,
        ]);
        $response->assertStatus(500);
    }

    public function test_create_resource_fails_validation()
    {
        $response = $this->withHeaders(Helper::FORM_HEADERS)->postJson('/api/resources', []);
        $response->assertStatus(422);
        $response->assertJsonStructure([
            'message',
            'errors' => []
        ]);
    }

    public function test_update_resource_succeeds()
    {
        $testFormData = Helper::TEST_FORM_DATA;
        $testFormData['_method'] = 'PUT';

        $response = $this->withHeaders(Helper::FORM_HEADERS)->post('/api/resources/1000', $testFormData);
        $response->assertStatus(200);
        $response->assertExactJson(['success' => "Resource saved"]);
    }

    public function test_update_resource_with_file_upload_succeeds()
    {
        $file = UploadedFile::fake()->create('testfile.pdf', 100, 'application/pdf');

        $response = $this->withHeaders(Helper::FORM_HEADERS)->post('/api/resources/1002', [
            'id' => 1002,
            'title' => "test title",
            'type' => 3,
            'filename' => 'testfile.pdf',
            'file' => $file,
            '_method' => 'PUT'
        ]);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'success',
            'filename',
        ]);

        Storage::assertExists('public/'.$file->hashName());
        Storage::delete('public/'.$file->hashName());
    }

    public function test_delete_resource_succeeds()
    {
        $response = $this->deleteJson('/api/resources/1000');
        $response->assertStatus(200);
        $response->assertExactJson(['success' => "Resource deleted"]);
    }
}
