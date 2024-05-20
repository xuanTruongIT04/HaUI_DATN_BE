<?php

use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admins\AdminController;
use App\Http\Controllers\Admins\Auth\ConfirmPasswordController;
use App\Http\Controllers\Admins\Auth\ForgotPasswordController;
use App\Http\Controllers\Admins\Auth\LoginController;
use App\Http\Controllers\Admins\Auth\RegisterController;
use App\Http\Controllers\Admins\Auth\ResetPasswordController;
use App\Http\Controllers\Admins\Auth\VerificationController;
use App\Http\Controllers\Admins\BrandController;
use App\Http\Controllers\Admins\CategoryController;
use App\Http\Controllers\Admins\ColorController;
use App\Http\Controllers\Admins\CouponController;
use App\Http\Controllers\Admins\ProductController;
use App\Http\Controllers\Admins\SlideController;
use App\Http\Controllers\Admins\PostController;
use App\Http\Controllers\Admins\ImageController;
use App\Http\Controllers\Admins\TagController;
use App\Http\Controllers\Admins\ProductTagController;
use App\Http\Controllers\Admins\UserController;
use App\Http\Controllers\Admins\OrderController;
use App\Http\Controllers\Admins\CartController;
use App\Http\Controllers\Admins\BillController;
use App\Http\Controllers\Admins\ExcelController;

// Controller
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
// Custom auth routes
Auth::routes(['verify' => true]);

Route::controller(LoginController::class)->group(function () {
    Route::get('/login', 'showLoginForm')->name('login');
    Route::post('/login', 'login');
    Route::post('/logout', 'logout')->name('logout');
});

Route::controller(RegisterController::class)->group(function () {
    Route::get('/register', 'showRegistrationForm')->name('register');
    Route::post('/register', 'register');
});

Route::prefix("email")->controller(VerificationController::class)->group(function () {
    Route::get('/verify', 'show')->name('verification.notice');
    Route::get('/verify/{id}/{hash}', 'verify')->name('verification.verify');
    Route::post('/resend', 'resend')->name('verification.resend');
});

Route::prefix("password")->group(function () {
    Route::controller(ForgotPasswordController::class)->group(function () {
        Route::get('/reset', 'showLinkRequestForm')->name('password.request');
        Route::post('/email', 'sendResetLinkEmail')->name('password.email');
    });

    Route::controller(ResetPasswordController::class)->group(function () {
        Route::get('/reset/{token}', 'showResetForm')->name('password.reset');
        Route::post('/reset', 'reset')->name('password.update');
    });

    Route::controller(ConfirmPasswordController::class)->group(function () {
        Route::get('/confirm', 'showConfirmForm')->name('password.confirm');
        Route::post('/confirm', 'confirm');
    });
});


Route::prefix("email")->group(function () {
    //The Email Verification Notice
    Route::get('/verify', function () {
        return view('auth.verify');
    })->name('verification.notice');

    //The Email Verification Handler
    Route::get('/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        // Thêm session
        session()->flash('welcome', true);

        return redirect('/');
    })->name('verification.verify');
});

// File manager
Route::group(['prefix' => 'laravel-filemanager'], function () {
    \UniSharp\LaravelFilemanager\Lfm::routes();
});

// =====================================ROUTE MAIN=============================================
Route::middleware('auth', 'role')->group(function () {
    Route::get('/', function () {
        return view('admin.dashboard');
    })->name("dashboard");

    Route::middleware("role:admin super manager")->group(function () {
        // SLIDE
        Route::prefix("slide")->controller(SlideController::class)->group(function () {
            Route::get("/", "list")->name("slide.list");
            Route::get("/list", "list")->name("slide.list");

            Route::get("/add", "add")->name("slide.add");
            Route::post("/store", "store")->name("slide.store");

            Route::get("/edit/{id}", "edit")->name("slide.edit");
            Route::post("/update/{id}", "update")->name("slide.update");

            Route::get("/delete/{id}", "delete")->name("slide.delete");

            Route::get("/restore/{id}", "restore")->name("slide.restore");

            Route::get("/action", "action")->name("slide.action");
        });

        // POST
        Route::prefix("post")->controller(PostController::class)->group(function () {
            Route::get("/", "list")->name("post.list");
            Route::get("/list", "list")->name("post.list");

            Route::get("/add", "add")->name("post.add");
            Route::post("/store", "store")->name("post.store");

            Route::get("/edit/{id}", "edit")->name("post.edit");
            Route::post("/update/{id}", "update")->name("post.update");

            Route::get("/delete/{id}", "delete")->name("post.delete");

            Route::get("/restore/{id}", "restore")->name("post.restore");

            Route::get("/action", "action")->name("post.action");
        });
    });

    Route::middleware("role:admin super sales_manager")->group(function () {
        // CATEGORIES
        Route::prefix("category")->controller(CategoryController::class)->group(function () {

            Route::get("/", "list")->name("category.list");
            Route::get("/list", "list")->name("category.list");

            Route::get("/add", "add")->name("category.add");
            Route::post("/store", "store")->name("category.store");

            Route::get("/edit/{id}", "edit")->name("category.edit");
            Route::post("/update/{id}", "update")->name("category.update");

            Route::get("/delete/{id}", "delete")->name("category.delete");

            Route::get("/restore/{id}", "restore")->name("category.restore");

            Route::get("/action", "action")->name("category.action");
        });

        // COLOR
        Route::prefix("color")->controller(ColorController::class)->group(function () {
            Route::get("/", "list")->name("color.list");
            Route::get("/list", "list")->name("color.list");

            Route::get("/add", "add")->name("color.add");
            Route::post("/store", "store")->name("color.store");

            Route::get("/edit/{id}", "edit")->name("color.edit");
            Route::post("/update/{id}", "update")->name("color.update");

            Route::get("/delete/{id}", "delete")->name("color.delete");

            Route::get("/restore/{id}", "restore")->name("color.restore");

            Route::get("/action", "action")->name("color.action");
        });

        // BRAND
        Route::prefix("brand")->controller(BrandController::class)->group(function () {
            Route::get("/", "list")->name("brand.list");
            Route::get("/list", "list")->name("brand.list");

            Route::get("/add", "add")->name("brand.add");
            Route::post("/store", "store")->name("brand.store");

            Route::get("/edit/{id}", "edit")->name("brand.edit");
            Route::post("/update/{id}", "update")->name("brand.update");

            Route::get("/delete/{id}", "delete")->name("brand.delete");

            Route::get("/restore/{id}", "restore")->name("brand.restore");

            Route::get("/action", "action")->name("brand.action");
        });

        // PRODUCT
        Route::prefix("product")->controller(ProductController::class)->group(function () {
            Route::get("/", "list")->name("product.list");
            Route::get("/list", "list")->name("product.list");

            Route::get("/track-product-sold", "trackProductSold")->name("product.track.product.sold");

            Route::get("/add", "add")->name("product.add");
            Route::post("/store", "store")->name("product.store");

            Route::get("/edit/{id}", "edit")->name("product.edit");
            Route::post("/update/{id}", "update")->name("product.update");
            Route::post("/updateImage/{id}", "updateImage")->name("product.updateImage");

            Route::get("/delete/{id}", "delete")->name("product.delete");

            Route::get("/restore/{id}", "restore")->name("product.restore");

            Route::get("/action", "action")->name("product.action");
        });

        // COUPON
        Route::prefix("coupon")->controller(CouponController::class)->group(function () {
            Route::get("/", "list")->name("coupon.list");
            Route::get("/list", "list")->name("coupon.list");

            Route::get("/add", "add")->name("coupon.add");
            Route::post("/store", "store")->name("coupon.store");

            Route::get("/edit/{id}", "edit")->name("coupon.edit");
            Route::post("/update/{id}", "update")->name("coupon.update");

            Route::get("/delete/{id}", "delete")->name("coupon.delete");

            Route::get("/restore/{id}", "restore")->name("coupon.restore");

            Route::get("/action", "action")->name("coupon.action");
        });

        // IMAGE
        Route::prefix("image")->controller(ImageController::class)->group(function () {
            Route::get("/", "list")->name("image.list");
            Route::get("/list", "list")->name("image.list");

            Route::get("/add/{id?}", "add")->name("image.add");
            Route::post("/store/{id?}", "store")->name("image.store");

            Route::get("/edit/{id}", "edit")->name("image.edit");
            Route::post("/update/{id}", "update")->name("image.update");

            Route::get("/delete/{id}", "delete")->name("image.delete");

            Route::get("/restore/{id}", "restore")->name("image.restore");

            Route::get("/action", "action")->name("image.action");
        });

        // TAG
        Route::prefix("tag")->controller(TagController::class)->group(function () {
            Route::get("/", "list")->name("tag.list");
            Route::get("/list", "list")->name("tag.list");

            Route::get("/add", "add")->name("tag.add");
            Route::post("/store", "store")->name("tag.store");

            Route::get("/edit/{id}", "edit")->name("tag.edit");
            Route::post("/update/{id}", "update")->name("tag.update");

            Route::get("/delete/{id}", "delete")->name("tag.delete");

            Route::get("/restore/{id}", "restore")->name("tag.restore");

            Route::get("/action", "action")->name("tag.action");
        });

        // PRODUCT TAG
        Route::prefix("product-tag")->controller(ProductTagController::class)->group(function () {
            Route::get("/", "list")->name("productTag.list");
            Route::get("/list", "list")->name("productTag.list");

            Route::get("/add", "add")->name("productTag.add");
            Route::post("/store", "store")->name("productTag.store");

            Route::get("/edit/{id}", "edit")->name("productTag.edit");
            Route::post("/update/{id}", "update")->name("productTag.update");

            Route::get("/delete/{id}", "delete")->name("productTag.delete");

            Route::get("/restore/{id}", "restore")->name("productTag.restore");

            Route::get("/action", "action")->name("productTag.action");
        });
    });

    Route::middleware("role:admin super")->group(function () {
        // USER
        Route::prefix("user")->controller(UserController::class)->group(function () {

            Route::get("/", "list")->name("user");
            Route::get("/list", "list")->name("user.list");

            Route::get("/edit/{id}", "edit")->name("user.edit");
            Route::post("/update/{id}", "update")->name("user.update");

            Route::get("/action", "action")->name("user.action");
        });

        // ORDER
        Route::prefix("order")->controller(OrderController::class)->group(function () {
            Route::get("/", "list")->name("order");
            Route::get("/list", "list")->name("order.list");

            Route::get("/edit/{id}", "edit")->name("order.edit");
            Route::post("/update/{id}", "update")->name("order.update");

            Route::get("/detail/{id}", "detail")->name("order.detail");
            Route::post("/detail/update/{id}", "detailUpdate")->name("order.detailUpdate");

            Route::get("/action", "action")->name("order.action");
        });

        // CART
        Route::prefix("cart")->controller(CartController::class)->group(function () {
            Route::get("/", "list")->name("cart");
            Route::get("/list", "list")->name("cart.list");

            Route::get("/edit/{id}", "edit")->name("cart.edit");
            Route::post("/update/{id}", "update")->name("cart.update");

            Route::get("/action", "action")->name("cart.action");
        });

        // BILL
        Route::prefix("bill")->controller(BillController::class)->group(function () {
            Route::get("/", "list")->name("bill");
            Route::get("/list", "list")->name("bill.list");

            Route::get("/edit/{id}", "edit")->name("bill.edit");
            Route::post("/update/{id}", "update")->name("bill.update");

            Route::get("/detail/{id}", "detail")->name("bill.detail");
            Route::post("/detail/update/{id}", "detailUpdate")->name("bill.detailUpdate");

            Route::get("/action", "action")->name("bill.action");
        });
    });

    Route::prefix("admin")->controller(AdminController::class)->group(function () {
        // ADMINS
        Route::get("/editPassword/{id}", "editPassword")->name("admin.editPassword");
        Route::post("/updatePassword/{id}", "updatePassword")->name("admin.updatePassword");
        Route::get("/edit/{id}", "edit")->name("admin.edit");
        Route::post("/update/{id}", "update")->name("admin.update");

        Route::middleware("role:super")->group(function () {
            Route::get("/", "list")->name("admin.list");
            Route::get("/list", "list")->name("admin.list");

            Route::get("/add", "add")->name("admin.add");
            Route::post("/store", "store")->name("admin.store");

            Route::get("/delete/{id}", "delete")->name("admin.delete");

            Route::get("/restore/{id}", "restore")->name("admin.restore");

            Route::get("/action", "action")->name("admin.action");
        });
    });

    // COLOR
    Route::prefix("export")->controller(ExcelController::class)->group(function () {
        Route::post("/order-list", "exportOrders")->name("export.order");
    });
});
