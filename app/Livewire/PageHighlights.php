<?php

namespace App\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Masmerise\Toaster\Toaster;

class PageHighlights extends Component
{
    public $rows = [];

    public function mount(){
        $this->rows = DB::table('highlights')->orderBy('id')->get()
            ->map(fn ($r) => (array) $r)->all();
    }

    public function storePage(){
        foreach ($this->rows as $i => $row) {
            $n = $i + 1;
            if(! is_numeric($row['value'])){
                Toaster::error("Highlight $n: value must be a number");
                return;
            }elseif(trim($row['labelEN']) == '' || trim($row['labelID']) == ''){
                Toaster::error("Highlight $n: label English and Indonesia are required");
                return;
            }
        }

        foreach ($this->rows as $row) {
            DB::table('highlights')->where('id', $row['id'])->update([
                'value' => $row['value'],
                'decimals' => max(0, min(2, (int) $row['decimals'])),
                'unitEN' => $row['unitEN'],
                'unitID' => $row['unitID'],
                'labelEN' => $row['labelEN'],
                'labelID' => $row['labelID'],
                'updated_at' => now(),
            ]);
        }
        Toaster::success('Succesfully update highlights');
    }

    public function render()
    {
        return view('livewire.page-highlights');
    }
}
