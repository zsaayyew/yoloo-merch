<?php

namespace Tests\Feature\Admin;

use App\Models\Media;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaLibraryTest extends TestCase
{
    use RefreshDatabase;

    public function test_media_can_be_uploaded_updated_and_deleted(): void
    {
        Storage::fake('public');

        $user = $this->makeUser();

        $this->actingAs($user)
            ->post(route('admin.media.store'), [
                'files' => [
                    UploadedFile::fake()->image('brosur.jpg', 800, 600)->size(300),
                    UploadedFile::fake()->create('katalog.pdf', 120, 'application/pdf'),
                ],
            ])
            ->assertRedirect(route('admin.media.index'))
            ->assertSessionHas('status');

        $this->assertSame(2, Media::query()->count());

        $media = Media::query()->where('original_name', 'brosur.jpg')->firstOrFail();
        $this->assertTrue($media->isImage());
        $this->assertSame($user->id, $media->uploaded_by);
        Storage::disk('public')->assertExists($media->path);

        $this->actingAs($user)
            ->patch(route('admin.media.update', $media), ['alt_text' => 'Brosur YOLOO Merch'])
            ->assertRedirect(route('admin.media.index'));

        $this->assertSame('Brosur YOLOO Merch', $media->fresh()->alt_text);

        $this->actingAs($user)
            ->delete(route('admin.media.destroy', $media))
            ->assertRedirect(route('admin.media.index'));

        $this->assertDatabaseMissing('media', ['id' => $media->id]);
        Storage::disk('public')->assertMissing($media->path);
    }

    public function test_media_rejects_unsupported_file_types(): void
    {
        Storage::fake('public');

        $this->actingAs($this->makeUser())
            ->post(route('admin.media.store'), [
                'files' => [UploadedFile::fake()->create('skrip.exe', 10)],
            ])
            ->assertSessionHasErrors('files.0');

        $this->assertSame(0, Media::query()->count());
    }

    private function makeUser(): User
    {
        $role = Role::firstOrCreate(['name' => Role::ADMIN], ['label' => 'Admin']);

        return User::factory()->create([
            'role_id' => $role->id,
            'is_active' => true,
        ]);
    }
}
