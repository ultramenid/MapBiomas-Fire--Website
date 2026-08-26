<?php

namespace App\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Masmerise\Toaster\Toaster;

class AddInfographicComponent extends Component
{
    use WithFileUploads;
    public $period;
    public $category = 'monthly';
    public $publishdate, $titleID, $titleEN, $descriptionID, $descriptionEN, $photoID, $photoEN, $isactive=0;

    public function uploadImageID(){
        $file = $this->photoID->store('public/files/photos');
        $foto = $this->photoID->hashName();
        return $foto;
    }
    public function uploadImageEN(){
        $file = $this->photoEN->store('public/files/photos');
        $foto = $this->photoEN->hashName();
        return $foto;
    }

    public function updatedPhotoID($photo){
        $extension = pathinfo($photo->getFilename(), PATHINFO_EXTENSION);
        if (!in_array($extension, ['png', 'jpeg', 'bmp', 'gif','jpg','webp','mp4', 'avi', '3gp', 'mov', 'm4a'])) {
           $this->reset('photoID');
           Toaster::error('File not supported!');
        }

    }
    public function updatedPhotoEN($photo){
        $extension = pathinfo($photo->getFilename(), PATHINFO_EXTENSION);
        if (!in_array($extension, ['png', 'jpeg', 'bmp', 'gif','jpg','webp','mp4', 'avi', '3gp', 'mov', 'm4a'])) {
           $this->reset('photoEN');
           Toaster::error('File not supported!');
        }

    }
    public function storePosts(){
        if($this->manualValidation()){
            DB::table('infographic')->insert([
                'publishdate' => $this->publishdate,
                'period' => $this->period ?: null,
                'category' => $this->category,
                'titleID' => $this->titleID,
                'titleEN' => $this->titleEN,
                'slug' => Str::slug($this->titleID,'-'),
                'descriptionID' => $this->descriptionID,
                'descriptionEN' => $this->descriptionEN,
                'imgID' => $this->uploadImageID(),
                'imgEN' => $this->uploadImageEN(),
                'status' => $this->isactive,
                'created_at' => Carbon::now('Asia/Jakarta')
            ]);

            redirect()->to('/cms/listinfographic');
            // $this->redirect('/cms/listnews', navigate: true);
        }
    }
    public function render()
    {
        return view('livewire.add-infographic-component');
    }

    public function manualValidation(){
        if(!in_array($this->category, ['monthly', 'annual'])){
            Toaster::error('Category must be monthly or annual!');
            return;
        }elseif($this->titleID == '' ){
            Toaster::error('Title Indonesia is required!');
            return;
        }elseif($this->titleEN == '' ){
            Toaster::error('Title English is required!');
            return;
        }elseif($this->photoID == '' ){
            Toaster::error('Image Indonesia is required!');
            return;
        }elseif($this->photoEN == '' ){
            Toaster::error('Image English is required!');
            return;
        }elseif($this->descriptionID == '' ){
            Toaster::error('Description Indonesia is required!');
            return;
        }elseif($this->descriptionEN == '' ){
            Toaster::error('Description English is required!');
            return;
        }elseif($this->publishdate == '' ){
            Toaster::error('Publish date is required!');
            return;
        }
        return true;
    }
}
