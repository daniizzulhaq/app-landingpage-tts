<?php

namespace Tests\Feature;

use App\Models\Album;
use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use App\Services\ImageService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ContentWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected bool $seed = true;

    private function super(): User
    {
        return User::where('email', 'admin@polrestts.test')->first();
    }

    private function operator(): User
    {
        return User::where('email', 'operator.lantas@polrestts.test')->first();
    }

    private function payload(array $extra = []): array
    {
        return array_merge([
            'judul' => 'Judul uji '.uniqid(),
            'isi' => '<p>Isi uji <script>alert(1)</script><strong>tebal</strong></p>',
            'action' => 'submit',
        ], $extra);
    }

    public function test_admin_unit_content_goes_to_pending_and_is_sanitized(): void
    {
        $this->actingAs($this->operator())
            ->post(route('admin.posts.store', 'berita'), $this->payload(['judul' => 'Berita Operator']))
            ->assertRedirect();

        $post = Post::where('judul', 'Berita Operator')->first();
        $this->assertSame('pending', $post->status);
        $this->assertSame($this->operator()->unit_id, $post->unit_id);
        $this->assertStringNotContainsString('<script', $post->isi);
        $this->assertStringContainsString('<strong>tebal</strong>', $post->isi);
    }

    public function test_admin_unit_cannot_force_other_unit_or_create_announcement(): void
    {
        $op = $this->operator();
        $this->actingAs($op)
            ->post(route('admin.posts.store', 'berita'), $this->payload(['unit_id' => 1]))
            ->assertSessionHasErrors('unit_id');

        $this->actingAs($op)->get(route('admin.posts.create', 'pengumuman'))->assertForbidden();
        $this->actingAs($op)->post(route('admin.posts.store', 'pengumuman'), $this->payload())->assertForbidden();
        $this->actingAs($op)->get(route('admin.posts.index', 'pengumuman'))->assertForbidden();
    }

    public function test_admin_unit_cannot_touch_other_units_content(): void
    {
        $other = Post::where('type', 'berita')->where('unit_id', '!=', $this->operator()->unit_id)->first();
        $this->actingAs($this->operator());
        $this->get(route('admin.posts.edit', ['berita', $other]))->assertForbidden();
        $this->put(route('admin.posts.update', ['berita', $other]), $this->payload())->assertForbidden();
        $this->delete(route('admin.posts.destroy', ['berita', $other]))->assertForbidden();
        $this->get(route('admin.posts.index', 'berita'))->assertOk()->assertDontSee($other->judul);
    }

    public function test_super_admin_approve_and_reject(): void
    {
        $pending = Post::where('status', 'pending')->first();
        $this->actingAs($this->super());
        $this->get(route('admin.approval.index'))->assertOk()->assertSee($pending->judul);

        $this->post(route('admin.approval.posts.reject', $pending), ['catatan_revisi' => 'Perbaiki judul'])->assertRedirect();
        $this->assertSame('rejected', $pending->fresh()->status);
        $this->assertSame('Perbaiki judul', $pending->fresh()->catatan_revisi);

        // Admin Unit memperbaiki lalu kirim ulang
        $this->actingAs($this->operator())
            ->put(route('admin.posts.update', ['berita', $pending]), $this->payload(['judul' => 'Judul diperbaiki']))
            ->assertRedirect();
        $this->assertSame('pending', $pending->fresh()->status);

        $this->actingAs($this->super())->post(route('admin.approval.posts.approve', $pending))->assertRedirect();
        $fresh = $pending->fresh();
        $this->assertSame('published', $fresh->status);
        $this->assertNotNull($fresh->published_at);
        $this->assertNull($fresh->catatan_revisi);
    }

    public function test_admin_unit_cannot_approve(): void
    {
        $pending = Post::where('status', 'pending')->first();
        $this->actingAs($this->operator())->post(route('admin.approval.posts.approve', $pending))->assertForbidden();
    }

    public function test_super_admin_content_publishes_directly(): void
    {
        $this->actingAs($this->super())
            ->post(route('admin.posts.store', 'pengumuman'), $this->payload(['judul' => 'Pengumuman Super', 'is_penting' => 1]))
            ->assertRedirect();
        $post = Post::where('judul', 'Pengumuman Super')->first();
        $this->assertSame('published', $post->status);
        $this->assertTrue($post->is_penting);
    }

    public function test_without_approval_setting_admin_unit_publishes_directly(): void
    {
        Setting::put('require_approval', '0');
        $this->actingAs($this->operator())
            ->post(route('admin.posts.store', 'kegiatan'), $this->payload(['judul' => 'Kegiatan Langsung', 'tanggal_kegiatan' => '2026-01-10']))
            ->assertRedirect();
        $this->assertSame('published', Post::where('judul', 'Kegiatan Langsung')->value('status'));
    }

    public function test_image_upload_is_resized_and_thumbnailed(): void
    {
        Storage::fake('public');
        $this->actingAs($this->super())->post(route('admin.posts.store', 'berita'), $this->payload([
            'judul' => 'Dengan Gambar',
            'thumbnail' => UploadedFile::fake()->image('foto.jpg', 2400, 1600),
            'images' => [UploadedFile::fake()->image('a.png', 800, 600), UploadedFile::fake()->image('b.jpg', 1000, 700)],
            'lampiran' => UploadedFile::fake()->create('poster.pdf', 200, 'application/pdf'),
        ]))->assertRedirect()->assertSessionHasNoErrors();

        $post = Post::where('judul', 'Dengan Gambar')->first();
        Storage::disk('public')->assertExists($post->thumbnail);
        Storage::disk('public')->assertExists(ImageService::thumbPath($post->thumbnail));
        [$w] = getimagesizefromstring(Storage::disk('public')->get($post->thumbnail));
        [$tw] = getimagesizefromstring(Storage::disk('public')->get(ImageService::thumbPath($post->thumbnail)));
        $this->assertSame(1600, $w);
        $this->assertSame(600, $tw);
        $this->assertCount(2, $post->images);
        $this->assertStringEndsWith('.pdf', $post->lampiran);
    }

    public function test_upload_rejects_invalid_file_types(): void
    {
        Storage::fake('public');
        $this->actingAs($this->super())->post(route('admin.posts.store', 'berita'), $this->payload([
            'thumbnail' => UploadedFile::fake()->create('virus.php', 10, 'text/x-php'),
        ]))->assertSessionHasErrors('thumbnail');
    }

    public function test_album_with_multiple_photos(): void
    {
        Storage::fake('public');
        $this->actingAs($this->operator())->post(route('admin.albums.store'), [
            'judul' => 'Album Operator',
            'action' => 'submit',
            'photos' => [UploadedFile::fake()->image('1.jpg', 640, 480), UploadedFile::fake()->image('2.jpg', 640, 480), UploadedFile::fake()->image('3.jpg', 640, 480)],
        ])->assertRedirect();
        $album = Album::where('judul', 'Album Operator')->first();
        $this->assertSame('pending', $album->status);
        $this->assertCount(3, $album->photos);

        $photo = $album->photos->first();
        $this->actingAs($this->operator())->delete(route('admin.albums.photos.destroy', [$album, $photo]))->assertRedirect();
        $this->assertCount(2, $album->fresh()->photos);
    }
}
