
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Menu Kantin</title>
</head>
<body>

    <h1>Sistem Menu Kantin</h1>
    <p>Aplikasi pengelolaan menu makanan dan minuman kantin kampus sederhana.</p>

    <hr>

    {{-- Pesan berhasil --}}
    @if(session('success'))
        <p>{{ session('success') }}</p>
    @endif

    {{-- Pesan error --}}
    @if(session('error'))
        <p>{{ session('error') }}</p>
    @endif

    {{-- Validasi --}}
    @if($errors->any())
        <div>
            <strong>Terjadi kesalahan:</strong>
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <h2>Tambah Menu Baru</h2>

    <form action="{{ route('menu.store') }}" method="POST">
        @csrf

        <p>
            <label>Kode Menu:</label><br>
            <input
                type="text"
                name="kode_menu"
                minlength="10"
                maxlength="10"
                pattern="[A-Za-z0-9]{10}"
                value="{{ old('kode_menu') }}"
                required
            >
        </p>

        <p>
            <label>Nama Menu:</label><br>
            <input
                type="text"
                name="nama_menu"
                minlength="3"
                maxlength="100"
                value="{{ old('nama_menu') }}"
                required
            >
        </p>

        <p>
            <label>Barcode:</label><br>
            <input
                type="text"
                name="barcode"
                minlength="5"
                maxlength="30"
                value="{{ old('barcode') }}"
                required
            >
        </p>

        <p>
            <label>Harga:</label><br>
            <input
                type="number"
                name="harga"
                min="1000"
                max="100000"
                value="{{ old('harga') }}"
                required
            >
        </p>

        <p>
            <label>Kategori Menu:</label><br>
            <select name="kategori_menu_id" required>
                <option value="">-- Pilih Kategori --</option>

                @foreach($kategoris as $kategori)
                    <option
                        value="{{ $kategori['id'] }}"
                        {{ old('kategori_menu_id') == $kategori['id'] ? 'selected' : '' }}
                    >
                        {{ $kategori['nama_kategori'] }}
                    </option>
                @endforeach

            </select>
        </p>

        <button type="submit">Tambah Menu</button>
    </form>

    <hr>

    <h2>Daftar Menu Kantin</h2>

    {{-- Pencarian dan filter --}}
    <form action="{{ route('menu.index') }}" method="GET">

        <p>
            <label>Cari Menu:</label><br>
            <input
                type="text"
                name="search"
                placeholder="Kode, nama, atau barcode"
                value="{{ request('search') }}"
            >
        </p>

        <p>
            <label>Filter Kategori:</label><br>
            <select name="kategori_menu_id">
                <option value="">Semua Kategori</option>

                @foreach($kategoris as $kategori)
                    <option
                        value="{{ $kategori['id'] }}"
                        {{ request('kategori_menu_id') == $kategori['id'] ? 'selected' : '' }}
                    >
                        {{ $kategori['nama_kategori'] }}
                    </option>
                @endforeach

            </select>
        </p>

        <button type="submit">Cari</button>
        <a href="{{ route('menu.index') }}">Reset</a>

    </form>

    <br>

    {{-- Tabel menu --}}
    <table border="1" cellpadding="8" cellspacing="0">

        <thead>
            <tr>
                <th>No</th>
                <th>Kode Menu</th>
                <th>Nama Menu</th>
                <th>Barcode</th>
                <th>Harga</th>
                <th>Kategori</th>
                <th>Status Harga</th>
                <th>Aksi</th>
            </tr>
        </thead>

        <tbody>

            @forelse($menus as $index => $menu)

                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $menu['kode_menu'] }}</td>
                    <td>{{ $menu['nama_menu'] }}</td>
                    <td>{{ $menu['barcode'] }}</td>

                    <td>
                        Rp {{ number_format($menu['harga'], 0, ',', '.') }}
                    </td>

                    <td>{{ $menu['nama_kategori'] }}</td>

                    <td>{{ $menu['status_harga'] }}</td>

                    <td>
                        <a href="{{ route('menu.edit', $menu['id']) }}">
                            Edit
                        </a>

                        |

                        <form
                            action="{{ route('menu.destroy', $menu['id']) }}"
                            method="POST"
                        >
                            @csrf
                            @method('DELETE')

                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>

            @empty

                <tr>
                    <td colspan="8">Data menu tidak ditemukan.</td>
                </tr>

            @endforelse

        </tbody>

    </table>

</body>
</html>
