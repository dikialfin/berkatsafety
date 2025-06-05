<?php

use App\Http\Controllers\{HomeController, CategoryController, BrandsController, CatalogueController, MediaController, ProductsController, AboutController, ContactUsController};
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['prefix' => 'media', 'middleware' => ['web']], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

Route::get('/', function () {
    return redirect('/en');
});

Route::get('/create-symlink', function () {
    return symlink('/home/t70337/landing.berkatsafety.co.id/storage/app/public', '/home/t70337/public_html/storage')
        ? 'Symlink created'
        : 'Failed to create symlink';
});

Route::group(['prefix' => '{lang}', 'middleware' => 'setLocale', 'where' => ['lang' => 'en|id']], function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::get('/category/{slug}', [CategoryController::class, 'detail'])->name('category-details');
    Route::get('/category', [CategoryController::class, 'index'])->name('category');

    
    Route::get('products/{slug}', [ProductsController::class, 'detail'])->name('products');

    Route::get('/catalogue/{slug}', [CatalogueController::class, 'detail'])->name('catalogue.detail');
    Route::get('/catalogue-brands/{slug}', [CatalogueController::class, 'index'])->name('catalogue.brands');
    Route::get('/catalogue', [CatalogueController::class, 'index'])->name('catalogue');

    Route::get('/brands/{slug}', [BrandsController::class, 'index'])->name('brands.detail');
    Route::get('/brands', [BrandsController::class, 'index'])->name('brands');

    Route::get('/media/{slug}', [MediaController::class, 'detail'])->name('media.detail');
    Route::get('/media', [MediaController::class, 'index'])->name('media');

    Route::get('/about-us/csr/{slug}', [AboutController::class, 'detailCsr'])->name('about.csr');
    Route::get('/about-us/{slug?}', [AboutController::class, 'index'])->name('about.detail');
    Route::get('/about-us', [AboutController::class, 'index'])->name('about');

    Route::get('/contact-us', [ContactUsController::class, 'index'])->name('contact-us');
    
});

Route::post('/contacts', [ContactUsController::class, 'store'])->name('contacts.store');

Route::post('/product-fliter', [HomeController::class,'productFliter']);

Route::get('/app/{any}', fn () => view('app'))->where('any', '^((?!api).)*');

Route::get('{any}', fn () => view('app'))->where('any', '^((?!api).)*');
