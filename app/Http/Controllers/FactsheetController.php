<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class FactsheetController extends Controller
{
    public function index(){
        $title = 'MapBiomas Fire - factsheet';
        $nav = 'factsheet';
        return view('backends.factsheet', compact('title', 'nav'));
    }

    public function add(){
        $title = 'MapBiomas Fire - add factsheet';
        $nav = 'factsheet';
        return view('backends.addfactsheet', compact('title', 'nav'));
    }

    public function edit($id){
        $title = 'MapBiomas Fire - edit factsheet';
        $nav = 'factsheet';
        $idFactsheet = $id;
        return view('backends.editfactsheet', compact('title', 'nav', 'idFactsheet'));
    }

    public function getSelect(){
        if (App::getLocale() == 'id') {
            return 'id, linkID as link, fileID as file, titleID as title, descriptionID as description';
        }else{
            return 'id, linkEN as link, fileEN as file, titleEN as title, descriptionEN as description';
        }
    }

    public function listFactsheet(){
        $title = 'MapBiomas Fire - Factsheet';
        $description = "Inisiatif MapBiomas Fire dimulai sejak 2023, bersama sembilan jaringan organisasi masyarakat sipil (CSO) yang dikoordinasi oleh Auriga Nusantara dan Woods and Wayside International (WWI). MapBiomas Fire memetakan kebakaran menggunakan teknologi komputasi yang didukung algoritma machine learning dan deep learning.";
        // Satu daftar gabungan annual + monthly; kategorinya ditampilkan
        // sebagai badge per item, bukan tab terpisah.
        // Urut terbaru dulu; id jadi tiebreaker untuk baris yang dibuat
        // pada detik yang sama.
        $sheets = DB::table('factsheet')
                ->selectRaw($this->getSelect().', category')
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->get();
        return view('frontends.factsheet', compact('title', 'description', 'sheets'));
    }

    /**
     * Melayani PDF factsheet satu origin untuk sampul PDF.js.
     * Berkas unggahan dilayani langsung; tautan luar di-proxy karena
     * peramban memblokir fetch lintas-domain (CORS).
     */
    public function file(Request $request, $id){
        $suffix = $request->query('lang') === 'en' ? 'EN' : 'ID';
        $row = DB::table('factsheet')->where('id', $id)->first();
        abort_if(! $row, 404);

        // Kolom bahasa aktif lebih dulu; bila PDF/tautannya belum diisi,
        // jatuh ke bahasa lain supaya sampul tetap tampil.
        $suffixes = [$suffix, $suffix === 'EN' ? 'ID' : 'EN'];

        foreach ($suffixes as $sfx) {
            $fileCol = 'file'.$sfx;
            if (! empty($row->$fileCol)) {
                $path = storage_path('app/public/files/factsheet/'.$row->$fileCol);
                if (is_file($path)) {
                    return response()->file($path, [
                        'Cache-Control' => 'public, max-age=86400',
                    ]);
                }
            }
        }

        foreach ($suffixes as $sfx) {
            $linkCol = 'link'.$sfx;
            $link = $row->$linkCol ?? '';
            if (! (is_string($link) && str_starts_with($link, 'http'))) {
                continue;
            }
            // Range diteruskan ke server sumber supaya pdf.js bisa mengambil
            // potongan awal file saja (xref + halaman 1), bukan puluhan MB
            // penuh, sebelum menampilkan sampul.
            $range = $request->header('Range');
            $resp = Http::timeout(60)
                ->withHeaders($range ? ['Range' => $range] : [])
                ->get($link);
            if (! $resp->successful()) {
                continue;
            }

            $headers = array_filter([
                'Content-Type' => $resp->header('Content-Type', 'application/pdf'),
                'Accept-Ranges' => $resp->header('Accept-Ranges', 'bytes'),
                'Content-Range' => $resp->header('Content-Range'),
                'Content-Length' => $resp->header('Content-Length'),
                // pdf.js meminta ETag/Last-Modified untuk memastikan berkas
                // tidak berubah di antara permintaan range yang terpisah.
                'ETag' => $resp->header('ETag'),
                'Last-Modified' => $resp->header('Last-Modified'),
                'Cache-Control' => 'public, max-age=86400',
            ]);

            return response($resp->body(), $resp->status(), $headers);
        }

        abort(404);
    }
}
