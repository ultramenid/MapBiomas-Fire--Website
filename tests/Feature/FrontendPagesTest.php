<?php

namespace Tests\Feature;

use App\Livewire\FrontendInfographic;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Livewire\Livewire;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

/**
 * Halaman publik selain landing, plus navbar bersama yang mereka pakai.
 */
class FrontendPagesTest extends TestCase
{
    use RefreshDatabase;

    private function isiHalaman(string $tabel, array $ubah = []): void
    {
        $default = [
            'name' => 'uji',
            'contentID' => '<p>Isi bahasa Indonesia.</p>',
            'contentEN' => '<p>English content.</p>',
        ];

        // Factsheet tidak lagi menyimpan satu blok konten, melainkan
        // judul, deskripsi, dan tautan unduhan per kategori.
        if ($tabel === 'factsheet') {
            $default = [
                'titleID' => 'Judul bahasa Indonesia.',
                'titleEN' => 'Title in english.',
                'descriptionID' => 'Deskripsi bahasa Indonesia.',
                'descriptionEN' => 'Description in english.',
                'linkID' => 'https://contoh.id/berkas.pdf',
                'linkEN' => 'https://example.com/file.pdf',
            ];
        }

        DB::table($tabel)->insert(array_merge($default, $ubah));
    }

    private function terbitkanBerita(array $ubah = []): int
    {
        return DB::table('news')->insertGetId(array_merge([
            'category' => 'news',
            'publishdate' => Carbon::now('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'),
            'titleID' => 'Judul berita',
            'titleEN' => 'News title',
            'img' => 'berita.jpg',
            'descriptionID' => '<p>Ringkasan.</p>',
            'descriptionEN' => '<p>Summary.</p>',
            'contentID' => '<p>Isi lengkap.</p>',
            'contentEN' => '<p>Full body.</p>',
            'slug' => 'judul-berita',
            'status' => '1',
        ], $ubah));
    }

    // ── Halaman isi satu baris ────────────────────────────────────────────

    public static function halamanIsiTunggal(): array
    {
        return [
            'about' => ['about', 'pageabout'],
            'terms of use' => ['termsofuse', 'pagetermofuse'],
            'reference map' => ['refrencemap', 'pagerefrencemap'],
            'downloads' => ['downloads', 'pagedownload'],
        ];
    }

    #[DataProvider('halamanIsiTunggal')]
    public function test_halaman_isi_menampilkan_konten_sesuai_bahasa(string $rute, string $tabel): void
    {
        $this->isiHalaman($tabel, ['id' => 1]);

        $this->get(route($rute, 'id'))->assertOk()->assertSee('Isi bahasa Indonesia.');
        $this->get(route($rute, 'en'))->assertOk()->assertSee('English content.');
    }

    /**
     * Baris isinya belum tentu ada di pemasangan baru; halaman tetap harus
     * terbuka, bukan melempar galat.
     */
    #[DataProvider('halamanIsiTunggal')]
    public function test_halaman_isi_tetap_terbuka_saat_konten_belum_diisi(string $rute): void
    {
        $this->get(route($rute, 'id'))->assertOk();
    }

    // ── Halaman berkategori dua ───────────────────────────────────────────

    public static function halamanBerkategori(): array
    {
        return [
            'ATBD' => ['atbd', 'pageatbd', 'contentID'],
            'factsheet' => ['factsheet', 'factsheet', 'descriptionID'],
        ];
    }

    #[DataProvider('halamanBerkategori')]
    public function test_kategori_menentukan_isi_yang_tampil(string $rute, string $tabel, string $isiKey): void
    {
        $this->isiHalaman($tabel, ['category' => 'monthly', $isiKey => 'Isi bulanan.']);
        $this->isiHalaman($tabel, ['category' => 'annual', $isiKey => 'Isi tahunan.']);

        $this->get(route($rute, ['lang' => 'id', 'cat' => 'monthly']))
            ->assertOk()->assertSee('Isi bulanan.')->assertDontSee('Isi tahunan.');

        $this->get(route($rute, ['lang' => 'id', 'cat' => 'annual']))
            ->assertOk()->assertSee('Isi tahunan.')->assertDontSee('Isi bulanan.');
    }

    #[DataProvider('halamanBerkategori')]
    public function test_kategori_kosong_atau_ngawur_jatuh_ke_tahunan(string $rute, string $tabel, string $isiKey): void
    {
        $this->isiHalaman($tabel, ['category' => 'annual', $isiKey => 'Isi tahunan.']);

        $this->get(route($rute, 'id'))->assertOk()->assertSee('Isi tahunan.');
        $this->get(route($rute, ['lang' => 'id', 'cat' => 'sembarang']))
            ->assertOk()->assertSee('Isi tahunan.');
    }

    public function test_factsheet_menampilkan_deskripsi_sesuai_bahasa(): void
    {
        $this->isiHalaman('factsheet', ['category' => 'annual']);

        $this->get(route('factsheet', 'id'))->assertOk()
            ->assertSee('Deskripsi bahasa Indonesia.')->assertDontSee('Description in english.');
        $this->get(route('factsheet', 'en'))->assertOk()
            ->assertSee('Description in english.')->assertDontSee('Deskripsi bahasa Indonesia.');
    }

    /**
     * Entri warisan hasil migrasi tak punya berkas maupun tautan. Tombolnya
     * harus hilang, bukan terbit sebagai <a href=""> yang memuat ulang halaman.
     */
    public function test_factsheet_tanpa_berkas_dan_tautan_tidak_menampilkan_tombol_unduh(): void
    {
        $this->isiHalaman('factsheet', [
            'category' => 'annual',
            'linkID' => '', 'linkEN' => '',
            'fileID' => null, 'fileEN' => null,
        ]);

        $this->get(route('factsheet', 'id'))->assertOk()
            ->assertDontSee('href=""', false)
            ->assertDontSee(__('Download Factsheet'));
    }

    /**
     * Status aktif tidak boleh hanya dibawa warna — aria-current membuatnya
     * terbaca pembaca layar.
     */
    #[DataProvider('halamanBerkategori')]
    public function test_tab_kategori_menandai_yang_sedang_aktif(string $rute, string $tabel, string $isiKey): void
    {
        $this->isiHalaman($tabel, ['category' => 'annual']);

        $this->get(route($rute, ['lang' => 'id', 'cat' => 'annual']))
            ->assertSee('aria-current="page"', false)
            ->assertSee('Monthly')
            ->assertSee('Annual');
    }

    // ── FAQ ───────────────────────────────────────────────────────────────

    public function test_faq_menampilkan_pertanyaan_sesuai_bahasa(): void
    {
        DB::table('faq')->insert([
            'questionID' => 'Apa itu MapBiomas Fire?',
            'answerID' => 'Inisiatif pemantauan area terbakar.',
            'questionEN' => 'What is MapBiomas Fire?',
            'answerEN' => 'A burned area monitoring initiative.',
        ]);

        $this->get(route('faq', 'id'))->assertOk()->assertSee('Apa itu MapBiomas Fire?');
        $this->get(route('faq', 'en'))->assertOk()->assertSee('What is MapBiomas Fire?');
    }

    // ── Berita ────────────────────────────────────────────────────────────

    public function test_daftar_kabar_dan_agenda_terbuka(): void
    {
        $this->terbitkanBerita();
        $this->get(route('newsnevent', 'id'))->assertOk();
    }

    public function test_detail_berita_menampilkan_isi_sesuai_bahasa(): void
    {
        $id = $this->terbitkanBerita();

        $this->get(route('detailnews', ['id', $id, 'judul-berita']))
            ->assertOk()->assertSee('Isi lengkap.', false);

        $this->get(route('detailnews', ['en', $id, 'judul-berita']))
            ->assertOk()->assertSee('Full body.', false);
    }

    /** Deskripsi masuk ke <meta>; markup mentah di sana merusak pratinjau. */
    public function test_meta_deskripsi_berita_bebas_markup(): void
    {
        $id = $this->terbitkanBerita(['descriptionEN' => '<p>Ringkasan berita.</p>']);

        $this->get(route('detailnews', ['en', $id, 'judul-berita']))
            ->assertSee('name="description" content="Ringkasan berita."', false)
            ->assertDontSee('content="&lt;p&gt;', false);
    }

    private function terbitkanInfografis(string $category, string $judul): void
    {
        DB::table('infographic')->insert([
            'publishdate' => Carbon::now('Asia/Jakarta')->subDay()->format('Y-m-d H:i:s'),
            'period' => Carbon::now('Asia/Jakarta')->format('Y-m'),
            'category' => $category,
            'titleID' => $judul, 'titleEN' => $judul,
            'imgID' => 'a.jpg', 'imgEN' => 'a.jpg',
            'descriptionID' => 'x', 'descriptionEN' => 'x',
            'slug' => strtolower($judul),
            'status' => '1',
        ]);
    }

    public function test_saringan_kategori_infografis_menyaring_daftar(): void
    {
        $this->terbitkanInfografis('monthly', 'Bulanan');
        $this->terbitkanInfografis('annual', 'Tahunan');

        Livewire::test(FrontendInfographic::class)
            ->assertSee('Bulanan')->assertSee('Tahunan')
            ->set('category', 'annual')
            ->assertSee('Tahunan')->assertDontSee('Bulanan')
            ->set('category', 'monthly')
            ->assertSee('Bulanan')->assertDontSee('Tahunan');
    }

    /**
     * Default kosong berarti semua. Membuka halaman ini tidak boleh diam-diam
     * menyembunyikan salah satu kategori, dan nilai ngawur dari ?cat= juga
     * dikembalikan ke semua, bukan menghasilkan daftar kosong.
     */
    public function test_kategori_infografis_kosong_atau_ngawur_menampilkan_semua(): void
    {
        $this->terbitkanInfografis('monthly', 'Bulanan');
        $this->terbitkanInfografis('annual', 'Tahunan');

        Livewire::test(FrontendInfographic::class)
            ->assertSet('category', '')
            ->assertSee('Bulanan')->assertSee('Tahunan')
            ->set('category', 'sembarang')
            ->assertSet('category', '')
            ->assertSee('Bulanan')->assertSee('Tahunan');
    }

    public function test_infografis_terbuka(): void
    {
        $this->get(route('infographics', 'id'))->assertOk();
    }

    // ── Navbar bersama ────────────────────────────────────────────────────

    /**
     * Beranda, halaman biasa, dan halaman detail memakai partials/navPC yang
     * sama — dulu tiga salinan terpisah yang pelan-pelan berbeda isinya.
     */
    public function test_navbar_beranda_halaman_biasa_dan_detail_menjangkau_tujuan_yang_sama(): void
    {
        $id = $this->terbitkanBerita();

        $tujuan = [
            '/en/about', '/en/faq', '/en/termsofuse', '/en/atbd?cat=monthly',
            '/en/atbd?cat=annual', '/en/refrencemap', '/en/newnevent',
            '/en/downloads', '/en/infographics', '/en/factsheet',
        ];

        $halaman = [
            'beranda' => $this->get(route('index', 'en')),
            'biasa' => $this->get(route('about', 'en')),
            'detail' => $this->get(route('detailnews', ['en', $id, 'judul-berita'])),
        ];

        foreach ($halaman as $nama => $res) {
            $res->assertOk();
            foreach ($tujuan as $t) {
                $res->assertSee($t, false, "tujuan $t hilang dari navbar halaman $nama");
            }
        }
    }

    /**
     * Label menu juga harus sama di ketiga halaman: dulu beranda dan halaman
     * lain menulis label yang sama dengan ejaan berbeda.
     */
    public function test_label_menu_sama_di_semua_halaman(): void
    {
        $id = $this->terbitkanBerita();

        $label = ['about', 'FAQ', 'map &amp; data', 'methodology', 'news &amp; event',
                  'downloads', 'terms of use', 'reference map', 'factsheet'];

        foreach ([route('index', 'en'), route('about', 'en'),
                  route('detailnews', ['en', $id, 'judul-berita'])] as $url) {
            $res = $this->get($url)->assertOk();
            foreach ($label as $l) {
                $res->assertSee($l, false);
            }
        }
    }

    /**
     * Pengalih bahasa di navbar seluler membangun ulang rute yang sedang aktif.
     * Pada rute detail yang butuh id dan slug, versi lamanya melempar galat —
     * itu sebabnya dulu dimatikan.
     */
    public function test_pengalih_bahasa_seluler_mempertahankan_parameter_rute(): void
    {
        $id = $this->terbitkanBerita();

        $this->get(route('detailnews', ['id', $id, 'judul-berita']))
            ->assertOk()
            ->assertSee("/en/news/$id/judul-berita", false);
    }

    public function test_pengalih_bahasa_seluler_mempertahankan_query(): void
    {
        $this->isiHalaman('pageatbd', ['category' => 'annual']);

        $this->get(route('atbd', ['lang' => 'id', 'cat' => 'annual']))
            ->assertOk()
            ->assertSee('/en/atbd?cat=annual', false);
    }
}
