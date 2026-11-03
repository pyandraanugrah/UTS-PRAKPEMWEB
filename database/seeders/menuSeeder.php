<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $pdo = DB::connection()->getPdo();

        try {
            $pdo->beginTransaction();

            // Memasukkan kategori
            $stmt = $pdo->prepare(
                "INSERT INTO kategori_menu (nama_kategori)
                 VALUES (?), (?)"
            );

            $stmt->execute([
                'Makanan',
                'Minuman'
            ]);

            // Mengambil ID kategori
            $stmt = $pdo->prepare(
                "SELECT id, nama_kategori
                 FROM kategori_menu"
            );

            $stmt->execute();

            $kategori = $stmt->fetchAll(
                \PDO::FETCH_KEY_PAIR
            );

            $makananId = array_search('Makanan', $kategori);
            $minumanId = array_search('Minuman', $kategori);

            // Memasukkan data menu
            $stmt = $pdo->prepare(
                "INSERT INTO menu
                (kode_menu, nama_menu, barcode, harga,
                kategori_menu_id, created_at, updated_at)
                VALUES (?, ?, ?, ?, ?, NOW(), NOW())"
            );

            $dataMenu = [
                [
                    'MN00000001',
                    'Nasi Goreng',
                    'BR-MKN-0001',
                    18000,
                    $makananId
                ],
                [
                    'MN00000002',
                    'Es Teh',
                    'BR-MNM-0001',
                    5000,
                    $minumanId
                ],
                [
                    'MN00000003',
                    'Mie Rebus',
                    'BR-MKN-0002',
                    15000,
                    $makananId
                ]
            ];

            foreach ($dataMenu as $menu) {
                $stmt->execute($menu);
            }

            $pdo->commit();

            echo "Data awal berhasil dimasukkan.\n";

        } catch (\Throwable $e) {
            $pdo->rollBack();

            throw $e;
        }
    }
}
