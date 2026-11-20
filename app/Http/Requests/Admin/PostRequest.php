<?php

namespace App\Http\Requests\Admin;

use App\Models\Post;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PostRequest extends FormRequest
{
    public function authorize(): bool
    {
        $post = $this->route('post');
        $type = $this->route('type');

        if ($post) {
            return $post->type === $type && $this->user()->can('update', $post);
        }

        return $this->user()->can('createType', [Post::class, $type]);
    }

    public function rules(): array
    {
        $type = $this->route('type');
        $super = $this->user()->isSuperAdmin();

        return [
            'judul' => ['required', 'string', 'max:200'],
            'ringkasan' => ['nullable', 'string', 'max:500'],
            'isi' => ['required', 'string', 'max:200000'],
            'category_id' => ['nullable', 'exists:categories,id'],
            'unit_id' => [$super ? 'nullable' : 'prohibited', 'exists:units,id'],
            'lokasi' => ['nullable', 'string', 'max:200'],
            'tanggal_kegiatan' => [$type === Post::TYPE_KEGIATAN ? 'required' : 'nullable', 'date'],
            'is_penting' => ['nullable', 'boolean'],
            'published_at' => [$super ? 'nullable' : 'prohibited', 'date'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'hapus_thumbnail' => ['nullable', 'boolean'],
            'lampiran' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,pdf', 'max:10240'],
            'hapus_lampiran' => ['nullable', 'boolean'],
            'images' => ['nullable', 'array', 'max:30'],
            'images.*' => ['image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'captions' => ['nullable', 'array'],
            'captions.*' => ['nullable', 'string', 'max:255'],
            'hapus_images' => ['nullable', 'array'],
            'hapus_images.*' => ['integer'],
            'action' => ['required', Rule::in(['draft', 'submit'])],
        ];
    }

    public function attributes(): array
    {
        return [
            'isi' => 'isi konten',
            'images.*' => 'foto galeri',
            'tanggal_kegiatan' => 'tanggal kegiatan',
            'category_id' => 'kategori',
        ];
    }
}
