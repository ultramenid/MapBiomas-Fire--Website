<?php

namespace App\Livewire;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;
use Masmerise\Toaster\Toaster;

class EditInfographicComponent extends Component
{
    use WithFileUploads;
    public $publishdate, $titleID, $titleEN, $photoID, $photoEN, $isactive, $descriptionID, $descriptionEN, $idInfographic;
    public $uphotoID, $uphotoEN;
    public $period;
    public $category;

    public function mount($id){
        $this->idInfographic = $id;
        $data = DB::table('infographic')->where('id', $id)->first();
        $this->publishdate = $data->publishdate;
        $this->period = $data->period;
        $this->category = $data->category;
        $this->titleEN = $data->titleEN;
        $this->titleID = $data->titleID;
        $this->descriptionID = $data->descriptionID;
        $this->descriptionEN = $data->descriptionEN;
        $this->isactive = $data->status;
        $this->uphotoEN = $data->imgEN;
        $this->uphotoID = $data->imgID;
    }

    public function manualValidation(){
        if($this->titleID == '' ){
            Toaster::error('Title Indonesia is required!');
            return;
        }elseif($this->titleEN == '' ){
            Toaster::error('Title English is required!');
            return;
        }elseif($this->descriptionEN == '' ){
            Toaster::error('Description English is required!');
            return;
        }elseif($this->descriptionID == '' ){
            Toaster::error('Description Indonesia is required!');
            return;
        }elseif($this->publishdate == '' ){
            Toaster::error('Publish date is required!');
            return;
        }
        return true;
    }

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

    protected function handlePhotoUpload($newPhoto, $existingPhoto, $uploadMethod){
        if (!$newPhoto) {
            return $existingPhoto;
        }

        Storage::delete([
            'public/files/photos/' . $existingPhoto,
            'public/files/photos/thumbnail/' . $existingPhoto
        ]);

        return $this->$uploadMethod();
    }

    public function storePosts(){
        if($this->manualValidation()){

            DB::table('infographic')
            ->where('id', $this->idInfographic)
            ->update([
                'publishdate' => $this->publishdate,
                'period' => $this->period ?: null,
                'category' => $this->category,
                'titleID' => $this->titleID,
                'titleEN' => $this->titleEN,
                'descriptionID' => $this->descriptionID,
                'descriptionEN' => $this->descriptionEN,
                'imgID' => $nameID = $this->handlePhotoUpload($this->photoID, $this->uphotoID, 'uploadImageID'),
                'imgEN' => $nameEN = $this->handlePhotoUpload($this->photoEN, $this->uphotoEN, 'uploadImageEN'),
                'status' => $this->isactive,
                'updated_at' => Carbon::now('Asia/Jakarta')
            ]);

            redirect()->to('/cms/listinfographic');
        }
    }
    public function render()
    {
        return view('livewire.edit-infographic-component');
    }
}
