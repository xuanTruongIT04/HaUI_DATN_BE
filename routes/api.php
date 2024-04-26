<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Users\AuthController;
use App\Http\Controllers\Users\CouponController;
use App\Http\Controllers\Users\CategoryController;
use App\Http\Controllers\Users\ProductController;
use App\Http\Controllers\Users\FavoriteController;
use App\Http\Controllers\Users\UserController;
use App\Http\Controllers\Users\SlideController;
use App\Http\Controllers\Users\PostController;
use App\Http\Controllers\Users\ImageController;
use App\Http\Controllers\Users\BrandController;
use App\Http\Controllers\Users\ColorController;
use App\Http\Controllers\Users\TagController;
use App\Http\Controllers\Users\CartController;
use App\Http\Controllers\Users\OrderController;
use App\Http\Controllers\Users\BillController;

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

//Auth
Route::prefix("auth")->controller(AuthController::class)->group(function () {
    Route::post('/register', "register")->name("user.auth.register");
    Route::post('/login', "login")->name("user.auth.login");

    Route::post('/forget-password', "forgetPassword")->name("user.auth.forgetPassword");
    Route::post('/reset-password/{token}', "resetPassword")->name("user.auth.resetPassword");

    Route::post('/check-token', 'checkToken')->name("user.auth.checkToken");

    Route::post('/verification', 'verificationSend')->name("user.auth.verification");
    Route::get('/verify-account/{idUser}/{token}', 'verificationGet')->name("user.auth.verificationGet");

    Route::post('/logout', "logout")->name("user.auth.logout");
});

//COUPON
Route::prefix("coupon")->controller(CouponController::class)->group(function () {
    Route::get('/', 'list')->name("user.coupon.list");
    Route::get('/list', 'list')->name("user.coupon.list");
});

// CATEGORY
Route::prefix("category")->controller(CategoryController::class)->group(function () {
    Route::get("/", "list")->name("user.category");
    Route::get("/list", "list")->name("user.category.list");
    Route::get("/tree-list", "getTreeList")->name("user.category.parent");
    // CALL SIDEBAR FILTER
    Route::get("/get-sidebar-filter", "getSidebarFilter")->name("user.category.sidebar");
});

// PRODUCT
Route::prefix("product")->controller(ProductController::class)->group(function () {
    Route::get("/", "list")->name("user.product");
    Route::get("/list", "list")->name("user.product.list");
    Route::get("/detail/{slug}", "detail")->name("user.product.detail");
    Route::get("/list-latest", "getAllLatest")->name("user.product.getAllLatest");
    Route::post("/filter", "filter")->name("user.product.filter");
    Route::get("/get-max-min-price", "getMMPrice")->name("user.product.getMMPrice");
    Route::get("/related/{id}", "getProductRelated")->name("user.product.getProductRelated");
});

//POST
Route::prefix("post")->controller(PostController::class)->group(function () {
    Route::get("/", "getAllLicensed")->name("user.post");
    Route::get("/list", "getAllLicensed")->name("user.post.list");
    Route::get("/list-latest", "getAllLatest")->name("user.post.getAllLatest");
    Route::get("/detail/{id}", "detail")->name("user.post.detail");
});

// SLIDE
Route::prefix("slide")->controller(SlideController::class)->group(function () {
    Route::get("/", "list")->name("user.slide");
    Route::get("/list", "list")->name("user.slide.list");
});

// BRAND
Route::prefix("brand")->controller(BrandController::class)->group(function () {
    Route::get("/", "list")->name("user.brand");
    Route::get("/list", "list")->name("user.brand.list");
});

// IMAGE
Route::prefix("image")->controller(ImageController::class)->group(function () {
    Route::get("/get-image-pc/{idProduct}", "getImagePC")->name("user.image.getImagePC");
    Route::post("/get-thumb", "getImageProduct")->name("user.product.getImageProduct");
});

// COLOR
Route::prefix("color")->controller(ColorController::class)->group(function () {
    Route::get("/", "list")->name("user.color");
    Route::get("/list", "list")->name("user.color.list");
});

//TAG
Route::prefix("tag")->controller(TagController::class)->group(function () {
    Route::get("/list-popular", "listPopular")->name("user.tag.listPopular");
});

//COUPON
Route::prefix("coupon")->controller(CouponController::class)->group(function () {
    Route::get('/', 'list')->name("user.coupon.list");
    Route::get('/list', 'list')->name("user.coupon.list");
});

Route::group(
    ['middleware' => 'user:api'],
    function () {
        // Auth
        Route::controller(AuthController::class)->group(function () {
            Route::post('change-password', 'changePassword')->name("user.auth.changePassword");
        });
        //User
        Route::controller(UserController::class)->group(function () {
            Route::get('show-infor', 'showInfor')->name("user.showInfo");
            Route::post('update-infor', 'updateInfor')->name("user.updateInfor");
            Route::get('check-infor', 'checkInfor')->name("user.checkInfor");

            Route::post('update-remember-token', 'updateRememberToken')->name("user.updateRememberToken");
            Route::post('check-remember-token', 'checkRememberToken')->name("user.checkRememberToken");
        });

        // Favorite
        Route::prefix("favorite")->controller(FavoriteController::class)->group(function () {
            Route::get("/", "list")->name("user.favorite");
            Route::get("/list", "list")->name("user.favorite.list");

            Route::post("/add-to-cart", "addToCart")->name("user.favorite.addToCart");

            Route::post('/toggle/{itemId}', 'toggle')->name("user.favorite.toggle");

            Route::post('/delete/{itemId?}', 'deleteByIdProduct')->name("user.favorite.deleteByIdProduct");
        });

        // Cart
        Route::prefix("cart")->controller(CartController::class)->group(function () {
            Route::get("/", "list")->name("user.cart");
            Route::get("/list", "list")->name("user.cart.list");

            Route::post('/add/{productId}/{numberOrder}', 'add')->name("user.cart.add");

            Route::post('/update', 'updateProductInCart')->name("user.cart.updateProductInCart");

            Route::post('/delete/{productId?}', 'deleteProductInCart')->name("user.cart.deleteProductInCart");
            Route::post('/deleteAll', 'deleteAll')->name("user.cart.deleteAll");
        });

        // Order
        Route::prefix("order")->controller(OrderController::class)->group(function () {
            Route::post('/create', 'create')->name("user.order.create");
            Route::post('/update', 'update')->name("user.order.update");

            Route::get('/get-status', 'getStatus')->name("user.order.status");
            Route::get('/get-info', 'getInfoOrder')->name("user.order.info");

            Route::post('/submit-order', 'submitOrder')->name("user.order.submitOrder");
        });

        // Coupon
        Route::prefix("coupon")->controller(CouponController::class)->group(function () {
            Route::post('/check', 'check')->name("user.coupon.check");
        });

        // Bill
        Route::prefix("bill")->controller(BillController::class)->group(function () {
            Route::get('/get-info/{idBill}', 'getInfoFromBill')->name("user.bill.getInfo");
        });


    }
);

// CATEGORY
Route::prefix("category")->controller(CategoryController::class)->group(function () {
    Route::get("/", "list")->name("user.category");
    Route::get("/list", "list")->name("user.category.list");
    Route::get("/tree-list", "getTreeList")->name("user.category.parent");
    // CALL SIDEBAR FILTER
    Route::get("/get-sidebar-filter", "getSidebarFilter")->name("user.category.sidebar");
});

// PRODUCT
Route::prefix("product")->controller(ProductController::class)->group(function () {
    Route::get("/", "list")->name("user.product");
    Route::get("/list", "list")->name("user.product.list");
    Route::get("/detail/{slug}", "detail")->name("user.product.detail");
    Route::get("/list-latest", "getAllLatest")->name("user.product.getAllLatest");
    Route::post("/filter", "filter")->name("user.product.filter");
    Route::get("/get-max-min-price", "getMMPrice")->name("user.product.getMMPrice");
    Route::get("/related/{id}", "getProductRelated")->name("user.product.getProductRelated");
});

//POST
Route::prefix("post")->controller(PostController::class)->group(function () {
    Route::get("/", "getAllLicensed")->name("user.post");
    Route::get("/list", "getAllLicensed")->name("user.post.list");
    Route::get("/list-latest", "getAllLatest")->name("user.post.getAllLatest");
    Route::get("/detail/{id}", "detail")->name("user.post.detail");
});

// SLIDE
Route::prefix("slide")->controller(SlideController::class)->group(function () {
    Route::get("/", "list")->name("user.slide");
    Route::get("/list", "list")->name("user.slide.list");
});

// BRAND
Route::prefix("brand")->controller(BrandController::class)->group(function () {
    Route::get("/", "list")->name("user.brand");
    Route::get("/list", "list")->name("user.brand.list");
});

// IMAGE
Route::prefix("image")->controller(ImageController::class)->group(function () {
    Route::get("/get-image-pc/{idProduct}", "getImagePC")->name("user.image.getImagePC");
    Route::post("/get-thumb", "getImageProduct")->name("user.product.getImageProduct");
});

// COLOR
Route::prefix("color")->controller(ColorController::class)->group(function () {
    Route::get("/", "list")->name("user.color");
    Route::get("/list", "list")->name("user.color.list");
});

//TAG
Route::prefix("tag")->controller(TagController::class)->group(function () {
    Route::get("/list-popular", "listPopular")->name("user.tag.listPopular");
});

//COUPON
Route::prefix("coupon")->controller(CouponController::class)->group(function () {
    Route::get('/', 'list')->name("user.coupon.list");
    Route::get('/list', 'list')->name("user.coupon.list");
});

Route::group(
    ['middleware' => 'user:api'],
    function () {
        // Auth
        Route::controller(AuthController::class)->group(function () {
            Route::post('change-password', 'changePassword')->name("user.auth.changePassword");
        });
        //User
        Route::controller(UserController::class)->group(function () {
            Route::get('show-infor', 'showInfor')->name("user.showInfo");
            Route::post('update-infor', 'updateInfor')->name("user.updateInfor");
            Route::get('check-infor', 'checkInfor')->name("user.checkInfor");

            Route::post('update-remember-token', 'updateRememberToken')->name("user.updateRememberToken");
            Route::post('check-remember-token', 'checkRememberToken')->name("user.checkRememberToken");
        });

        // Favorite
        Route::prefix("favorite")->controller(FavoriteController::class)->group(function () {
            Route::get("/", "list")->name("user.favorite");
            Route::get("/list", "list")->name("user.favorite.list");

            Route::post("/add-to-cart", "addToCart")->name("user.favorite.addToCart");

            Route::post('/toggle/{itemId}', 'toggle')->name("user.favorite.toggle");

            Route::post('/delete/{itemId?}', 'deleteByIdProduct')->name("user.favorite.deleteByIdProduct");
        });

        // Cart
        Route::prefix("cart")->controller(CartController::class)->group(function () {
            Route::get("/", "list")->name("user.cart");
            Route::get("/list", "list")->name("user.cart.list");

            Route::post('/add/{productId}/{numberOrder}', 'add')->name("user.cart.add");

            Route::post('/update', 'updateProductInCart')->name("user.cart.updateProductInCart");

            Route::post('/delete/{productId?}', 'deleteProductInCart')->name("user.cart.deleteProductInCart");
            Route::post('/deleteAll', 'deleteAll')->name("user.cart.deleteAll");
        });

        // Order
        Route::prefix("order")->controller(OrderController::class)->group(function () {
            Route::post('/create', 'create')->name("user.order.create");
            Route::post('/update', 'update')->name("user.order.update");

            Route::get('/get-status', 'getStatus')->name("user.order.status");
            Route::get('/get-info', 'getInfoOrder')->name("user.order.info");

            Route::post('/submit-order', 'submitOrder')->name("user.order.submitOrder");
        });

        // Coupon
        Route::prefix("coupon")->controller(CouponController::class)->group(function () {
            Route::post('/check', 'check')->name("user.coupon.check");
        });

        // Bill
        Route::prefix("bill")->controller(BillController::class)->group(function () {
            Route::get('/get-info/{idBill}', 'getInfoFromBill')->name("user.bill.getInfo");
        });


    }
);
