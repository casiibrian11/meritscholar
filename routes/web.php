<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers;

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/unauthorized', [Controllers\AccountsController::class, 'unauthorized']);
Route::post('/verify-email', [Controllers\AccountsController::class, 'verifyEmail'])->name('verify-email');

Auth::routes();
Route::get('/page/announcements', [Controllers\AnnouncementsController::class, 'announcements']);

Route::middleware(['auth', 'active'])->group(function () {
    Route::get('/send-verification', [Controllers\HomeController::class, 'sendVerification']);
    Route::get('/verification', [Controllers\HomeController::class, 'verification']);
    Route::post('/verify', [Controllers\HomeController::class, 'verify'])->name('verify');

    Route::middleware(['verified'])->group(function () {
        Route::get('/home', [Controllers\HomeController::class, 'index'])->name('home');
        
        Route::middleware(['student'])->group(function () {
            Route::post('/page/scholarships-table', [Controllers\ScholarshipsController::class, 'table'])->name('scholarships-table');

            Route::get('/profile', [Controllers\AccountsController::class, 'index']);
            Route::get('/account', [Controllers\AccountsController::class, 'account']);
            Route::post('/account/update', [Controllers\AccountsController::class, 'update'])->name('account-update');
            Route::get('/personal-information', [Controllers\AccountsController::class, 'info']);
            Route::get('/address', [Controllers\AccountsController::class, 'address']);
            Route::get('/additional-information', [Controllers\AccountsController::class, 'additionalInformation']);
            Route::get('/scholarships/list', [Controllers\ScholarshipsController::class, 'selectSchoolYear']);
            Route::get('/scholarships/view/{sy}/list', [Controllers\ScholarshipsController::class, 'scholarships']);
            Route::get('/scholarships/{sy}/application', [Controllers\ScholarshipsController::class, 'application']);
            Route::get('/scholarships/{sy}/application/{application}/requirements', [Controllers\ScholarshipsController::class, 'requirements']);
            Route::post('/scholarships/requirements/store', [Controllers\ScholarshipsController::class, 'storeRequirements'])->name('requirements-submit');

            Route::post('/scholarships/application/save', [Controllers\ScholarshipsController::class, 'applicationSave'])->name('application-save');
            Route::post('/scholarships/application/store', [Controllers\ScholarshipsController::class, 'store'])->name('application-store');

            Route::post('/scholarships/store', [Controllers\ScholarshipsController::class, 'store'])->name('scholarship-store');
            
            Route::get('/attachment/{requirement}/remove', [Controllers\ScholarshipsController::class, 'removeAttachment']);
            Route::post('/scholarships/application/complete', [Controllers\ScholarshipsController::class, 'completeApplication'])->name('complete-application');
            
            Route::match(['get', 'post'], '/personal-information/save', [Controllers\AccountsController::class, 'save'])->name('personal-information-save');

            Route::get('/scholarships/{sy}/application/{application}/withdraw', [Controllers\ScholarshipsController::class, 'withdraw']);
        });

        // Admin | Support | Director routes
        Route::middleware(['admin'])->group(function () {

            Route::get('/dashboard', [Controllers\DashboardController::class, 'index']);
            Route::get('/scholarship/applications/list', [Controllers\ApplicationsController::class, 'masterlist']);

            Route::group(['middleware' => ['support'], 'prefix' => 'emails'], function () {
                Route::get('/', [Controllers\EmailsController::class, 'emailReport']);
            });

            Route::group(['middleware' => ['support'], 'prefix' => 'announcements'], function () {
                Route::get('/', [Controllers\AnnouncementsController::class, 'index']);
                Route::get('/manage', [Controllers\AnnouncementsController::class, 'manage']);
                Route::post('/save', [Controllers\AnnouncementsController::class, 'save'])->name('announcements-save');
                Route::post('/delete', [Controllers\AnnouncementsController::class, 'delete'])->name('announcements-delete');
                Route::get('/{announcement}/visibility', [Controllers\AnnouncementsController::class, 'visibility']);
            });
            
            
            // Admin only Routes            
            Route::group(['middleware' => ['adminOnly'], 'prefix' => 'users'], function () {
                Route::get('/', [Controllers\UsersController::class, 'index']);
                Route::get('/{user}/delete', [Controllers\UsersController::class, 'delete']);
                Route::get('/{user}/update', [Controllers\UsersController::class, 'update']);
                Route::get('/{user}/verification', [Controllers\UsersController::class, 'verification']);
                Route::get('/{user}/user_type', [Controllers\UsersController::class, 'updateUserType']);
            });
          
            Route::group(['middleware' => ['adminOnly'], 'prefix' => 'colleges'], function () {
                Route::get('/', [Controllers\CollegesController::class, 'index'])->name('colleges');
                Route::post('/save', [Controllers\CollegesController::class, 'save'])->name('colleges-save');
                Route::post('/delete', [Controllers\CollegesController::class, 'delete'])->name('colleges-delete');
                Route::get('/{college}/visibility', [Controllers\CollegesController::class, 'visibility']);
                Route::get('/{id}/restore', [Controllers\CollegesController::class, 'restore']);
            });

            Route::group(['middleware' => ['adminOnly'], 'prefix' => 'courses'], function () {
                Route::get('/', [Controllers\CoursesController::class, 'index'])->name('courses');
                Route::post('/save', [Controllers\CoursesController::class, 'save'])->name('courses-save');
                Route::post('/delete', [Controllers\CoursesController::class, 'delete'])->name('courses-delete');
                Route::get('/{course}/visibility', [Controllers\CoursesController::class, 'visibility']);
                Route::get('/{id}/restore', [Controllers\CoursesController::class, 'restore']);

                Route::post('/load', [Controllers\CoursesController::class, 'load'])->name('courses-load');
            });

            Route::group(['middleware' => ['adminOnly'], 'prefix' => 'sy'], function () {
                Route::get('/', [Controllers\SchoolYearsController::class, 'index'])->name('sy');
                Route::post('/save', [Controllers\SchoolYearsController::class, 'save'])->name('sy-save');
                Route::post('/delete', [Controllers\SchoolYearsController::class, 'delete'])->name('sy-delete');
                Route::get('/{sy}/visibility', [Controllers\SchoolYearsController::class, 'visibility']);
                Route::get('/{id}/restore', [Controllers\SchoolYearsController::class, 'restore']);
            });

            Route::group(['middleware' => ['adminOnly'], 'prefix' => 'scholarships'], function () {
                Route::get('/', [Controllers\ScholarshipsController::class, 'index']);
                Route::post('/save', [Controllers\ScholarshipsController::class, 'save'])->name('scholarships-save');
                Route::post('/delete', [Controllers\ScholarshipsController::class, 'delete'])->name('scholarships-delete');
                Route::get('/{scholarship}/visibility', [Controllers\ScholarshipsController::class, 'visibility']);
                Route::get('/{id}/restore', [Controllers\ScholarshipsController::class, 'restore']);

                Route::post('/category/form', [Controllers\CategoriesController::class, 'form'])->name('category-form');
                Route::post('/category/save', [Controllers\CategoriesController::class, 'save'])->name('category-save');
                Route::post('/category/delete', [Controllers\CategoriesController::class, 'delete'])->name('category-delete');
                Route::post('/category/sort', [Controllers\CategoriesController::class, 'sort'])->name('category-sort');
                Route::post('/category/list', [Controllers\CategoriesController::class, 'list'])->name('scholarships-list');
                Route::post('/sort', [Controllers\CategoriesController::class, 'scholarshipSort'])->name('scholarships-sort');
            });

            Route::group(['middleware' => ['adminOnly'], 'prefix' => 'requirements'], function () {
                Route::get('/', [Controllers\RequirementsController::class, 'index'])->name('requirements');
                Route::post('/save', [Controllers\RequirementsController::class, 'save'])->name('requirements-save');
                Route::post('/delete', [Controllers\RequirementsController::class, 'delete'])->name('requirements-delete');
                Route::get('/{requirement}/visibility', [Controllers\RequirementsController::class, 'visibility']);
                Route::get('/{id}/restore', [Controllers\RequirementsController::class, 'restore']);

                Route::get('/manage', [Controllers\RequirementsController::class, 'manage']);
                Route::post('/file-type', [Controllers\RequirementsController::class, 'loadFileTypes'])->name('file-types');
            });

            Route::group(['middleware' => ['adminOnly'], 'prefix' => 'manage-scholarships'], function () {
                Route::get('/', [Controllers\ManageScholarshipsController::class, 'manage'])->name('scholarships');
                Route::post('/save', [Controllers\ManageScholarshipsController::class, 'save'])->name('manage-scholarships-save');
                Route::post('/delete', [Controllers\ManageScholarshipsController::class, 'delete'])->name('manage-scholarships-delete');
                Route::get('/{scholarship}/visibility', [Controllers\ManageScholarshipsController::class, 'visibility']);
                Route::get('/{id}/restore', [Controllers\ManageScholarshipsController::class, 'restore']);

                Route::get('/manage', [Controllers\ManageScholarshipsController::class, 'manage']);
                Route::post('/load-scholarships', [Controllers\ManageScholarshipsController::class, 'loadScholarships'])->name('load-scholarships');
            });

            Route::group(['middleware' => ['support'], 'prefix' => 'scholarship/applications'], function () {
                Route::get('/', [Controllers\ApplicationsController::class, 'index']);
                Route::get('/{application}/requirements', [Controllers\ApplicationsController::class, 'requirements']);
                Route::post('/requirements/update', [Controllers\ApplicationsController::class, 'update'])->name('update-status');
                Route::post('/requirements/save-note', [Controllers\ApplicationsController::class, 'saveNote'])->name('save-note');
                Route::get('/{note}/delete-note', [Controllers\ApplicationsController::class, 'deleteNote']);
                Route::get('/{requirement}/request-to-change/{application}', [Controllers\ApplicationsController::class, 'requestToChange']);
                Route::get('/{application}/update-status', [Controllers\ApplicationsController::class, 'updateStatus']);
                Route::get('/{requirement}/requirements/download', [Controllers\ApplicationsController::class, 'download']);

                Route::post('/new-applications', [Controllers\ApplicationsController::class, 'newApplications'])->name('new-applications');
            });

            Route::group(['middleware' => ['support'], 'prefix' => 'pdf'], function () {
                Route::get('/scholarship/applications', [Controllers\ApplicationsController::class, 'toPdf']);
            });

            Route::group(['middleware' => ['support'], 'prefix' => 'excel'], function () {
                Route::get('/scholarship/applications', [Controllers\ApplicationsController::class, 'toExcel']);
            });

            Route::group(['middleware' => ['adminOnly'], 'prefix' => 'settings'], function () {
                Route::get('/', [Controllers\SettingsController::class, 'index']);
                Route::post('/save', [Controllers\SettingsController::class, 'save'])->name('save-settings');
            });
        });
    });
});
