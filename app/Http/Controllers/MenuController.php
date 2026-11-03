<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PDO;
use Throwable;

class MenuController extends Controller
{
    // READ: Menampilkan, mencari, dan memfilter menu
    public function index(Request $request)
    {
        try {
            $pdo = DB::connection()->getPdo();

            $search = trim($request->query('search', ''));
            $kategori = $request->query('kategori_menu_id', '');

            $sql = "SELECT menu.*, kategori_menu.nama_kategori
                    FROM menu
                    INNER JOIN kategori_menu
                    ON menu.kategori_menu_id = kategori_menu.id
                    WHERE 1=1";

            $params = [];

            // Pencarian kode, nama, dan barcode
            if ($search !== '') {
                $sql .= " AND (
                    menu.kode_menu LIKE ?
                    OR menu.nama_menu LIKE ?
                    OR menu.barcode LIKE ?
                )";

                $keyword = "%{$search}%";

                $params[] = $keyword;
                $params[] = $keyword;
                $params[] = $keyword;
            }

            // Filter kategori
            if ($kategori !== '') {
                $sql .= " AND menu.kategori_menu_id = ?";
                $params[] = $kategori;
            }

            $sql .= " ORDER BY menu.id ASC";

            $stmt = $pdo->prepare($sql);
            $stmt->execute($params);

            $menus = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Menentukan status harga
            foreach ($menus as &$menu) {
                $menu['status_harga'] = $this->statusHarga(
                    $menu['harga']
                );
            }

            unset($menu);

            // Mengambil kategori
            $stmt = $pdo->prepare(
                "SELECT * FROM kategori_menu ORDER BY id ASC"
            );

            $stmt->execute();

            $kategoris = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return view('menu.index', compact(
                'menus',
                'kategoris'
            ));

        } catch (Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Gagal mengambil data menu.'
            );
        }
    }

    // CREATE: Menambahkan menu
    public function store(Request $request)
    {
        $data = $request->all();

        foreach (['kode_menu', 'nama_menu', 'barcode'] as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $data[$field] = strip_tags(trim($data[$field]));
            }
        }

        $validator = Validator::make($data, [
            'kode_menu' => [
                'required',
                'regex:/^[A-Za-z0-9]{10}$/'
            ],
            'nama_menu' => 'required|string|min:3|max:100',
            'barcode' => 'required|string|min:5|max:30',
            'harga' => 'required|integer|min:1000|max:100000',
            'kategori_menu_id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $pdo = DB::connection()->getPdo();

            // Memastikan kategori tersedia
            $stmt = $pdo->prepare(
                "SELECT id FROM kategori_menu WHERE id = ?"
            );

            $stmt->execute([$data['kategori_menu_id']]);

            if (!$stmt->fetch()) {
                return back()->withErrors([
                    'kategori_menu_id' => 'Kategori tidak tersedia.'
                ])->withInput();
            }

            // Memeriksa kode, nama, dan barcode duplikat
            $stmt = $pdo->prepare(
                "SELECT COUNT(*) FROM menu
                 WHERE kode_menu = ?
                 OR nama_menu = ?
                 OR barcode = ?"
            );

            $stmt->execute([
                $data['kode_menu'],
                $data['nama_menu'],
                $data['barcode']
            ]);

            if ($stmt->fetchColumn() > 0) {
                return back()->withErrors([
                    'kode_menu' => 'Kode, nama, atau barcode sudah digunakan.'
                ])->withInput();
            }

            // Menyimpan data
            $stmt = $pdo->prepare(
                "INSERT INTO menu
                (kode_menu, nama_menu, barcode, harga,
                kategori_menu_id, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())"
            );

            $stmt->execute([
                $data['kode_menu'],
                $data['nama_menu'],
                $data['barcode'],
                $data['harga'],
                $data['kategori_menu_id']
            ]);

            return redirect()->route('menu.index')->with(
                'success',
                'Menu berhasil ditambahkan.'
            );

        } catch (Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Gagal menyimpan menu.'
            )->withInput();
        }
    }

    // EDIT: Mengambil data menu
    public function edit($id)
    {
        try {
            $pdo = DB::connection()->getPdo();

            $stmt = $pdo->prepare(
                "SELECT * FROM menu WHERE id = ?"
            );

            $stmt->execute([$id]);

            $menu = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$menu) {
                return redirect()->route('menu.index')->with(
                    'error',
                    'Menu tidak ditemukan.'
                );
            }

            $stmt = $pdo->prepare(
                "SELECT * FROM kategori_menu ORDER BY id ASC"
            );

            $stmt->execute();

            $kategoris = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return view('menu.edit', compact(
                'menu',
                'kategoris'
            ));

        } catch (Throwable $e) {
            report($e);

            return redirect()->route('menu.index')->with(
                'error',
                'Gagal mengambil data.'
            );
        }
    }

    // UPDATE: Memperbarui menu
    public function update(Request $request, $id)
    {
        $data = $request->all();

        foreach (['kode_menu', 'nama_menu', 'barcode'] as $field) {
            if (isset($data[$field]) && is_string($data[$field])) {
                $data[$field] = strip_tags(trim($data[$field]));
            }
        }

        $validator = Validator::make($data, [
            'kode_menu' => [
                'required',
                'regex:/^[A-Za-z0-9]{10}$/'
            ],
            'nama_menu' => 'required|string|min:3|max:100',
            'barcode' => 'required|string|min:5|max:30',
            'harga' => 'required|integer|min:1000|max:100000',
            'kategori_menu_id' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $pdo = DB::connection()->getPdo();

            // Memastikan menu tersedia
            $stmt = $pdo->prepare(
                "SELECT id FROM menu WHERE id = ?"
            );

            $stmt->execute([$id]);

            if (!$stmt->fetch()) {
                return redirect()->route('menu.index')->with(
                    'error',
                    'Menu tidak ditemukan.'
                );
            }

            // Memastikan kategori tersedia
            $stmt = $pdo->prepare(
                "SELECT id FROM kategori_menu WHERE id = ?"
            );

            $stmt->execute([$data['kategori_menu_id']]);

            if (!$stmt->fetch()) {
                return back()->withErrors([
                    'kategori_menu_id' => 'Kategori tidak tersedia.'
                ])->withInput();
            }

            // Memeriksa duplikat selain data sendiri
            $stmt = $pdo->prepare(
                "SELECT COUNT(*) FROM menu
                 WHERE (
                    kode_menu = ?
                    OR nama_menu = ?
                    OR barcode = ?
                 )
                 AND id != ?"
            );

            $stmt->execute([
                $data['kode_menu'],
                $data['nama_menu'],
                $data['barcode'],
                $id
            ]);

            if ($stmt->fetchColumn() > 0) {
                return back()->withErrors([
                    'kode_menu' => 'Kode, nama, atau barcode sudah digunakan.'
                ])->withInput();
            }

            // Memperbarui data
            $stmt = $pdo->prepare(
                "UPDATE menu SET
                kode_menu = ?,
                nama_menu = ?,
                barcode = ?,
                harga = ?,
                kategori_menu_id = ?,
                updated_at = NOW()
                WHERE id = ?"
            );

            $stmt->execute([
                $data['kode_menu'],
                $data['nama_menu'],
                $data['barcode'],
                $data['harga'],
                $data['kategori_menu_id'],
                $id
            ]);

            return redirect()->route('menu.index')->with(
                'success',
                'Menu berhasil diperbarui.'
            );

        } catch (Throwable $e) {
            report($e);

            return back()->with(
                'error',
                'Gagal memperbarui menu.'
            )->withInput();
        }
    }

    // DELETE: Menghapus menu
    public function destroy($id)
    {
        try {
            $pdo = DB::connection()->getPdo();

            $stmt = $pdo->prepare(
                "DELETE FROM menu WHERE id = ?"
            );

            $stmt->execute([$id]);

            if ($stmt->rowCount() === 0) {
                return redirect()->route('menu.index')->with(
                    'error',
                    'Menu tidak ditemukan.'
                );
            }

            return redirect()->route('menu.index')->with(
                'success',
                'Menu berhasil dihapus.'
            );

        } catch (Throwable $e) {
            report($e);

            return redirect()->route('menu.index')->with(
                'error',
                'Gagal menghapus menu.'
            );
        }
    }

    // Fungsi menentukan status harga
    private function statusHarga($harga): string
    {
        if ($harga >= 20000) {
            return 'Premium';
        }

        return 'Reguler';
    }
}
