<?php

namespace App\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

class FrontendInfographic extends Component
{
    public $paginate = 10;
    public $period = '';

    /**
     * Kategori infografis. Default kosong = semua, supaya membuka halaman ini
     * tidak diam-diam menyembunyikan salah satu kategori. Disinkronkan ke ?cat=
     * lewat history, jadi tautannya bisa dibagikan.
     */
    #[Url(as: 'cat')]
    public $category = '';

    public const KATEGORI = ['monthly', 'annual'];

    use WithPagination;

    /*
     * Bulan data infografis. Entri lama tak punya `period`, jadi bulannya
     * diambil dari publishdate — satu ekspresi ini dipakai untuk urutan,
     * saringan, dan daftar pilihan supaya ketiganya tak bisa berbeda.
     */
    private const MONTH = "COALESCE(period, substr(publishdate, 1, 7))";

    public function updatedPeriod(){
        $this->resetPage();
    }

    public function updatedCategory($value){
        if ($value !== '' && ! in_array($value, self::KATEGORI)) {
            $this->category = '';
        }

        $this->resetPage();
    }

    public function getSelectInfographic(){
        if (app()->getLocale() == 'id') {
            return 'id, titleID as title, imgID as img, period, publishdate';
        } else {
            return 'id, titleEN as title, imgEN as img, period, publishdate';
        }
    }

    private function published(){
        return DB::table('infographic')
        ->where('publishdate', '<', Carbon::now('Asia/Jakarta'))
        ->where('status', 1);
    }

    /** Bulan yang benar-benar ada isinya, terbaru dulu. */
    public function getPeriods(){
        return $this->published()
        ->selectRaw(self::MONTH . ' as period')
        ->distinct()
        ->orderByRaw(self::MONTH . ' desc')
        ->pluck('period');
    }

    public function getInfographics(){
        return $this->published()
        ->selectRaw($this->getSelectInfographic())
        ->when($this->period, fn ($q) => $q->whereRaw(self::MONTH . ' = ?', [$this->period]))
        ->when(in_array($this->category, self::KATEGORI), fn ($q) => $q->where('category', $this->category))
        // Bulan dulu supaya periode tak berselang-seling, lalu publishdate
        // agar urutan di dalam satu bulan tetap seperti sebelumnya.
        ->orderByRaw(self::MONTH . ' desc')
        ->orderBy('publishdate', 'desc')
        ->paginate($this->paginate);
    }

    /** 'Agustus 2026' / 'August 2026', mengikuti bahasa aktif. */
    public static function monthLabel($period, $publishdate = null){
        $bulan = $period ?: substr((string) $publishdate, 0, 7);
        return $bulan
            ? Carbon::createFromFormat('Y-m', $bulan)->locale(app()->getLocale())->translatedFormat('F Y')
            : '';
    }

    public function render()
    {
        $data = $this->getInfographics();
        return view('livewire.frontend-infographic', [
            'data' => $data,
            'periods' => $this->getPeriods(),
        ]);
    }
}
