@props(['colspan' => 1, 'message' => 'Belum ada data.'])
<tr>
    <td colspan="{{ $colspan }}" class="text-center text-muted py-5">
        <i class="bi bi-inbox fs-2 d-block mb-2"></i>{{ $message }}
    </td>
</tr>
