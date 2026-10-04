<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{
    public function cmshighlights(){
        $title = 'MapBiomas Fire - Highlights';
        $nav = 'pages';
        return view('backends.highlights', compact('title', 'nav'));
    }

    public function cmsabout(){
        $title = 'MapBiomas Fire - About';
        $nav = 'pages';
        return view('backends.about', compact('title', 'nav'));
    }

    public function cmstermofuse(){
        $title = 'MapBiomas Fire - Term of use';
        $nav = 'pages';
        return view('backends.termofuse', compact('title', 'nav'));
    }

    public function cmsatbd(){
        $title = 'MapBiomas Fire - ATBD';
        $nav = 'pages';
        return view('backends.atbd', compact('title', 'nav'));
    }

    public function cmsrefrencemap(){
        $title = 'MapBiomas Fire - Refrence Map';
        $nav = 'pages';
        return view('backends.refrencemap', compact('title', 'nav'));
    }

    public function cmsdownloads(){
        $title = 'MapBiomas Fire - Downloads';
        $nav = 'pages';
        return view('backends.downloads', compact('title', 'nav'));
    }

    public function getSelect(){
        if (App::getLocale() == 'id') {
            return 'id, contentID as content';
        }else{
            return 'id, contentEN as content';
        }
    }

    public function getRefrenceMap(){
        return DB::table('pagerefrencemap')
                ->selectRaw($this->getSelect())
                ->where('id', 1)
                ->first();
    }
     public function getDownloads(){
        return DB::table('pagedownload')
                ->selectRaw($this->getSelect())
                ->where('id', 1)
                ->first();
    }

    public function getTermofuse(){
        return DB::table('pagetermofuse')
                ->selectRaw($this->getSelect())
                ->where('id', 1)
                ->first();
    }

    public function getatbd($category = 'annual'){
        return DB::table('pageatbd')
                ->selectRaw($this->getSelect())
                ->where('category', $category)
                ->first();
    }

    public function getPageData(){
        return DB::table('pageabout')
                ->selectRaw($this->getSelect())
                ->where('id', 1)
                ->first();
    }

    public function about(){
        $title = 'MapBiomas Fire - About';
        $description = "Inisiatif MapBiomas Fire dimulai sejak 2023, bersama sembilan jaringan organisasi masyarakat sipil (CSO) yang dikoordinasi oleh Auriga Nusantara dan Woods and Wayside International (WWI). MapBiomas Fire memetakan kebakaran menggunakan teknologi komputasi yang didukung algoritma machine learning dan deep learning.";
        $data = $this->getPageData();
        return view('frontends.about', compact('description', 'title', 'data'));
    }

    public function refrencemap(){
        $title = 'MapBiomas Fire - Refrence Map';
        $description = "Inisiatif MapBiomas Fire dimulai sejak 2023, bersama sembilan jaringan organisasi masyarakat sipil (CSO) yang dikoordinasi oleh Auriga Nusantara dan Woods and Wayside International (WWI). MapBiomas Fire memetakan kebakaran menggunakan teknologi komputasi yang didukung algoritma machine learning dan deep learning.";
        $data = $this->getRefrenceMap();
        return view('frontends.refrencemap', compact('title', 'description', 'data'));
    }

    public function termsofuse(){
        $title = 'MapBiomas Fire - Termsofuse';
        $description = "Inisiatif MapBiomas Fire dimulai sejak 2023, bersama sembilan jaringan organisasi masyarakat sipil (CSO) yang dikoordinasi oleh Auriga Nusantara dan Woods and Wayside International (WWI). MapBiomas Fire memetakan kebakaran menggunakan teknologi komputasi yang didukung algoritma machine learning dan deep learning.";
        $data = $this->getTermofuse();
        return view('frontends.termsofuse', compact('title', 'description', 'data'));
    }

    public function downloads(){
        $title = 'MapBiomas Fire - Downloads';
        $description = "Inisiatif MapBiomas Fire dimulai sejak 2023, bersama sembilan jaringan organisasi masyarakat sipil (CSO) yang dikoordinasi oleh Auriga Nusantara dan Woods and Wayside International (WWI). MapBiomas Fire memetakan kebakaran menggunakan teknologi komputasi yang didukung algoritma machine learning dan deep learning.";
         $data = $this->getDownloads();
        return view('frontends.downloads', compact('title','description', 'data'));
    }

    public function atbd(Request $request){
        $cat = $request->query('cat');
        $category = in_array($cat, ['annual', 'monthly']) ? $cat : 'annual';

        $title = 'MapBiomas Fire - ATBD '.$category;
        $data = $this->getatbd($category);
        $description = "Inisiatif MapBiomas Fire dimulai sejak 2023, bersama sembilan jaringan organisasi masyarakat sipil (CSO) yang dikoordinasi oleh Auriga Nusantara dan Woods and Wayside International (WWI). MapBiomas Fire memetakan kebakaran menggunakan teknologi komputasi yang didukung algoritma machine learning dan deep learning.";
        return view('frontends.atbd', compact('title','description', 'data', 'category'));
    }

}
