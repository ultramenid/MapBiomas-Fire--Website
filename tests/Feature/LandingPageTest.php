<?php

namespace Tests\Feature;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/**
 * Landing (frontends/landing.blade.php) — halaman yang dilayani rute `index`.
 *
 * Isinya sengaja berupa pemeriksaan perilaku, bukan potongan markup: yang diuji
 * adalah tujuan tautan, penyaringan data, dan teks yang sampai ke pembaca.
 */
class LandingPageTest extends TestCase
{
    use RefreshDatabase;

    /** Berita terbit, di masa lalu, kategori `news`. */
    private function terbitkanBerita(array $ubah = []): int
    {
        return DB::table('news')->insertGetId(array_merge([
            'category' => 'news',
            'publishdate' => Carbon::now('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'),
            'titleID' => 'Judul berita',
            'titleEN' => 'News title',
            'img' => 'berita.jpg',
            'descriptionID' => 'Ringkasan berita.',
            'descriptionEN' => 'News summary.',
            'contentID' => '<p>Isi.</p>',
            'contentEN' => '<p>Body.</p>',
            'slug' => 'judul-berita',
            'status' => '1',
        ], $ubah));
    }

    private function terbitkanInfografis(array $ubah = []): void
    {
        DB::table('infographic')->insert(array_merge([
            'publishdate' => Carbon::now('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'),
            'titleID' => 'Infografis uji',
            'titleEN' => 'Test infographic',
            'category' => 'monthly',
            'imgID' => 'info-id.jpg',
            'imgEN' => 'info-en.jpg',
            'descriptionID' => 'Keterangan infografis.',
            'descriptionEN' => 'Infographic caption.',
            'slug' => 'infografis-uji',
            'status' => '1',
        ], $ubah));
    }

    /**
     * Kartu landing menampilkan placeholder bila berkas fotonya tidak ada,
     * jadi uji yang menyangkut gambar harus benar-benar membuat berkasnya.
     */
    private function siapkanFoto(string $nama): void
    {
        $dir = public_path('storage/files/photos');
        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }
        file_put_contents($dir.'/'.$nama, '');
        $this->beforeApplicationDestroyed(fn () => @unlink($dir.'/'.$nama));
    }

    // ── Rute dan lokal ────────────────────────────────────────────────────

    public function test_akar_dialihkan_ke_bahasa_inggris(): void
    {
        $this->get('/')->assertRedirect('/en');
    }

    public function test_landing_terbuka_di_kedua_bahasa(): void
    {
        foreach (['en', 'id'] as $lang) {
            $this->get("/$lang")->assertOk()->assertViewIs('frontends.landing');
        }
    }

    public function test_atribut_bahasa_dokumen_mengikuti_lokal(): void
    {
        $this->get('/id')->assertSee('<html lang="id"', false);
        $this->get('/en')->assertSee('<html lang="en"', false);
    }

    /**
     * Label menu sengaja tidak diterjemahkan: tetap berbahasa Inggris di kedua
     * lokal. Terjemahannya masih ada di lang/id.json, jadi tanpa pagar ini
     * cukup satu __() yang dipasang kembali untuk mengembalikannya diam-diam.
     */
    public function test_label_menu_tetap_inggris_di_kedua_lokal(): void
    {
        foreach (['id', 'en'] as $lang) {
            $this->get("/$lang")
                ->assertSee('map &amp; data', false)
                ->assertSee('news &amp; event', false)
                ->assertSee('methodology')
                ->assertSee('downloads')
                ->assertDontSee('peta &amp; data', false)
                ->assertDontSee('kabar &amp; acara', false)
                ->assertDontSee('metodologi')
                ->assertDontSee('unduhan');
        }
    }

    // ── Navigasi ──────────────────────────────────────────────────────────

    /**
     * Menu landing harus menjangkau seluruh halaman publik. Daftar ini juga
     * berfungsi sebagai pagar: menghapus tujuan dari menu akan menggagalkan tes.
     */
    public function test_menu_menjangkau_seluruh_halaman_publik(): void
    {
        $respons = $this->get('/en');

        foreach ([
            '/en/about',
            '/en/faq',
            '/en/termsofuse',
            '/en/atbd?cat=monthly',
            '/en/atbd?cat=annual',
            '/en/refrencemap',
            '/en/newnevent',
            '/en/downloads',
            '/en/infographics',
            '/en/factsheet',
        ] as $tujuan) {
            $respons->assertSee($tujuan, false);
        }
    }

    public function test_seluruh_tujuan_menu_dapat_dibuka(): void
    {
        foreach ([
            '/en/about', '/en/faq', '/en/termsofuse', '/en/atbd?cat=monthly',
            '/en/atbd?cat=annual', '/en/refrencemap', '/en/newnevent',
            '/en/downloads', '/en/infographics', '/en/factsheet',
        ] as $tujuan) {
            $this->get($tujuan)->assertOk();
        }
    }

    public function test_pengalih_bahasa_menunjuk_kedua_lokal(): void
    {
        $this->get('/id')
            ->assertSee('hreflang="en"', false)
            ->assertSee('hreflang="id"', false)
            ->assertSee('aria-current="true"', false);
    }

    // ── Hero ──────────────────────────────────────────────────────────────

    /** Dulu menunjuk anchor #metodologi yang tidak pernah ada di halaman. */
    public function test_tombol_fact_sheet_menuju_halaman_fact_sheet(): void
    {
        $this->get('/en')
            ->assertSee('/en/factsheet', false)
            ->assertDontSee('#metodologi', false);
    }

    /**
     * Tautan factsheet di hero diambil dari tabel factsheet per lokal, bukan
     * ditulis di Blade — kalau kembali dikeraskan, versi Inggris akan diam-diam
     * memakai berkas berbahasa Indonesia.
     */
    public function test_tautan_factsheet_di_hero_berbeda_tiap_lokal(): void
    {
        DB::table('factsheet')->insert([
            'category' => 'monthly',
            'titleID' => 'Bulanan', 'titleEN' => 'Monthly',
            'descriptionID' => 'x', 'descriptionEN' => 'x',
            'linkID' => 'https://contoh.test/bulanan-id.pdf',
            'linkEN' => 'https://contoh.test/monthly-en.pdf',
        ]);

        $this->get('/id')
            ->assertSee('https://contoh.test/bulanan-id.pdf', false)
            ->assertDontSee('https://contoh.test/monthly-en.pdf', false);

        $this->get('/en')
            ->assertSee('https://contoh.test/monthly-en.pdf', false)
            ->assertDontSee('https://contoh.test/bulanan-id.pdf', false);
    }

    /** Belum diisi ('#' atau kosong) diarahkan ke halaman factsheet, bukan tautan mati. */
    public function test_tautan_factsheet_belum_diisi_jatuh_ke_halaman_factsheet(): void
    {
        DB::table('factsheet')->insert([
            'category' => 'monthly',
            'titleID' => 'Bulanan', 'titleEN' => 'Monthly',
            'descriptionID' => 'x', 'descriptionEN' => 'x',
            'linkID' => '#', 'linkEN' => '#',
        ]);

        $this->get('/id')->assertSee('/id/factsheet', false);
    }

    // ── Kabar ─────────────────────────────────────────────────────────────

    public function test_kabar_hanya_menampilkan_yang_sudah_terbit(): void
    {
        $this->terbitkanBerita(['titleEN' => 'Sudah terbit']);
        $this->terbitkanBerita([
            'titleEN' => 'Masih draf',
            'status' => '0',
            'slug' => 'draf',
        ]);
        $this->terbitkanBerita([
            'titleEN' => 'Terjadwal',
            'publishdate' => Carbon::now('Asia/Jakarta')->addWeek()->format('Y-m-d H:i:s'),
            'slug' => 'terjadwal',
        ]);

        $this->get('/en')
            ->assertSee('Sudah terbit')
            ->assertDontSee('Masih draf')
            ->assertDontSee('Terjadwal');
    }

    public function test_kabar_memuat_terbaru_dari_semua_kategori(): void
    {
        // Event yang sudah terbit juga layak muncul sebagai kabar terbaru.
        $this->terbitkanBerita([
            'titleEN' => 'Agenda lokakarya',
            'category' => 'event',
            'slug' => 'agenda',
            'publishdate' => Carbon::now('Asia/Jakarta')->subHours(2)->format('Y-m-d H:i:s'),
        ]);
        $this->terbitkanBerita(['titleEN' => 'Berita biasa']);

        $this->get('/en')
            ->assertSee('Agenda lokakarya')
            ->assertSee('Berita biasa');
    }

    public function test_kabar_dibatasi_dua_dan_terbaru_lebih_dulu(): void
    {
        foreach ([3, 1, 2, 4] as $hari) {
            $this->terbitkanBerita([
                'titleEN' => "Berita $hari hari lalu",
                'publishdate' => Carbon::now('Asia/Jakarta')->subDays($hari)->format('Y-m-d H:i:s'),
                'slug' => "berita-$hari",
            ]);
        }

        $isi = $this->get('/en')->assertOk()->getContent();

        $this->assertStringContainsString('Berita 1 hari lalu', $isi);
        $this->assertStringContainsString('Berita 2 hari lalu', $isi);
        $this->assertStringNotContainsString('Berita 3 hari lalu', $isi);
        $this->assertStringNotContainsString('Berita 4 hari lalu', $isi);
        $this->assertLessThan(
            strpos($isi, 'Berita 2 hari lalu'),
            strpos($isi, 'Berita 1 hari lalu'),
            'Kabar terbaru harus tampil lebih dulu.'
        );
    }

    /** Deskripsi berkolom teks biasa; markup yang terlanjur tersimpan disaring. */
    public function test_markup_pada_deskripsi_tidak_bocor_ke_halaman(): void
    {
        $this->terbitkanBerita([
            'descriptionEN' => '<p>Ringkasan dengan markup.</p>',
        ]);

        $this->get('/en')
            ->assertSee('Ringkasan dengan markup.')
            ->assertDontSee('&lt;p&gt;', false)
            ->assertDontSee('<p>Ringkasan dengan markup.</p>', false);
    }

    public function test_kabar_kosong_menampilkan_keterangan(): void
    {
        $this->get('/id')->assertSee('Belum ada kabar terbit.');
    }

    public function test_kabar_memakai_bahasa_yang_aktif(): void
    {
        $this->terbitkanBerita([
            'titleID' => 'Judul Indonesia',
            'titleEN' => 'English title',
        ]);

        $this->get('/id')->assertSee('Judul Indonesia')->assertDontSee('English title');
        $this->get('/en')->assertSee('English title')->assertDontSee('Judul Indonesia');
    }

    // ── Infografis ────────────────────────────────────────────────────────

    public function test_infografis_terbaru_yang_ditampilkan(): void
    {
        $this->terbitkanInfografis([
            'titleEN' => 'Infografis lama',
            'publishdate' => Carbon::now('Asia/Jakarta')->subMonth()->format('Y-m-d H:i:s'),
        ]);
        $this->terbitkanInfografis(['titleEN' => 'Infografis terbaru']);

        $this->get('/en')
            ->assertSee('Infografis terbaru')
            ->assertDontSee('Infografis lama');
    }

    public function test_infografis_kosong_menampilkan_keterangan(): void
    {
        $this->get('/id')->assertSee('Belum ada infografis terbit.');
    }

    public function test_petunjuk_perbesar_hanya_muncul_bila_ada_infografis(): void
    {
        $this->get('/id')->assertDontSee('Ketuk untuk memperbesar');

        $this->siapkanFoto('info-id.jpg');
        $this->terbitkanInfografis();
        $this->get('/id')->assertSee('Ketuk untuk memperbesar');
    }

    // ── Placeholder saat data tidak lengkap ───────────────────────────────

    public function test_kabar_tanpa_berkas_foto_memakai_placeholder(): void
    {
        $this->terbitkanBerita(['img' => 'hilang.jpg']);

        $this->get('/id')
            ->assertSee('Gambar belum tersedia')
            ->assertDontSee('storage/files/photos/hilang.jpg', false);
    }

    public function test_kabar_dengan_berkas_foto_menampilkan_gambarnya(): void
    {
        $this->siapkanFoto('ada.jpg');
        $this->terbitkanBerita(['img' => 'ada.jpg']);

        $this->get('/id')
            ->assertSee('storage/files/photos/ada.jpg', false)
            ->assertDontSee('Gambar belum tersedia');
    }

    public function test_kabar_tanpa_teks_inggris_memakai_penggantinya(): void
    {
        // titleEN dan descriptionEN nullable, jadi hanya /en yang bisa kosong.
        $this->terbitkanBerita(['titleEN' => null, 'descriptionEN' => null]);

        $this->get('/en')
            ->assertOk()
            ->assertSee('Untitled')
            ->assertSee('No summary yet.');
    }

    public function test_infografis_tanpa_berkas_foto_tetap_menampilkan_keterangan(): void
    {
        $this->terbitkanInfografis(['imgID' => 'hilang.jpg']);

        // Kotak abu-abu penggantinya sudah dilepas: yang penting keterangannya tetap ada.
        $this->get('/id')
            ->assertSee('Infografis uji')
            ->assertSee('Keterangan infografis.')
            // Tidak ada yang bisa diperbesar, jadi pemicu zoom ikut hilang.
            ->assertDontSee('Ketuk untuk memperbesar')
            ->assertDontSee('Belum ada infografis terbit.');
    }

    // ── Footer ────────────────────────────────────────────────────────────

    public function test_footer_memuat_setiap_kelompok_menu(): void
    {
        $respons = $this->get('/en');

        foreach (['about', 'FAQ', 'map &amp; data', 'methodology', 'news &amp; event', 'downloads'] as $kelompok) {
            $respons->assertSee($kelompok, false);
        }
    }
}
