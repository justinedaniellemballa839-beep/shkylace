<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\DeliveryController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MapController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\TontineController;
use App\Http\Controllers\VendorController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/marches', [HomeController::class, 'markets'])->name('markets.index');
Route::get('/marches/{market}', [HomeController::class, 'marketShow'])->name('markets.show');
Route::get('/produits', [HomeController::class, 'products'])->name('products.index');
Route::get('/produits/{product}', [HomeController::class, 'productShow'])->name('products.show');
Route::get('/carte', [MapController::class, 'index'])->name('map.index');
Route::get('/tontines', [TontineController::class, 'index'])->name('tontines.index');
Route::get('/tontines/{tontine}', [TontineController::class, 'show'])->name('tontines.show');
Route::get('/devenir-livreur', [DeliveryController::class, 'showApply'])->name('delivery.apply');
Route::post('/devenir-livreur', [DeliveryController::class, 'apply'])->name('delivery.apply.store');

Route::middleware('guest')->group(function () {
    Route::get('/connexion', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/connexion', [AuthController::class, 'login']);
    Route::get('/inscription', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/inscription', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/deconnexion', [AuthController::class, 'logout'])->name('logout');
    Route::get('/tableau-de-bord', [HomeController::class, 'dashboard'])->name('dashboard');
    Route::get('/mot-de-passe', [AuthController::class, 'showChangePassword'])->name('password.change');
    Route::post('/mot-de-passe', [AuthController::class, 'changePassword']);
    Route::get('/verifier-pin', [AuthController::class, 'showPinVerify'])->name('pin.verify');
    Route::post('/verifier-pin', [AuthController::class, 'verifyPin']);
    Route::post('/configurer-pin', [AuthController::class, 'setupPin'])->name('pin.setup');

    Route::get('/panier', [CartController::class, 'index'])->name('cart.index');
    Route::post('/panier/ajouter/{product}', [CartController::class, 'add'])->name('cart.add');
    Route::put('/panier/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/panier/{item}', [CartController::class, 'remove'])->name('cart.remove');

    Route::get('/commandes', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/commandes/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/commandes/annuler/{order}', [OrderController::class, 'cancel'])->name('orders.cancel');
    Route::post('/commandes/valider', [OrderController::class, 'checkout'])
        ->middleware('pin.verified')
        ->name('orders.checkout');

    Route::post('/tontines/{tontine}/cotiser', [TontineController::class, 'contribute'])
        ->middleware('pin.verified')
        ->name('tontines.contribute');

    Route::get('/paiement/{order}', [PaymentController::class, 'show'])->name('payments.show');
    Route::post('/paiement/{order}', [PaymentController::class, 'process'])
        ->middleware('pin.verified')
        ->name('payments.process');
    Route::get('/recu/{payment}', [PaymentController::class, 'receipt'])->name('payments.receipt');
});

Route::middleware(['auth', 'role:vendeur'])->prefix('vendeur')->name('vendor.')->group(function () {
    Route::get('/tableau-de-bord', [VendorController::class, 'dashboard'])->name('dashboard');
    Route::get('/boutique/creer', [VendorController::class, 'createShop'])->name('shop.create');
    Route::post('/boutique/creer', [VendorController::class, 'storeShop'])->name('shop.store');

    Route::get('/produits', [ProductController::class, 'index'])->name('products.index');
    Route::get('/produits/creer', [ProductController::class, 'create'])->name('products.create');
    Route::post('/produits', [ProductController::class, 'store'])->name('products.store');
    Route::get('/produits/{product}/modifier', [ProductController::class, 'edit'])->name('products.edit');
    Route::put('/produits/{product}', [ProductController::class, 'update'])->name('products.update');
    Route::delete('/produits/{product}', [ProductController::class, 'destroy'])->name('products.destroy');

    Route::get('/commandes', [OrderController::class, 'vendorOrders'])->name('orders.index');
    Route::put('/commandes/{order}/statut', [OrderController::class, 'updateStatus'])->name('orders.status');
    Route::get('/commandes/{order}/livreur', [DeliveryController::class, 'availableLivreurs'])->name('orders.assign');

    Route::get('/tontines', [TontineController::class, 'vendorIndex'])->name('tontines.index');
    Route::get('/tontines/creer', [TontineController::class, 'create'])->name('tontines.create');
    Route::post('/tontines', [TontineController::class, 'store'])->name('tontines.store');
    Route::post('/tontines/{tontine}/inviter', [TontineController::class, 'invite'])->name('tontines.invite');
});

Route::middleware(['auth', 'role:livreur'])->prefix('livreur')->name('delivery.')->group(function () {
    Route::get('/tableau-de-bord', [DeliveryController::class, 'dashboard'])->name('dashboard');
    Route::post('/localisation/{order}', [DeliveryController::class, 'updateLocation'])->name('location');
    Route::post('/terminer/{order}', [DeliveryController::class, 'completeDelivery'])->name('complete');
});

Route::middleware(['auth', 'role:administrateur'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/tableau-de-bord', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/utilisateurs', [AdminController::class, 'users'])->name('users.index');
    Route::put('/utilisateurs/{user}/toggle', [AdminController::class, 'toggleUser'])->name('users.toggle');
    Route::get('/vendeurs', [AdminController::class, 'vendors'])->name('vendors.index');
    Route::put('/vendeurs/{shop}/approuver', [AdminController::class, 'approveVendor'])->name('vendors.approve');
    Route::put('/vendeurs/{shop}/suspendre', [AdminController::class, 'suspendVendor'])->name('vendors.suspend');
    Route::get('/tontines', [AdminController::class, 'tontines'])->name('tontines.index');
    Route::get('/livreurs', [AdminController::class, 'deliveryApplications'])->name('delivery.index');
    Route::put('/livreurs/{application}', [AdminController::class, 'reviewApplication'])->name('delivery.review');
});
