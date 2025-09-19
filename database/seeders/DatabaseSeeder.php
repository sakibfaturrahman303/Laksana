<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Tool;
use App\Models\Borrowing;
use App\Models\BorrowingDetail;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // === USERS ===
        $admins = [
            User::create([
                'name' => 'Administrator',
                'email' => 'admin1@example.com',
                'password' => Hash::make('123'),
                'role' => 'admin'
            ]),
            User::create([
                'name' => 'Administrator 2',
                'email' => 'admin2@example.com',
                'password' => Hash::make('123'),
                'role' => 'admin'
            ]),
        ];

        $operators = [
            User::create([
                'name' => 'Operator 1',
                'email' => 'operator1@example.com',
                'password' => Hash::make('123'),
                'role' => 'operator'
            ]),
            User::create([
                'name' => 'Operator 2',
                'email' => 'operator2@example.com',
                'password' => Hash::make('123'),
                'role' => 'operator'
            ]),
        ];

        // === CATEGORIES ===
        $categories = collect([
            'Kamera',
            'Audio',
            'Pencahayaan',
            'Aksesoris',
            'Transportasi'
        ])->map(function ($cat) {
            return Category::create(['nama_kategori' => $cat]);
        });

        // === TOOLS ===
        $tools = [
            ['nama_alat' => 'Kamera Sony A7', 'merk' => 'Sony', 'jumlah_total' => 5, 'jumlah_tersedia' => 5, 'category_id' => $categories[0]->id],
            ['nama_alat' => 'Kamera Canon EOS', 'merk' => 'Canon', 'jumlah_total' => 3, 'jumlah_tersedia' => 3, 'category_id' => $categories[0]->id],
            ['nama_alat' => 'Mic Wireless', 'merk' => 'Shure', 'jumlah_total' => 4, 'jumlah_tersedia' => 4, 'category_id' => $categories[1]->id],
            ['nama_alat' => 'Boom Mic', 'merk' => 'Rode', 'jumlah_total' => 2, 'jumlah_tersedia' => 2, 'category_id' => $categories[1]->id],
            ['nama_alat' => 'Lampu LED', 'merk' => 'Godox', 'jumlah_total' => 6, 'jumlah_tersedia' => 6, 'category_id' => $categories[2]->id],
            ['nama_alat' => 'Tripod Kamera', 'merk' => 'Manfrotto', 'jumlah_total' => 5, 'jumlah_tersedia' => 5, 'category_id' => $categories[3]->id],
            ['nama_alat' => 'Drone DJI', 'merk' => 'DJI', 'jumlah_total' => 2, 'jumlah_tersedia' => 2, 'category_id' => $categories[4]->id],
            ['nama_alat' => 'Lensa Wide', 'merk' => 'Nikon', 'jumlah_total' => 3, 'jumlah_tersedia' => 3, 'category_id' => $categories[0]->id],
        ];

        foreach ($tools as $t) {
            Tool::create($t);
        }

        // === BORROWINGS ===
        // $createBorrowing = function ($status, $tanggalPinjam, $tanggalKembaliRencana, $tanggalKembaliAktual = null) use ($operators) {
        //     $borrowing = Borrowing::create([
        //         'operator_pinjam' => $operators[0]->id,
        //         'operator_kembali' => $status !== 'dipinjam' ? $operators[1]->id : null,
        //         'nama_peminjam' => fake()->name(),
        //         'tanggal_pinjam' => $tanggalPinjam,
        //         'tanggal_kembali_rencana' => $tanggalKembaliRencana,
        //         'tanggal_kembali_aktual' => $tanggalKembaliAktual,
        //         'keperluan' => fake()->sentence(3),
        //         'catatan' => $status === 'terlambat' ? 'Dikembalikan lewat dari jadwal' : null,
        //         'status' => $status,
        //     ]);

        //     $toolIds = Tool::inRandomOrder()->limit(2)->get();
        //     foreach ($toolIds as $tool) {
        //         BorrowingDetail::create([
        //             'borrowing_id' => $borrowing->id,
        //             'tool_id' => $tool->id,
        //             'jumlah_pinjam' => 1,
        //             'kondisi_awal' => 'Baik',
        //             'keterangan_awal' => 'Alat berfungsi dengan baik',
        //             'kondisi_akhir' => $status === 'selesai' ? 'Baik' : null,
        //             'keterangan_akhir' => $status === 'selesai' ? 'Alat kembali dengan baik' : null,
        //         ]);

        //         // Kurangi stok hanya kalau status masih dipinjam / terlambat
        //         if (in_array($status, ['dipinjam', 'terlambat'])) {
        //             $tool->decrement('jumlah_tersedia', 1);
        //         }
        //     }
        // };

        // // 3 peminjaman selesai
        // for ($i = 0; $i < 3; $i++) {
        //     $tanggalPinjam = Carbon::now()->subDays(rand(5, 10));
        //     $tanggalKembaliRencana = $tanggalPinjam->copy()->addDays(3);
        //     $tanggalKembaliAktual = $tanggalKembaliRencana->copy();
        //     $createBorrowing('selesai', $tanggalPinjam, $tanggalKembaliRencana, $tanggalKembaliAktual);
        // }

        // // 4 peminjaman masih berlangsung
        // for ($i = 0; $i < 4; $i++) {
        //     $tanggalPinjam = Carbon::now()->subDays(rand(1, 3));
        //     $tanggalKembaliRencana = $tanggalPinjam->copy()->addDays(5);
        //     $createBorrowing('dipinjam', $tanggalPinjam, $tanggalKembaliRencana);
        // }

        // // 2 peminjaman terlambat
        // for ($i = 0; $i < 2; $i++) {
        //     $tanggalPinjam = Carbon::now()->subDays(7);
        //     $tanggalKembaliRencana = $tanggalPinjam->copy()->addDays(3);
        //     $createBorrowing('terlambat', $tanggalPinjam, $tanggalKembaliRencana);
        // }
    }
}
