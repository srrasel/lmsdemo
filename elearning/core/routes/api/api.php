<?php

use Illuminate\Support\Facades\Route;

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

Route::controller('AppController')->group(function () {
    Route::get('general-setting', 'generalSetting');
    Route::get('menus', 'menus');
    Route::get('footer-menus', 'footerMenus');
    Route::get('get-countries', 'getCountries');
    Route::get('language/{key?}', 'getLanguage');
    Route::get('policies', 'policies');
    Route::get('policy/{slug}', 'policyContent');
    Route::get('blog-details/{slug}', 'blogDetails');
    Route::get('faq', 'faq');
    Route::get('seo', 'seo');
    Route::get('get-extension/{act}', 'getExtension');
    Route::post('contact', 'submitContact');
    Route::get('cookie', 'cookie');
    Route::post('cookie/accept', 'cookieAccept');
    Route::get('custom-pages', 'customPages');
    Route::get('custom-page/{slug}', 'customPageData');
    Route::get('sections/{key?}', 'allSections');
    Route::get('ticket/{ticket}', 'viewTicket');
    Route::post('ticket/ticket-reply/{id}', 'replyTicket');
    Route::post('subscription', 'subscription');
    Route::get('categories', 'categories');
    Route::get('popular-courses', 'popularCourses');
    Route::get('top-courses', 'topCourses');
    Route::get('short-courses', 'shortCourses');
    Route::get('course-detail/{slug}', 'courseDetail');
    Route::get('deposit/methods', 'methods');
    Route::get('all-courses/{slug?}', 'allCourses');
    Route::get('instructor-profile/{username}', 'instructorProfile');
    Route::post('save-keyword', 'saveKeyword');
    Route::get('keyword', 'keyword');
    Route::get('latest-course', 'latestCourse');
    Route::get('verify-certificate/{secret}', 'verifyCertificate')->name('verify.certificate');
});

Route::controller('App\Http\Controllers\Api\DonationController')->group(function () {
    Route::post('donation/store', 'store');
});

Route::controller('BlogController')->group(function () {
    Route::get('blogs', 'blogs');
    Route::get('blog/details/{id}', 'blogDetails');
    Route::get('blog/category/{id}', 'categoryBlogs');
});

Route::controller('LibraryController')->group(function () {
    Route::get('library/categories', 'categories');
    Route::get('library/category/{id}', 'categoryBooks');
    Route::get('library/books', 'books');
    Route::get('library/book/{id}', 'bookDetails');
    Route::get('library/chapter/{id}', 'chapterDetails');
    Route::get('library/lesson/{id}', 'lessonDetails');
});

Route::controller('FaqController')->group(function () {
    Route::get('faqs', 'index');
});

@include('user.php');
@include('instructor.php');
