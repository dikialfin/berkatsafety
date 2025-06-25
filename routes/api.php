<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Authentication\AuthController;
use App\Http\Controllers\Api\V1\Masterdata\BrandsController;
use App\Http\Controllers\Api\V1\Masterdata\BroadcastController;
use App\Http\Controllers\Api\V1\Masterdata\CategoriesController;
use App\Http\Controllers\Api\V1\Masterdata\CustomerController;
use App\Http\Controllers\Api\V1\Masterdata\EmailConfigurationController;
use App\Http\Controllers\Api\V1\Masterdata\RoleController;
use App\Http\Controllers\Api\V1\Masterdata\UserController;
use App\Http\Controllers\Api\V1\Masterdata\ProductsController;
use App\Http\Controllers\Api\V1\Masterdata\EmailNotificationController;
use App\Http\Controllers\Api\V1\Masterdata\TranslationsController;
use App\Http\Controllers\Api\V1\Masterdata\SeoPageController;
use App\Http\Controllers\Api\V1\Masterdata\BlogsController;
use App\Http\Controllers\Api\V1\Masterdata\CatalogueController;
use App\Http\Controllers\Api\V1\Masterdata\AboutController;
use App\Http\Controllers\Api\V1\Masterdata\AnnouncementController;
use App\Http\Controllers\Api\V1\Masterdata\SettingController;
use App\Http\Controllers\Api\V1\Masterdata\ContactController;
use App\Http\Controllers\Api\V1\Masterdata\DashboardController;
use App\Http\Controllers\Api\V1\Masterdata\CsrController;
use App\Http\Controllers\Api\V1\Masterdata\ImageSliderController;
use App\Http\Controllers\Api\V1\User\FeatureLimitController;
use App\Http\Controllers\Api\V1\User\MeController;


/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')
    ->namespace('\App\Http\Controllers\Api\V1')
    ->group(function () {
        //authentication
        Route::middleware('throttle:5,1')->post('/login',[AuthController::class, 'login']);
        Route::middleware('throttle:5,1')->post('/register',[AuthController::class, 'register']);
        Route::middleware('throttle:5,1')->post('/generate-token',[AuthController::class, 'generateToken']);
        Route::middleware('throttle:5,1')->post('/login-google',[AuthController::class, 'loginGoogle']);

        Route::post('/forgot', [AuthController::class, 'forgot']);
        Route::post('/verify', [AuthController::class, 'verify']);
        Route::post('/reset', [AuthController::class, 'reset']);
        Route::post('/check-saas', [AuthController::class, 'checkSaas']);

        Route::get('/check-reset-token/{token}', [AuthController::class, 'resetCheck']);
        Route::get('/check-verify-token/{token}', [AuthController::class, 'verifyCheck']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('/me', [MeController::class,'index']);
            Route::get('/user-credit', [MeController::class,'userCredit']);
            Route::get('/current', [MeController::class,'current']);
            Route::get('/usages', [FeatureLimitController::class, 'getLimitAndUsage']);
            
            Route::get('/dashboard', [DashboardController::class,'index']);
            
            Route::prefix('customer')->group(function () {
                Route::get('/data', [CustomerController::class, 'data']);
                Route::middleware('role:superadmin')->get('/log', [CustomerController::class, 'log']);
                Route::get('/select', [CustomerController::class, 'select']);
                Route::get('/delete/{id}', [CustomerController::class, 'delete']);
                Route::get('/restore/{id}', [CustomerController::class, 'restore']);
                Route::get('/resend-verification/{id}', [CustomerController::class, 'resendVerification']);
                Route::middleware('xss')->get('/edit/{id}', [CustomerController::class, 'edit']);
                Route::get('/account', [CustomerController::class, 'account']);
                Route::middleware('xss')->post('/update', [CustomerController::class, 'update']);
                Route::post('/update-account', [CustomerController::class, 'updateAccount']);
                Route::post('/update-new', [CustomerController::class, 'updateNew']);
                Route::middleware('xss')->post('/add', [CustomerController::class, 'add']);
            });

            Route::prefix('translations')->group(function () {
                Route::get('/data', [TranslationsController::class, 'data']);
                Route::post('/store', [TranslationsController::class, 'store']);
                Route::get('/delete/{id}', [TranslationsController::class, 'delete']);
                Route::get('/restore/{id}', [TranslationsController::class, 'restore']);
                Route::middleware('xss')->get('/edit/{id}', [TranslationsController::class, 'edit']);
                Route::middleware('xss')->post('/update', [TranslationsController::class, 'update']);
                Route::middleware('xss')->post('/add', [TranslationsController::class, 'add']);
            });

            Route::prefix('seo-page-setting')->group(function () {
                Route::get('/data', [SeoPageController::class, 'data']);
                Route::post('/store', [SeoPageController::class, 'store']);
            });

            Route::prefix('products')->group(function () {
                Route::get('/data', [ProductsController::class, 'data']);
                Route::post('/add', [ProductsController::class, 'add']);
                Route::get('/edit/{id}', [ProductsController::class, 'edit']);
                Route::get('/delete/{id}', [ProductsController::class, 'delete']);
                Route::get('/restore/{id}', [ProductsController::class, 'restore']);
            });

            Route::prefix('blogs')->group(function () {
                Route::get('/data', [BlogsController::class, 'data']);
                Route::post('/add', [BlogsController::class, 'add']);
                Route::get('/edit/{id}', [BlogsController::class, 'edit']);
                Route::get('/delete/{id}', [BlogsController::class, 'delete']);
                Route::get('/restore/{id}', [BlogsController::class, 'restore']);
            });

            Route::prefix('catalogue')->group(function () {
                Route::get('/data', [CatalogueController::class, 'data']);
                Route::post('/add', [CatalogueController::class, 'add']);
                Route::get('/edit/{id}', [CatalogueController::class, 'edit']);
                Route::get('/delete/{id}', [CatalogueController::class, 'delete']);
                Route::get('/restore/{id}', [CatalogueController::class, 'restore']);
            });

            Route::prefix('about')->group(function () {
                Route::get('/data', [AboutController::class, 'data']);
                Route::post('/add', [AboutController::class, 'add']);
                Route::get('/edit/{id}', [AboutController::class, 'edit']);
            });
            Route::prefix('contact')->group(function () {
                Route::get('/data', [ContactController::class, 'data']);
            });

            Route::prefix('csr')->group(function () {
                Route::get('/data', [CsrController::class, 'data']);
                Route::post('/add', [CsrController::class, 'add']);
                Route::get('/edit/{id}', [CsrController::class, 'edit']);
                Route::get('/delete/{id}', [CsrController::class, 'delete']);
                Route::get('/restore/{id}', [CsrController::class, 'restore']);
            });

            Route::prefix('brands')->group(function () {
                Route::get('/data', [BrandsController::class, 'data']);
                Route::get('/select', [BrandsController::class, 'select']);
                Route::get('/delete/{id}', [BrandsController::class, 'delete']);
                Route::get('/restore/{id}', [BrandsController::class, 'restore']);
                Route::middleware('xss')->get('/edit/{id}', [BrandsController::class, 'edit']);
                Route::middleware('xss')->post('/update', [BrandsController::class, 'update']);
                Route::middleware('xss')->post('/add', [BrandsController::class, 'add']);
            });

            Route::prefix('setting')->group(function () {
                Route::get('/', [SettingController::class, 'show']);
                Route::post('/', [SettingController::class, 'add']);
            });

            Route::prefix('categories')->group(function () {
                Route::get('/data', [CategoriesController::class, 'data']);
                Route::get('/select/{id?}', [CategoriesController::class, 'select']);
                Route::get('/select-parent-children', [CategoriesController::class, 'selectParentChildren']);
                Route::get('/delete/{id}', [CategoriesController::class, 'delete']);
                Route::get('/restore/{id}', [CategoriesController::class, 'restore']);
                Route::middleware('xss')->get('/edit/{id}', [CategoriesController::class, 'edit']);
                Route::middleware('xss')->post('/update', [CategoriesController::class, 'update']);
                Route::middleware('xss')->post('/add', [CategoriesController::class, 'add']);
            });

            Route::prefix('user')->group(function () {
                Route::get('/data', [UserController::class, 'data']);
                Route::middleware('role:superadmin')->get('/log', [UserController::class, 'log']);
                Route::get('/select', [UserController::class, 'select']);
                Route::get('/delete/{id}', [UserController::class, 'delete']);
                Route::get('/restore/{id}', [UserController::class, 'restore']);
                Route::get('/resend-verification/{id}', [UserController::class, 'resendVerification']);
                Route::middleware('xss')->get('/edit/{id}', [UserController::class, 'edit']);
                Route::get('/account', [UserController::class, 'account']);
                Route::middleware('xss')->post('/update', [UserController::class, 'update']);
                Route::post('/update-account', [UserController::class, 'updateAccount']);
                Route::post('/update-new', [UserController::class, 'updateNew']);
                Route::middleware('xss')->post('/add', [UserController::class, 'add']);
            });

            Route::prefix('role')->group(function () {
                Route::middleware('permission:role.view')->get('/data', [RoleController::class, 'data']);
                Route::get('/permission', [RoleController::class, 'permission']);
                Route::get('/select', [RoleController::class, 'select']);
                Route::middleware('permission:role.delete')->get('/delete/{id}', [RoleController::class, 'delete']);
                Route::middleware('permission:role.delete')->get('/restore/{id}', [RoleController::class, 'restore']);
                Route::middleware('permission:role.update')->get('/edit/{id}', [RoleController::class, 'edit']);
                Route::middleware('permission:role.update')->post('/update', [RoleController::class, 'update']);
                Route::middleware('permission:role.create')->post('/add', [RoleController::class, 'add']);
            });

            Route::prefix('broadcast')->group(function () {
                Route::middleware('role:superadmin')->get('/data', [BroadcastController::class, 'data']);
                Route::middleware('role:superadmin')->get('/user', [BroadcastController::class, 'user']);
                Route::get('/notification', [BroadcastController::class, 'notification']);
                Route::middleware('role:superadmin')->get('/delete/{id}', [BroadcastController::class, 'delete']);
                Route::middleware('role:superadmin')->get('/restore/{id}', [BroadcastController::class, 'restore']);
                Route::middleware('role:superadmin')->get('/edit/{id}', [BroadcastController::class, 'edit']);
                Route::middleware('role:superadmin')->post('/update', [BroadcastController::class, 'update']);
                Route::middleware('role:superadmin')->post('/add', [BroadcastController::class, 'add']);
            });

            Route::prefix('email-notification')->group(function () {
                Route::middleware('role:superadmin')->get('/data', [EmailNotificationController::class, 'data']);
                Route::middleware('role:superadmin')->get('/user', [EmailNotificationController::class, 'user']);
                Route::get('/permission', [EmailNotificationController::class, 'permission']);
                Route::get('/select', [EmailNotificationController::class, 'select']);
                Route::middleware('role:superadmin')->get('/delete/{id}', [EmailNotificationController::class, 'delete']);
                Route::middleware('role:superadmin')->get('/restore/{id}', [EmailNotificationController::class, 'restore']);
                Route::middleware('role:superadmin')->get('/edit/{id}', [EmailNotificationController::class, 'edit']);
                Route::middleware('role:superadmin')->post('/update', [EmailNotificationController::class, 'update']);
                Route::middleware('role:superadmin')->post('/add', [EmailNotificationController::class, 'add']);
                Route::middleware('role:superadmin')->post('/preview', [EmailNotificationController::class, 'preview']);
            });

            Route::prefix('email-configuration')->group(function () {
                Route::middleware('role:superadmin')->get('/data', [EmailConfigurationController::class, 'data']);
                Route::middleware('role:superadmin')->post('/add', [EmailConfigurationController::class, 'add']);
            });

            Route::prefix('image_slider')->group(function () {
                // Route::get('/data', [ImageSliderController::class, 'data']);
                Route::post('/add', [ImageSliderController::class, 'add']);
                Route::get('/edit', [ImageSliderController::class, 'edit']);
                // Route::get('/delete/{id}', [ImageSliderController::class, 'delete']);
                // Route::get('/restore/{id}', [ImageSliderController::class, 'restore']);
            });

            Route::prefix('announcement')->group(function () {
                Route::get('/data', [AnnouncementController::class, 'data']);
                Route::post('/add', [AnnouncementController::class, 'add']);
                Route::get('/edit/{id}', [AnnouncementController::class, 'edit']);
                Route::get('/delete/{id}', [AnnouncementController::class, 'delete']);
                Route::get('/restore/{id}', [AnnouncementController::class, 'restore']);
            });
        });
    });

