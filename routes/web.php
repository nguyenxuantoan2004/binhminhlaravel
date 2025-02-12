<?php

use App\Http\Controllers\Admintractor\AdminBranchController;
use App\Http\Controllers\Admintractor\AdminCustomerController;
use App\Http\Controllers\Admintractor\AdminDashboardController;
use App\Http\Controllers\Admintractor\AdminEmployeeController;
use App\Http\Controllers\Admintractor\AdminLoginController;
use App\Http\Controllers\Admintractor\AdminMotorbikeCategoryController;
use App\Http\Controllers\Admintractor\AdminMotorbikeController;
use App\Http\Controllers\Admintractor\AdminOrderController;
use App\Http\Controllers\Admintractor\AdminPageController;
use App\Http\Controllers\Admintractor\AdminPositionController;
use App\Http\Controllers\Admintractor\AdminPostController;
use App\Http\Controllers\Admintractor\AdminSupplierController;
use App\Http\Controllers\Admintractor\UserAccountController;
use App\Http\Controllers\AppController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\MotorbikeController;
use App\Http\Controllers\User\PostController;
use App\Http\Controllers\User\ProfileController;
use App\Http\Middleware\CheckCustomerAuth;
use App\Http\Middleware\CheckEmployeeAuth;
use App\Models\Customer;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

Route::get('/', [HomeController::class, "index"])->name("home");

// Route::get("/", function () {
//     return view("frontend.home");
// })->name("home");

// ================================================================================================================

Route::get("/danh-sach-xe", [MotorbikeController::class, "index"])->name("home.motorbike.index");

Route::get("/danh-sach-xe/{slug}.html", [MotorbikeController::class, "index"])->name("home.motorbike.category");

Route::get("/danh-sach-xe/{slugCategory}/{slugXe}/{id}.html", [MotorbikeController::class, "detail"])->name("home.motorbike.detail");

// ================================================================================================================

Route::get("/meo-du-lich", [PostController::class, "index"])->name("home.post.index");

Route::get("/meo-du-lich/{slug}/{id}.html", [PostController::class, "show"])->name("home.post.detail");

// ================================================================================================================


Route::middleware(CheckCustomerAuth::class)->group(function () {
    Route::get("/thong-tin-ca-nhan", [ProfileController::class, "showProfileForm"])->name("home.profile.show");
    Route::post("/thong-tin-ca-nhan", [ProfileController::class, "profile"])->name("home.profile");

    Route::get("/doi-mat-khau", [ProfileController::class, "showChangePasswordForm"])->name("home.pass.show");
    Route::post("/doi-mat-khau", [ProfileController::class, "changePassword"])->name("home.pass");

    Route::get("/don-dat-xe", [ProfileController::class, "showOrderForm"])->name("home.order.show");

    Route::get("/don-dat-xe/chi-tiet/{id}", [ProfileController::class, "showOrderDetailForm"])->name("home.orderDetail.show");


    Route::post("/don-dat-xe/huy/{id}", [ProfileController::class, "orderCancel"])->name("home.order.cancel");


    Route::post("/dat-xe-ngay", [MotorbikeController::class, "order"])->name("home.motorbike.order");
});



Route::middleware('guest:customer')->group(function () {
    Route::get("/dang-ky", [AuthController::class, "showRegisterForm"])->name("home.register.show");
    Route::post("/dang-ky", [AuthController::class, "register"])->name("home.register");

    Route::get("/dang-nhap", [AuthController::class, "showLoginForm"])->name("home.login.show");
    Route::post("/dang-nhap", [AuthController::class, "login"])->name("home.login");

    Route::get("/quen-mat-khau", [AuthController::class, "showForgotPasswordForm"])->name("home.forgot.show");
    Route::post('/quen-mat-khau"', [AuthController::class, "forgot"])->name('password.email');

    Route::get('/dat-lai-mat-khau/{token}', [AuthController::class, "showResetPasswordForm"])->name('password.reset');
    Route::post('/dat-lai-mat-khau', [AuthController::class, "resetPassword"])->name('password.update');

});

Route::get("/dang-xuat", [AuthController::class, "logout"])->name("home.logout");



//================================================================================================================
//=====================================================ADMIN======================================================
//================================================================================================================

//========================
//	login admin
//========================
Route::middleware('guest:web')->group(function () {
    Route::get('admin/login', [AdminLoginController::class, "index"])->name("admin.login");
    Route::post('admin/login', [AdminLoginController::class, "login"])->name("admin.check");
});

Route::prefix("admin")->middleware(CheckEmployeeAuth::class)->group(function () {
    //trang chủ
    // Route::view("/", 'backend.dashboard')->name("admin.index");
    Route::get("/", [AdminDashboardController::class, "index"])->name("admin.index");
    //========================
    //motorbike category
    //========================
    Route::resource('motorbike-category', AdminMotorbikeCategoryController::class);

    Route::put("motorbike-category/restore/{motorbike_category}", [AdminMotorbikeCategoryController::class, "restore"])
        ->name("motorbike-category.restore");

    Route::put("motorbike-category/force-delete/{motorbike_category}", [AdminMotorbikeCategoryController::class, "forceDelete"])
        ->name("motorbike-category.force-delete");

    Route::post("motorbike-category/update-action", [AdminMotorbikeCategoryController::class, "updateAction"])
        ->name("motorbike-category.updateAction");

    //========================
    //	supplier
    //========================
    Route::resource('supplier', AdminSupplierController::class);

    Route::put("supplier/restore/{motorbike_category}", [AdminSupplierController::class, "restore"])
        ->name("supplier.restore");

    Route::put("supplier/force-delete/{motorbike_category}", [AdminSupplierController::class, "forceDelete"])
        ->name("supplier.force-delete");

    Route::post("supplier/update-action", [AdminSupplierController::class, "updateAction"])
        ->name("supplier.updateAction");


    //========================
    //	cumstomer
    //========================
    Route::resource('customer', AdminCustomerController::class);

    Route::put("customer/restore/{customer}", [AdminCustomerController::class, "restore"])
        ->name("customer.restore");

    Route::put("customer/force-delete/{customer}", [AdminCustomerController::class, "forceDelete"])
        ->name("customer.force-delete");

    Route::post("customer/update-action", [AdminCustomerController::class, "updateAction"])
        ->name("customer.updateAction");

    //========================
    //	Position
    //========================
    Route::resource('position', AdminPositionController::class);

    Route::put("position/restore/{position}", [AdminPositionController::class, "restore"])
        ->name("position.restore");

    Route::put("position/force-delete/{position}", [AdminPositionController::class, "forceDelete"])
        ->name("position.force-delete");

    Route::post("position/update-action", [AdminPositionController::class, "updateAction"])
        ->name("position.updateAction");

    //========================
    //	Branch
    //========================
    Route::resource('branch', AdminBranchController::class);

    Route::put("branch/restore/{branch}", [AdminBranchController::class, "restore"])
        ->name("branch.restore");

    Route::put("branch/force-delete/{branch}", [AdminBranchController::class, "forceDelete"])
        ->name("branch.force-delete");

    Route::post("branch/update-action", [AdminBranchController::class, "updateAction"])
        ->name("branch.updateAction");
    //========================
    //	motorbike
    //========================
    Route::resource('motorbike', AdminMotorbikeController::class);

    Route::put("motorbike/restore/{motorbike}", [AdminMotorbikeController::class, "restore"])
        ->name("motorbike.restore");

    Route::put("motorbike/force-delete/{motorbike}", [AdminMotorbikeController::class, "forceDelete"])
        ->name("motorbike.force-delete");

    Route::post("motorbike/update-action", [AdminMotorbikeController::class, "updateAction"])
        ->name("motorbike.updateAction");

    //========================
    //	employee
    //========================
    Route::resource('employee', AdminEmployeeController::class);

    Route::put("employee/restore/{employee}", [AdminEmployeeController::class, "restore"])
        ->name("employee.restore");

    Route::put("employee/force-delete/{employee}", [AdminEmployeeController::class, "forceDelete"])
        ->name("employee.force-delete");

    Route::post("employee/update-action", [AdminEmployeeController::class, "updateAction"])
        ->name("employee.updateAction");

    //========================
    //	post
    //========================
    Route::resource('post', AdminPostController::class);

    Route::put("post/restore/{post}", [AdminPostController::class, "restore"])
        ->name("post.restore");

    Route::put("post/force-delete/{post}", [AdminPostController::class, "forceDelete"])
        ->name("post.force-delete");

    Route::post("post/update-action", [AdminPostController::class, "updateAction"])
        ->name("post.updateAction");

    //========================
    //	page
    //========================
    Route::resource('page', AdminPageController::class);

    Route::put("page/restore/{page}", [AdminPageController::class, "restore"])
        ->name("page.restore");

    Route::put("page/force-delete/{page}", [AdminPageController::class, "forceDelete"])
        ->name("page.force-delete");

    Route::post("page/update-action", [AdminPageController::class, "updateAction"])
        ->name("page.updateAction");

    //========================
    //	invoice
    //========================
    Route::resource('invoice', AdminOrderController::class);

    Route::put("invoice/restore/{invoice}", [AdminOrderController::class, "restore"])
        ->name("invoice.restore");

    Route::put("invoice/force-delete/{invoice}", [AdminOrderController::class, "forceDelete"])
        ->name("invoice.force-delete");

    Route::post("invoice/update-action", [AdminOrderController::class, "updateAction"])
        ->name("invoice.updateAction");

    //========================
    //profile
    //========================
    Route::get("profile", [UserAccountController::class, "showProfile"])->name("profile.show");
    Route::PUT("update-profile/{employee}", [UserAccountController::class, "updateProfile"])->name("profile.update");

    //change password
    Route::get("change-password", [UserAccountController::class, "showChangePassword"])->name("pass.show");
    Route::PUT("update-password/{employee}", [UserAccountController::class, "updatePassword"])->name("pass.update");

    Route::get('admin/logout', [AdminLoginController::class, "logout"])->name("admin.logout");
});




Route::get("/{slugPage}", [AppController::class, "showPage"])->name("home.page.show");

