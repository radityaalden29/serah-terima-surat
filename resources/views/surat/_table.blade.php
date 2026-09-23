<div class="table-responsive">

    @php
        if (!function_exists('suratAvatarColor')) {
            function suratAvatarColor($name) {
                $colors = ['#7b4fc7','#2f9e44','#e8590c','#1971c2','#c2255c','#0ca678','#f08c00','#5f3dc4'];
                $hash = 0;
                foreach (str_split((string) $name) as $char) {
                    $hash = ord($char) + (($hash << 5) - $hash);
                }
                return $colors[abs($hash) % count($colors)];
            }
        }

        if (!function_exists('suratInitials')) {
            function suratInitials($name) {
                $words = preg_split('/\s+/', trim((string) $name));
                $initials = mb_strtoupper(mb_substr($words[0] ?? '', 0, 1));
                if (count($words) > 1) {
                    $initials .= mb_strtoupper(mb_substr(end($words), 0, 1));
                }
                return $initials !== '' ? $initials : '?';
            }
        }
    @endphp

    <table class="table table-hover align-middle">
        <thead class="table-light">
            <tr>
                <th>No</th>
                <th>No Agenda</th>
                <th>Jenis</th>
                <th>Pengirim</th>
                <th>Penerima</th>
                <th>Tanggal</th>
                <th>Status</th>
                <th width="130" class="text-center">Aksi</th>
            </tr>
        </thead>

        <tbody>
        @forelse($surats as $surat)
            <tr>
                <td>{{ $surats->firstItem() + $loop->index }}</td>
                <td>{{ $surat->no_agenda }}</td>
                <td>{{ $surat->jenis }}</td>
                <td>
                    <div class="avatar-cell">
                        <span class="avatar-circle" style="background:{{ suratAvatarColor($surat->pengirim) }}">
                            {{ suratInitials($surat->pengirim) }}
                        </span>
                        <span>{{ $surat->pengirim }}</span>
                    </div>
                </td>
                <td>
                    <div class="avatar-cell">
                        <span class="avatar-circle" style="background:{{ suratAvatarColor($surat->penerima) }}">
                            {{ suratInitials($surat->penerima) }}
                        </span>
                        <span>{{ $surat->penerima }}</span>
                    </div>
                </td>
                <td>{{ $surat->tanggal }}</td>
                <td>
                    @if($surat->status == 'Selesai')
                        <span class="status-pill status-pill--selesai">
                            <i class="bi bi-check2-all"></i> Selesai
                        </span>
                    @else
                        <span class="status-pill status-pill--diterima">
                            <i class="bi bi-check-circle-fill"></i> Diterima
                        </span>
                    @endif
                </td>
                <td class="text-center">
                    <div class="action-group">
                        <a href="{{ route('surat.show',$surat->id) }}" class="btn-action view" title="Lihat">
                            <i class="bi bi-eye"></i>
                        </a>
                        <a href="{{ route('surat.edit',$surat->id) }}" class="btn-action edit" title="Edit">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <form action="{{ route('surat.destroy',$surat->id) }}" method="POST" class="delete-form">
                            @csrf
                            @method('DELETE')
                            <button class="btn-action delete" title="Hapus">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" class="p-0">
                    <div class="empty-state">
                        <div class="empty-state-icon"><i class="bi bi-inbox"></i></div>
                        <h5 class="empty-state-title">Belum Ada Data Surat</h5>
                        <p class="empty-state-text">
                            @if(request('search') || request('status') || request('tanggal_awal'))
                                Tidak ada surat yang cocok dengan filter yang dipilih.
                                <br>Coba ubah kata kunci atau reset filter.
                            @else
                                Surat yang ditambahkan akan muncul di sini.
                            @endif
                        </p>
                        @if(request('search') || request('status') || request('tanggal_awal'))
                            <a href="{{ route('surat.index') }}" class="btn btn-ghost mt-2 mb-3">
                                <i class="bi bi-arrow-clockwise me-1"></i> Reset Filter
                            </a>
                        @else
                            <a href="{{ route('surat.create') }}" class="btn-tambah-sm">
                                <i class="bi bi-plus-circle"></i> Tambah Surat Sekarang
                            </a>
                        @endif
                    </div>
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>

<div class="d-flex flex-wrap justify-content-between align-items-center mt-3 gap-2">

    @if($surats->total() > 0)
        <span class="table-info-text">
            Menampilkan {{ $surats->firstItem() }}–{{ $surats->lastItem() }} dari {{ $surats->total() }} surat
        </span>
    @else
        <span class="table-info-text">Tidak ada data.</span>
    @endif

    {{ $surats->links('pagination::bootstrap-5') }}

</div>
