<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class IndexController extends Controller
{
    public function index()
    {
        // Landing baru (porting dari proyek prototipe); kabar & infografis
        // diambil dari CMS seperti pada tampilan lama.
        $title = 'MapBiomas Fire Indonesia — '.__('Pemantauan area terbakar');
        $description = __('Peta dan data area terbakar di Indonesia, diperbarui bulanan dan direkap tahunan sejak 2000.');

        return view('frontends.landing', [
            'title' => $title,
            'description' => $description,
            'news' => $this->getNews(),
            'infographic' => $this->getInfographic(),
            'factsheetLink' => $this->getFactsheetLink(),
        ]);
    }

    /**
     * Tautan factsheet bulanan untuk tombol di hero, mengikuti lokal aktif.
     * Sumbernya tabel factsheet supaya dapat diganti lewat CMS — sebelumnya
     * URL-nya ditulis langsung di Blade sehingga versi Inggris tidak mungkin
     * berbeda. Placeholder '#' diperlakukan sebagai belum diisi.
     */
    public function getFactsheetLink(): ?string
    {
        $kolom = app()->getLocale() === 'id' ? 'linkID' : 'linkEN';

        $link = DB::table('factsheet')->where('category', 'monthly')->value($kolom);

        return filled($link) && $link !== '#' ? $link : null;
    }

    public function selectNews()
    {
        if (app()->getLocale() == 'id') {
            return 'id, titleID as title, descriptionID as description, img, slug, publishdate';
        } else {
            return 'id, titleEN as title, descriptionEN as description, img, slug, publishdate';
        }
    }

    public function getSelectInfographic()
    {
        if (app()->getLocale() == 'id') {
            return 'id, titleID as title, imgID as img, descriptionID as description';
        } else {
            return 'id, titleEN as title, imgEN as img, descriptionEN as description';
        }
    }

    public function getInfographic()
    {
        $items = DB::table('infographic')
            ->selectRaw($this->getSelectInfographic() . ', category')
            ->where('publishdate', '<', Carbon::now('Asia/Jakarta'))
            ->where('status', 1)
            ->whereIn('category', ['monthly', 'annual'])
            ->orderBy('publishdate', 'desc')
            ->get();

        // Satu terbaru per kategori; urutan: monthly dulu, lalu annual.
        $order = ['monthly' => 0, 'annual' => 1];
        return $items
            ->groupBy('category')
            ->map(fn($group) => $group->first())
            ->sortBy(fn($item) => $order[$item->category] ?? 9)
            ->values();
    }

    public function getNews()
    {
        // Kartu kabar menampilkan konten terbaru apa pun kategorinya
        // (news/event), selama sudah terbit dan tanggalnya terlewat.
        return DB::table('news')
            ->selectRaw($this->selectNews())
            ->where('publishdate', '<', Carbon::now('Asia/Jakarta'))
            ->where('status', 1)
            ->orderBy('publishdate', 'desc')
            ->take(2)
            ->get();
    }

    public function getEvent()
    {
        return DB::table('news')
            ->selectRaw($this->selectNews())
            ->where('publishdate', '<', Carbon::now('Asia/Jakarta'))
            ->where('category', 'event')
            ->where('status', 1)
            ->orderBy('publishdate', 'desc')
            ->take(2)
            ->get();
    }
}
