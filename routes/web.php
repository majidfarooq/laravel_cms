<?php

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


use App\Http\Controllers\backend\{AdminController,
    CategoryController,
    ElementController,
    MediaController,
    MenuController,
    PageController,
    PostController,
    TagController,
    UserController
};

use App\Http\Controllers\frontend\{
    PageController as
    FrontPageController,
    PostController as FrontPostController,
    ServiceController
};

Route::get('/', [FrontPageController::class, 'home'])->name('home');
Route::get('/category/{slug}', [FrontPostController::class, 'showCategory'])->name('category.show');
Route::get('/tag/{slug}', [FrontPostController::class, 'showTag'])->name('tag.show');
Route::get('/blog/{slug}', [FrontPostController::class, 'show'])->name('blog.show');
Route::get('/blog', [FrontPostController::class, 'index'])->name('blogs.index');
Route::get('/author/{name}', [FrontPostController::class, 'author'])->name('author.index');
Route::get('/search/', [FrontPageController::class, 'search'])->name('search.index');
Route::get('/sitemap.xml', [App\Http\Controllers\frontend\SitemapController::class, 'index']);

Route::get('register', [App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [App\Http\Controllers\Auth\RegisterController::class, 'register']);
Route::post('login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::get('login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::prefix('/password')->as('password.')->group(function () {
//    Route::post('/email', [App\Http\Controllers\frontend\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('email');
//    Route::get('/reset/{token}', [App\Http\Controllers\frontend\ForgotPasswordController::class, 'showResetForm'])->name('reset');
//    Route::post('/reset', [App\Http\Controllers\frontend\ForgotPasswordController::class, 'reset'])->name('update');
//    Route::get('/reset/', [App\Http\Controllers\frontend\ForgotPasswordController::class, 'showLinkRequestForm'])->name('request');
});

Route::group(['middleware' => 'auth'], function () {
  Route::post('logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
  Route::group(['middleware' => 'role:user', 'prefix' => 'user', 'as' => 'user.'], function () {
    Route::get('/', [App\Http\Controllers\Frontend\UserController::class, 'dashboard'])->name('dashboard');
    Route::post('/profile/update', [App\Http\Controllers\Frontend\UserController::class, 'update'])->name('profile.update');
    Route::post('/password/update', [App\Http\Controllers\Frontend\UserController::class, 'password'])->name('password.update');
  });
});
Route::group(['prefix' => 'admin',
//    'as' => 'admin.'
//    'namespace' => 'backend'
], function () {
    Route::group(['middleware' => 'admin.guest'], function () {
        Route::view('login', 'backend.login.login')->name('admin.login');
        Route::post('login', [AdminController::class, 'login'])->name('admin.auth');
    });
    Route::group(['middleware' => 'admin.auth'], function () {

        Route::view('dashboard', 'backend.dashboard.dashboard')->name('admin.home');
        Route::view('/', 'backend.dashboard.dashboard');

        Route::get('/account', [AdminController::class, 'account'])->name('account.settings');
        Route::post('/change/information', [AdminController::class, 'basicInformation'])->name('account.information');
        Route::post('/change/password', [AdminController::class, 'changePassword'])->name('account.password');
        Route::post('logout', [AdminController::class, 'logout'])->name('admin.logout');

        Route::get('medias', [MediaController::class, 'index'])->name('media.index');

        Route::any('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/show/{var?}', [UserController::class, 'show'])->name('users.show');
        Route::delete('users/delete/{var?}', [UserController::class, 'destroy'])->name('users.destroy');
        Route::post('users/{id}/restore', [UserController::class, 'restore'])->name('user.restore');

//  Categories
        Route::any('categories/', [CategoryController::class, 'index'])->name('admin.categories.index');
        Route::get('categories/create', [CategoryController::class, 'create'])->name('admin.categories.create');
        Route::post('categories/store', [CategoryController::class, 'store'])->name('admin.categories.store');
        Route::post('categories/{slug}/edit', [CategoryController::class, 'edit'])->name('admin.categories.edit');
        Route::post('categories/{slug}/update', [CategoryController::class, 'update'])->name('admin.categories.update');

//  Tags
        Route::any('tags', [TagController::class, 'index'])->name('admin.tags.index');
        Route::get('tag/create', [TagController::class, 'create'])->name('admin.tag.create');
        Route::post('tag/store', [TagController::class, 'store'])->name('admin.tag.store');
        Route::post('tag/{slug}/edit', [TagController::class, 'edit'])->name('admin.tag.edit');
        Route::post('tag/{slug}/update', [TagController::class, 'update'])->name('admin.tag.update');

        Route::any('posts/', [PostController::class, 'index'])->name('admin.posts.index');
        Route::get('post/create', [PostController::class, 'create'])->name('admin.post.create');
        Route::post('post/store', [PostController::class, 'store'])->name('admin.post.store');
        Route::post('post/{slug}/edit', [PostController::class, 'edit'])->name('admin.post.edit');
        Route::post('post/update', [PostController::class, 'update'])->name('admin.post.update');
        Route::delete('post/{id}/delete', [PostController::class, 'destroy'])->name('admin.post.destroy');
        Route::post('post/{id}/restore', [PostController::class, 'restore'])->name('admin.post.restore');

        Route::get('menu/show/{menu:slug}', [MenuController::class, 'show'])->name('menu.show');
        Route::post('menu/store', [MenuController::class, 'store'])->name('menu.store');
        Route::post('menu/list', [MenuController::class, 'listMenu'])->name('listMenu');
        Route::post('menu/delete', [MenuController::class, 'delete'])->name('menu.delete');

        Route::any('pages', [PageController::class, 'index'])->name('pages.index');
        Route::get('page/create', [PageController::class, 'create'])->name('page.create');
        Route::post('page/edit/{page:slug}', [PageController::class, 'create'])->name('page.edit');
        Route::put('page/update/{page}', [PageController::class, 'update'])->name('page.update');
        Route::delete('page/{id}/disable', [PageController::class, 'disable'])->name('page.disable');
        Route::delete('page/{id}/destroy', [PageController::class, 'destroy'])->name('page.destroy');
        Route::post('page/{id}/restore', [PageController::class, 'restore'])->name('page.restore');

        Route::post('getChildPageElement', [PageController::class, 'getChildPageElement'])->name('getChildPageElement');
        Route::post('getElement', [PageController::class, 'getElement'])->name('getElement');
        Route::post('addSection', [PageController::class, 'addSection'])->name('addSection');

        Route::get('page/edit/element/{id?}', [PageController::class, 'editInnerElement'])->name('editInnerElement');
        Route::post('InnerElement/store', [PageController::class, 'storeInnerElement'])->name('storeInnerElement');
        Route::post('storeChildPe', [PageController::class, 'storeChildPe'])->name('storeChildPe');
        Route::post('InnerElement/update', [PageController::class, 'updateInnerElement'])->name('updateInnerElement');
        Route::post('getPageSections', [PageController::class, 'getPageSections'])->name('getPageSections');
        Route::post('section/order', [PageController::class, 'sectionOrder'])->name('parent.order');
        Route::post('section/delete', [PageController::class, 'deleteSection'])->name('delete.section');
        Route::post('change/selector', [PageController::class, 'changeSelector'])->name('changeSelector');
        Route::post('elements/delete', [PageController::class, 'deleteElements'])->name('element.delete');

        Route::any('elements', [ElementController::class, 'index'])->name('elements.index');
        Route::get('element/create', [ElementController::class, 'create'])->name('element.create');
        Route::post('element/store', [ElementController::class, 'store'])->name('element.store');
        Route::get('element/edit/{element:id}', [ElementController::class, 'edit'])->name('element.edit');
        Route::post('element/update', [ElementController::class, 'update'])->name('element.update');
        Route::post('elementDelete', [ElementController::class, 'delete'])->name('elementDelete');

        Route::post('field/create', [ElementController::class, 'fieldCreate'])->name('field.create');
        Route::post('field/get', [ElementController::class, 'fieldGet'])->name('field.get');
        Route::post('field/update', [ElementController::class, 'fieldUpdate'])->name('field.update');
        Route::post('field/delete', [ElementController::class, 'fieldDelete'])->name('field.delete');
    });
});

Route::get('/clear-cache', function () {
    $exitCode = Artisan::call('config:clear');
    $exitCode = Artisan::call('cache:clear');
    $exitCode = Artisan::call('config:cache');
    return 'DONE';
});
Route::get('/cli/migrate', function () {
    Artisan::call('migrate');
});
Route::get('/cli/config', function () {
    Artisan::call('config:cache');
});
Route::get('/cli/con', function () {
    Artisan::call('cache:clear');
});
Route::get('/cli/seed', function () {
    Artisan::call('db:seed');
});
Route::get('/cli/view', function () {
    Artisan::call('view:clear');
});
Route::get('/cli/queue', function () {
    Artisan::call('queue:work');
});
Route::get('/cli/sto', function () {
    Artisan::call('storage:link');
});
Route::get('/cli/route', function () {
    Artisan::call('route:clear');
});
Route::get('/cli/cache', function () {
    Artisan::call('route:cache');
});
Route::get('/cli/bread', function () {
    Artisan::call('vendor:publish', '--provider="DaveJamesMiller\Breadcrumbs\BreadcrumbsServiceProvider"');
});

Route::get('/{page:slug}', [FrontPageController::class, 'show'])->name('page.show');
