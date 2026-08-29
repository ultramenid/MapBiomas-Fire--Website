<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FactsheetController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\InfographicController;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\PagesController;
use App\Http\Middleware\checkSession;
use App\Http\Middleware\hasSession;
use App\Http\Middleware\setLanguage;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/en');

Route::middleware([setLanguage::class])->group(function () {
    Route::group(['prefix' => '{lang}'], function () {
        Route::get('/', [IndexController::class, 'index'])->name('index');
        Route::get('/about', [PagesController::class, 'about'])->name('about');
        Route::get('/refrencemap', [PagesController::class, 'refrencemap'])->name('refrencemap');
        Route::get('/termsofuse', [PagesController::class, 'termsofuse'])->name('termsofuse');
        Route::get('/faq', [FaqController::class, 'listfaq'])->name('faq');
        Route::get('/downloads', [PagesController::class, 'downloads'])->name('downloads');
        Route::get('/atbd', [PagesController::class, 'atbd'])->name('atbd');
        Route::get('/factsheet', [FactsheetController::class, 'listFactsheet'])->name('factsheet');
        Route::get('/news/{id}/{slug}', [NewsController::class, 'detailnews'])->name('detailnews');
        Route::get('/event/{id}/{slug}', [NewsController::class, 'detailevent'])->name('detailevent');
        Route::get('/newnevent', [NewsController::class, 'newsnevent'])->name('newsnevent');
        Route::get('/infographics', [InfographicController::class, 'listinfographic'])->name('infographics');

    });
});


//redirect to login page if user has no session
Route::middleware([checkSession::class])->group(function () {
    Route::get('/cms/dashboard', [DashboardController::class, 'index'])->name('cms.dashboard');
    Route::get('/cms/listfaq', [FaqController::class, 'index'])->name('cms.faq.index');
    Route::get('/cms/addfaq', [FaqController::class, 'add'])->name('cms.faq.create');
    Route::get('/cms/editfaq/{id}', [FaqController::class, 'edit'])->name('cms.faq.edit');
    Route::get('/cms/listnews', [NewsController::class, 'index'])->name('cms.news.index');
    Route::get('/cms/listinfographic', [InfographicController::class, 'index'])->name('cms.infographic.index');
    Route::get('/cms/addinfographic', [InfographicController::class, 'addinfographic'])->name('cms.infographic.create');
    Route::get('/cms/editinfographic/{id}', [InfographicController::class, 'edit'])->name('cms.infographic.edit');
    Route::get('/cms/addnews', [NewsController::class, 'add'])->name('cms.news.create');
    Route::get('/cms/editnews/{id}', [NewsController::class, 'edit'])->name('cms.news.edit');
    Route::get('/cms/previewnews/{id}', [NewsController::class, 'previewnews'])->name('cms.news.preview');
    Route::get('/cms/previewcardnews/{id}', [NewsController::class, 'previewcardnews'])->name('cms.news.preview-card');
    Route::get('/cms/pageabout', [PagesController::class, 'cmsabout'])->name('cms.pages.about');
    Route::get('/cms/termofuse', [PagesController::class, 'cmstermofuse'])->name('cms.pages.termofuse');
    Route::get('/cms/cmsrefrencemap', [PagesController::class, 'cmsrefrencemap'])->name('cms.pages.refrencemap');
    Route::get('/cms/cmsatbd', [PagesController::class, 'cmsatbd'])->name('cms.pages.atbd');
    Route::get('/cms/cmsdownload', [PagesController::class, 'cmsdownloads'])->name('cms.pages.downloads');
    Route::get('/cms/listfactsheet', [FactsheetController::class, 'index'])->name('cms.factsheet.index');
    Route::get('/cms/addfactsheet', [FactsheetController::class, 'add'])->name('cms.factsheet.create');
    Route::get('/cms/editfactsheet/{id}', [FactsheetController::class, 'edit'])->name('cms.factsheet.edit');

    Route::group(['prefix' => '/cms/fire-filemanager'], function () {
        \UniSharp\LaravelFilemanager\Lfm::routes();
    });

});

//redirect to dashboard if user has session to dashboard
Route::middleware([hasSession::class])->group(function () {
    Route::get('/cms/login', [DashboardController::class, 'login']);
});

//url to logout session
Route::get('/cms/logout', function () {
    session()->flush();
    return redirect('/cms/login');
});
