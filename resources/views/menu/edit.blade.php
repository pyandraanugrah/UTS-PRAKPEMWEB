
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Menu Kantin</title>
</head>
<body>

    <h1>Edit Menu Kantin</h1>

    <p>Silakan perbarui informasi menu berikut.</p>

    <hr>

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

    <form
        action="{{ route('menu.update', $menu['id']) }}"
        method="POST"
    >

        @csrf
        @method('PUT')

        <p>
            <label>Kode Menu:</label><br>
            <input
                type="text"
                name="kode_menu"
                minlength="10"
                maxlength="10"
                pattern="[A-Za-z0-9]{10}"
                value="{{ old('kode_menu', $menu['kode_menu']) }}"
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
                value="{{ old('nama_menu', $menu['nama_menu']) }}"
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
                value="{{ old('barcode', $menu['barcode']) }}"
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
                value="{{ old('harga', $menu['harga']) }}"
                required
            >
        </p>

        <p>
            <label>Kategori Menu:</label><br>

            <select name="kategori_menu_id" required>

                @foreach($kategoris as $kategori)

                    <option
                        value="{{ $kategori['id'] }}"
                        {{ old('kategori_menu_id', $menu['kategori_menu_id']) == $kategori['id'] ? 'selected' : '' }}
                    >
                        {{ $kategori['nama_kategori'] }}
                    </option>

                @endforeach

            </select>
        </p>

        <button type="submit">Simpan Perubahan</button>

        <a href="{{ route('menu.index') }}">Kembali</a>

    </form>

</body>
</html>
