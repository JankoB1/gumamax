<?php

use App\Http\Controllers\MainController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PartnerController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\HomeTestController;
use App\Http\Controllers\ShippingController;
use App\Http\Controllers\ElasticController;
use App\Http\Controllers\MembershipRequestController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\MichelinVehicleController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\VehicleController;
use App\Http\Controllers\CityController;
use App\Http\Controllers\Crm\MemberController;
use App\Http\Controllers\Crm\MemberPriceListController;
use App\Http\Controllers\Crm\MemberInformationController;
use App\Http\Controllers\ContactFormMessageController;
use App\Http\Controllers\CallbackRequestController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserActivityController;
use App\Http\Controllers\CarouselController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderPaymentController;
use App\Http\Controllers\MerchantApiController;
use App\Http\Controllers\PartnerPriceListController;
use App\Http\Controllers\PartnerAboutController;
use App\Http\Controllers\Crm\ProjectController;
use App\Http\Controllers\Crm\WorkingHoursController;
use App\Http\Controllers\Crm\MemberWorkingHoursController;
use App\Http\Controllers\Crm\MemberPaymentMethodController;
use App\Http\Controllers\Crm\MemberPageController;
use App\Http\Controllers\Crm\MemberAddressController;
use App\Http\Controllers\Crm\AddressController as CrmAddressController;
use App\Http\Controllers\Crm\InformationController;
use App\Http\Controllers\Crm\InformationGroupController;
use App\Http\Controllers\Crm\ProjectInformationTemplatesController;
use App\Http\Controllers\Crm\LogoController;
use App\Http\Controllers\Crm\CoverController;
use App\Http\Controllers\Crm\PhotoController;
use App\Http\Controllers\Crm\MemberUserRoleController;
use App\Http\Controllers\PaymentGatewayController;
use App\Http\Controllers\BackofficeController;

use Elasticsearch\ClientBuilder;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Auth::routes();

Route::get('/', [MainController::class, 'index'])->name('show-homepage');
Route::get('/proizvod/{id}/{kind}', [ProductController::class, 'showSingleProduct'])->name('show-single-product');
Route::get('/gume', [ProductController::class, 'showShop'])->name('show-shop');
Route::get('/uporedi', [ProductController::class, 'showCompare'])->name('show-compare');
Route::get('/mreza-partnera', [PartnerController::class, 'showPartners'])->name('show-partners');
Route::get('/partner', [PartnerController::class, 'showSinglePartner'])->name('show-single-partner');
Route::get('/porudzbina', [OrderController::class, 'showMakeOrder'])->name('show-make-order');
Route::get('/gume/items', [ProductController::class, 'showStoreItems'])->name('show-store-items');


//novi proizvodi
Route::get('/akumulatori', [ProductController::class, 'showShopBatteries'])->name('show-shop-batteries');
Route::get('api/products/batteries/search', [ProductController::class, 'apiBatteriesSearch'])->name('api.products.batteries.search');
Route::get('akumulatori/items', [ProductController::class, 'showStoreItemsBatteries'])->name('show-store-items-batteries');

Route::get('/ulja', [ProductController::class, 'showShopOil'])->name('show-shop-batteries');
Route::get('api/products/oil/search', [ProductController::class, 'apiOilSearch'])->name('api.products.batteries.search');
Route::get('ulja/items', [ProductController::class, 'showStoreItemsOil'])->name('show-store-items-batteries');

Route::get('/ratkapne', [ProductController::class, 'showShopHubcaps'])->name('show-shop-batteries');
Route::get('api/products/hubcaps/search', [ProductController::class, 'apiHubcapsSearch'])->name('api.products.batteries.search');
Route::get('ratkapne/items', [ProductController::class, 'showStoreItemsHubcaps'])->name('show-store-items-batteries');

Route::get('partners/login', [PartnerController::class, 'login']);

Route::get('partners/membership/create', [MembershipRequestController::class, 'create'])->name('partners.membership.create');
Route::post('partners/membership', [MembershipRequestController::class, 'store'])->name('partners.membership.store');

/*
|--------------------------------------------------------------------------
| Static pages related routes
|--------------------------------------------------------------------------
|
|
|
*/

Route::get('/static/{view}', [HomeController::class, 'staticPage']);
Route::get('/faq', [HomeController::class, 'faq']);
Route::get('/popup/{slug}', [HomeController::class, 'popup']);
Route::get('/read-tyre', [HomeController::class, 'howToReadTyre']);

Route::get('/api/products/tyres/{id}', [ProductController::class, 'fetchSingleItem'])->where('id', '[0-9]+')->name('fetch_tyres');

/*
|--------------------------------------------------------------------------
| Products
|--------------------------------------------------------------------------
| Products search related routes
|
|
*/

Route::get('products', [ProductController::class, 'index'])->name('products_index');

Route::get('api/products/search', [ProductController::class, 'apiSearch'])->name('api.product.search');


Route::get('api/products/dimensions/selected/bundle', [ProductController::class, 'apiDimensionsSelectedBundle'])->name('api.products.dimensions.selected.bundle');

Route::get('api/products/tyres/dimensions/widths/{vehicle_category}', [ProductController::class, 'getTyresWidths'])->name('tyres_widths');

Route::get('api/products/tyres/dimensions/ratios/{vehicle_category}/{width}', [ProductController::class, 'getTyresRatios'])->name('tyres_ratios');

Route::get('api/products/tyres/dimensions/diameters/{vehicle_category}/{width}/{ratio}', [ProductController::class, 'getTyresDiameters'])->name('tyres_diameters');

Route::get('api/products/tyres/search', [ProductController::class, 'apiTyresSearch'])->name('api.products.tyres.search');

Route::get('api/products/tyres/replacements', [ProductController::class, 'apiTyresReplacements'])->name('api.products.tyres.replacements');

Route::get('products/tyres/{id}/{name?}', [ProductController::class, 'show'])->where('id', '[0-9]+')->name('show_tyres');

Route::get('products/tyres/compare/{id1}/{id2?}/{id3?}', [ProductController::class, 'compareList'])->name('compare_tyres_list');

Route::get('products/tyres/compare', [ProductController::class, 'compare'])->name('compare_tyres_from_cookie');

Route::post('api/products/betterprice', [ProductController::class, 'addBetterPrice'])->name('api.products.betterprice.store');

/**
 * Auto planeta
 */
Route::get('api/products/tyres/all', [ProductController::class, 'apiTyresAll'])->name('api.products.tyres.all');


/*
|--------------------------------------------------------------------------
| Review: Products
|--------------------------------------------------------------------------
| For now, only reviewing product is allowed (no partner reviews)
*/
Route::get('/review/products/{product_id}', [ProductReviewController::class, 'reviewProduct']);
Route::post('/review/products/{product_id}', [ProductReviewController::class, 'storeProductReview']);
Route::get('/review/products/{product_id}/all', [ProductReviewController::class, 'getAllProductsReviews']);


/*
|--------------------------------------------------------------------------
| Michelin vehicle data
|--------------------------------------------------------------------------
|
*/

Route::get('api/vehicles/michelin/brands', [MichelinVehicleController::class, 'apiBrands']);
Route::get('api/vehicles/michelin/models/{brand}', [MichelinVehicleController::class, 'apiModels']);
Route::get('api/vehicles/michelin/engines/{brand}/{model}', [MichelinVehicleController::class, 'apiEngines']);
Route::get('api/vehicles/michelin/years/{brand}/{model}/{engine}', [MichelinVehicleController::class, 'apiYears']);
Route::get('api/vehicles/michelin/dimensions/{brand}/{model}/{engine}/{year}', [MichelinVehicleController::class, 'apiDimensions']);
Route::get('api/vehicles/michelin/selected/bundle', [MichelinVehicleController::class, 'apiGetDimensionsBundle']);

/*
|--------------------------------------------------------------------------
| Cart
|--------------------------------------------------------------------------
*/
Route::post('cart/items', [CartController::class, 'addItem'])->name('api.cart.item.add');

Route::get('/cart/partner/{id}/{opened?}', [CartController::class, 'getAllForPartner']);

Route::get('cart/edit', [CartController::class, 'show'])->name('cart_edit');

Route::get('api/opened/cart', [CartController::class, 'apiGetOpened'])->name('api.opened.cart');

Route::get('cart/{cartId}', [CartController::class, 'show'])->name('cart_show');

Route::post('api/cart/items/update/qty', [CartController::class, 'apiUpdateItemQty'])->name('api.cart.items.update.qty');

Route::delete('api/cart/items/{id}', [CartController::class, 'apiDeleteItem'])->name('api.cart.items.delete');

Route::delete('api/cart/empty', [CartController::class, 'apiEmptyCart']);

Route::post('api/cart/paymentMethod', [CartController::class, 'setPaymentMethod'])->name('api.cart.paymentMethod');

/*
|--------------------------------------------------------------------------
| addVehicle plugin
|--------------------------------------------------------------------------
*/
Route::get('/vehicle/ajax', [VehicleController::class, 'index']);
Route::get('/vehicle/ajax/session', [VehicleController::class, 'getInputVehicleFromSession']);


/*
|--------------------------------------------------------------------------
| Shipping location
|--------------------------------------------------------------------------
| - find/choose partner
| - input custom address
|
*/
Route::get('/shipping/location', [ShippingController::class, 'index']);
Route::get('/shipping/test', [ShippingController::class, 'testIndex']);
Route::post('cart/shipping/address', [ShippingController::class, 'setShippingAddress']);
Route::post('/shipping/location/address', [ShippingController::class, 'store']);
Route::put('/shipping/location/address/{id}', [ShippingController::class, 'update']);


Route::get('cities/{fmt?}', [CityController::class, 'cities']);

/*
|-------------------------------------------
| Postavlja troskove transporta u stavke lokalne korpe
|-------------------------------------------
*/
Route::get('api/cart/shipping/costs',[ShippingController::class, 'apiSetCosts'])->name('api.cart.shipping.set-costs');
/*
|-------------------------------------------
| Vrednost transporta za stavke lokalne korpe (samo za prikaz)
|-------------------------------------------
*/
Route::get('api/shipping/costs', [ShippingController::class, 'apiGetCosts'])->name('api.shipping.get-costs');
Route::post('api/cart/shippingToPartner', [CartController::class, 'apiSetShippingToPartner'])->name('api.cart.shippingToPartner');
Route::post('api/cart/shippingToAddress', [CartController::class, 'apiSetShippingToAddress']);

/*
 * Return values for shipping and installation costs
 */
Route::post('api/cart/costs',[CartController::class, 'costs'])->name('api.cart.costs');

/**
 * Partner (deprecated)
 * Member
 */

Route::get('member/{id}/{name?}', [MemberController::class, 'show'])->name('member.show');

Route::get('api/dt/member-price-list/tyres-services/{id}/{vehicle_category}/{diameter}',
    [MemberPriceListController::class, 'apiDtTyresServices'])->name('api.member-price-list.tyres-services');

Route::get('api/dt/member-price-list/tyres-other-services/{id}',
    [MemberPriceListController::class, 'apiDtTyresOtherServices'])->name('api.member-price-list.tyres-other-services');

Route::get('api/dt/member-price-list/wheel-alignment-services/{id}/{vehicle_category}/{diameter}',
    [MemberPriceListController::class, 'apiDtWheelAlignmentServices'])->name('api.member-price-list.wheel-alignment-services');

Route::get('api/dt/member-price-list/wheel-alignment-other-services/{id}',
    [MemberPriceListController::class, 'apiDtWheelAlignmentOtherServices'])->name('api.member-price-list.wheel-alignment-other-services');

Route::get('api/dt/member-information/frontend/{id}',
    [MemberInformationController::class, 'apiDtFrontend'])->name('api.dt.member-information.frontend');

Route::get('api/partner/locator', [PartnerController::class, 'searchNearestApi'])->name('api.partner.locator');

Route::get('api/partner/shipping-more-info', [PartnerController::class, 'apiShippingPartnerMoreInfo'])->name('api.partner.shipping-more-info');


/**
 * Contact form message
 */
Route::get('contact-form/create', [ContactFormMessageController::class, 'create'])->name('home.contact-form-message.create');
Route::post('contact-form', [ContactFormMessageController::class, 'store'])->name('home.contact-form-message.store');


/**
 * Callback request
 *	Ovo dolazi sa homepage-a
 */

Route::post('callback-request', [CallbackRequestController::class, 'store'])->name('home.callback-request.store');
Route::get('callback-request/create', [CallbackRequestController::class, 'create'])->name('home.callback-request.create');


/*
|--------------------------------------------------------------------------
| Authorized access
|--------------------------------------------------------------------------
|
|
|
*/

Route::middleware(['auth'])->group(function() {

    /**
     * Users Profile
     */

    Route::get('profile', [ProfileController::class, 'show'])->name('profile.show');

    Route::put('profile/{id}', [ProfileController::class, 'update'])->name('profile.update');

    Route::put('api/account/change/pwd', [ProfileController::class, 'changePassword'])->name('api.profile.change_password');
    Route::post('api/account/disable', [ProfileController::class, 'disableAccount'])->name('api.profile.disable_account');

    /**
     * Addresses
     */

    Route::resource('address', 'AddressController');
    Route::get('api/dt/address', [AddressController::class, 'datatablesApi'])->name('api.dt.address');
    Route::get('api/user/addresses', [AddressController::class, 'userAddressesApi'])->name('api.user.addresses');

    /**
     *	User orders
     */
    Route::get('api/dt/user/orders', [OrderController::class, 'apiUserOrders'])->name('api.user.orders');

    /**
     *  	Profil korisnika: adrese
     */
    Route::any('profile/address/save', [AddressController::class, 'saveUserAddress']);
    Route::any('profile/address/delete', [AddressController::class, 'delAddress']);
    Route::get('profile/address/{id}/{fmt?}', [AddressController::class, 'getAddressById']);
    /**
     *  	Profil korinsnika: osnovni podaci
     *  	Profil korinsnika: vozila
     *  	Profil korinsnika: odabrani servis
     *  	Profil korinsnika: porudzbenice
     */
    Route::post('/profile/update', [ProfileController::class, 'updateInfo']);
    Route::post('/profile/password', [ProfileController::class, 'changePassword']);
    Route::any('/profile/deactivate', [ProfileController::class, 'deactivate']);
    Route::any('/profile/vehicle/{vid}/update', [ProfileController::class, 'updateVehicle']);
    Route::any('/profile/vehicle/{vid}/delete', [ProfileController::class, 'deleteVehicle']);
    Route::any('/profile/preferred-partner/delete', [ProfileController::class, 'deletePreferredPartner']);
    Route::any('/profile/preferred-partner/{id}', [ProfileController::class, 'setPreferredPartner']);

    Route::get('turnover/autocomplete', [AdminController::class, 'turnoverAutocompletes']);


    /**
     * Checkout
     */
    Route::post('/checkout/start', [CheckoutController::class, 'start']);
    Route::post('/checkout/payment/start', [CheckoutController::class, 'paymentStart']);
    Route::get('/checkout/payment/execute/{order_id}/{checkout_id}', [CheckoutController::class, 'paymentExecute'])->name('checkout.payment.execute');
    Route::get('/checkout/payment/result', [CheckoutController::class, 'paymentResult']);
    Route::post('/checkout/payment/method-change', [CheckoutController::class, 'paymentMethodChange']);
    Route::get('/checkout/instructions/{order_id}', [CheckoutController::class, 'paymentInstructions'])->name('checkout.payment.instructions');
    Route::get('/checkout/thanks', [CheckoutController::class, 'thanks']);
});

Route::post('/api/shipping/subdomain', [ShippingController::class, 'apiSubdomainInfo']);


Route::middleware(['admin'])->group(function() {

    /**
     * Admin Home - dashboard
     */
    Route::get('/admin', [AdminController::class, 'index'])->name('admin');

    /**
     * Users
     */
    Route::get('admin/users/{role?}', [UserController::class, 'adminIndex'])->name('admin.users.index');
    Route::get('admin/api/dt/users/{role}', [UserController::class, 'apiDatatablesUsersByRole'])->name('admin.api.dt.users.role');


    Route::get('admin/profile', [ProfileController::class, 'show'])->name('admin.profile');
    Route::get('admin/profile/{user_id}/edit', [ProfileController::class, 'adminEdit'])->name('admin.profile.edit');

    Route::put('admin/profile/user/{user_id}', 	[ProfileController::class, 'userUpdate'])->name('admin.profile.user.update');
    Route::put('admin/profile/customer/{user_id}', [ProfileController::class, 'customerUpdate'])->name('admin.profile.customer.update');
    Route::put('admin/profile/address/{user_id}', [ProfileController::class, 'addressUpdate'])->name('admin.profile.address.update');
    Route::put('admin/profile/password/{user_id}', [ProfileController::class, 'passwordUpdate'])->name('admin.profile.password.update');

    Route::get('admin/api/dt/profile/activity/{user_id}', [UserActivityController::class, 'apiDatatablesGmxUser'])->name('admin.api.dt.profile.activity');


    /**
     * Carousel
     */
    Route::get('admin/carousel', [CarouselController::class, 'index'])->name('admin.carousel.index');
    Route::get('admin/carousel/create',	[CarouselController::class, 'create'])->name('admin.carousel.create');
    Route::post('admin/carousel', [CarouselController::class, 'store'])->name('admin.carousel.store');
    Route::get('admin/carousel/{id}', [CarouselController::class, 'edit'])->name('admin.carousel.edit');
    Route::put('admin/carousel/{id}', [CarouselController::class, 'update'])->name('admin.carousel.update');
    Route::delete('admin/carousel/{id}', [CarouselController::class, 'destroy'])->name('admin.carousel.delete');
    Route::get('admin/api/dt/carousel/{active}', [CarouselController::class, 'apiDatatables'])->name('admin.api.dt.carousel');

    Route::get('admin/pictures/carousel', [CarouselController::class, 'indexPictures']);
    Route::post('admin/pictures/carousel', [CarouselController::class, 'uploadPictures']);
    Route::get('admin/pictures/carousel/delete/{pic}', [CarouselController::class, 'deletePicture']);

    /**
     * Elasticsearch administracija
     */

    Route::get('/admin/es', [ElasticController::class, 'index'])->name('admin.elastic.index');
    Route::get('/admin/es/status', [ElasticController::class, 'indexInfo'])->name('admin.elastic.status');
    Route::post('/admin/es/index', [ElasticController::class, 'indexCreate'])->name('admin.elastic-index.create');
    Route::delete('/admin/es/index', [ElasticController::class, 'indexDelete'])->name('admin.elastic-index.delete');
    Route::post('/admin/es/type', [ElasticController::class, 'typeCreate'])->name('admin.elastic-type.create');
    Route::delete('/admin/es/type', [ElasticController::class, 'typeDelete'])->name('admin.elastic-type.delete');

    /***
     * Menu administration
     */

    Route::get('/admin/menu', [MenuController::class, 'index'])->name('admin.menu.index');
    Route::get('/admin/menu/create', [MenuController::class, 'create'])->name('admin.menu.create');
    Route::post('/admin/menu', [MenuController::class, 'store'])->name('admin.menu.store');
    Route::get('/admin/menu/{id}/edit', [MenuController::class, 'edit'])->name('admin.menu.edit');
    Route::put('/admin/menu/{id}', [MenuController::class, 'update'])->name('admin.menu.update');
    Route::delete('/admin/menu/{id}', [MenuController::class, 'destroy'])->name('admin.menu.delete');
    Route::get('admin/api/dt/menu', [MenuController::class, 'apiDatatables'])->name('admin.api.dt.menu.');
    Route::get('admin/api/menu/items', [MenuController::class, 'apiItems'])->name('admin.api.menu.items');

    /**
     * Membership request
     */
    Route::get('/admin/membership-request/{status}', [MembershipRequestController::class, 'index'])->name('admin.membership-request.index-status');
    Route::get('/admin/membership-request/{id}/{status}/show', [MembershipRequestController::class, 'show'])->name('admin.membership-request.show-status');
    Route::get('/admin/membership-request/{id}/edit', [MembershipRequestController::class, 'edit'])->name('admin.membership-request.edit');
    Route::put('/admin/membership-request/{id}', [MembershipRequestController::class, 'update'])->name('admin.membership-request.update');
    Route::delete('/admin/membership-request/{id}', [MembershipRequestController::class, 'destroy'])->name('admin.membership-request.delete');

    Route::get('admin/api/dt/membership-request/{status}', 	[MembershipRequestController::class, 'apiDatatables'])->name('admin.api.dt.membership-request.status');
    Route::get('admin/api/count/membership-request/{status}', [MembershipRequestController::class, 'apiCount'])->name('admin.api.count.membership-request.opened');
    Route::post('admin/api/approve/membership-request/{id}/{partnerId}', [MembershipRequestController::class, 'apiApprove'])->name('admin.api.approve.membership-request');

    /**
     * product review
     */

    Route::get('admin/product_review/{status}', [ProductReviewController::class, 'index'])->name('admin.product-review.status');
    Route::get('admin/product_review/{id}/edit', [ProductReviewController::class, 'edit'])->name('admin.product-review.edit');
    Route::put('admin/product_review/{id}', [ProductReviewController::class, 'update'])->name('admin.product-review.update');
    Route::get('admin/api/dt/product_review/{status}', [ProductReviewController::class, 'apiDatatables'])->name('admin.api.dt.product-review');


    /**
     * Orders admin
     */
    Route::get('admin/orders/{status}', [OrderController::class, 'index'])->name('admin.orders.index.status');
    Route::get('admin/api/dt/orders/{status}', [OrderController::class, 'apiDatatables'])->name('admin.api.dt.orders');
    Route::get('admin/api/count/orders/{period}', [OrderController::class, 'apiCount'])->name('admin.api.count.orders.period');
    /**
     * Orders payment
     */
    Route::get('admin/orders-payment/create', [OrderPaymentController::class, 'create'])->name('admin.orders-payment.create');
    Route::post('admin/orders-payment', [OrderPaymentController::class, 'store'])->name('admin.orders-payment.store');
    Route::get('admin/api/dt/orders-payment/{orderId}', [OrderPaymentController::class, 'apiDatatables'])->name('admin.api.dt.orders-payment');

    /**
     * PaymentGateway admin
     */
    Route::get('admin/payment-gateway', [PaymentGatewayController::class, 'index'])->name('admin.payment-gateway.index');
    Route::get('admin/api/dt/payment-gateway', [PaymentGatewayController::class, 'apiDatatables'])->name('admin.api.dt.payment-gateway');
    Route::get('admin/api/dt/payment-gateway/details/{orderId}', [PaymentGatewayController::class, 'apiDatatablesItems'])->name('admin.api.dt.payment-gateway-items');

    /**
     * Backoffice admin
     */
    Route::get('admin/backoffice/{orderId}', [BackofficeController::class, 'index']);
    Route::post('admin/backoffice/operations', [BackofficeController::class, 'store']);


    /**
     * MerchantAPI
     */
    Route::get('admin/api/merchant-api/health/{merchantId}', [MerchantApiController::class, 'apiHealth'])->name('admin.api.merchant-api.health');
    Route::get('api/count/products', [ProductController::class, 'apiCount'])->name('api.count.products');

    /**
     * Callback request
     */
    Route::get('admin/callback_request/{status}', [CallbackRequestController::class, 'index'])->name('admin.callback-request.index');
    Route::get('admin/callback_request/{id}/edit', [CallbackRequestController::class, 'edit'])->name('admin.callback-request.edit');
    Route::put('admin/callback_request/{id}', [CallbackRequestController::class, 'update'])->name('admin.callback-request.update');
    Route::get('admin/api/dt/callback_request/{status}', [CallbackRequestController::class, 'apiDatatables'])->name('admin.api.dt.callback-request.status');
    Route::get('admin/api/count/callback_request/{status}', [CallbackRequestController::class, 'apiCount'])->name('admin.api.count.callback-request.status');

    /**
     * Contact forma
     */
    Route::get('admin/contact_form_message/{status}', [ContactFormMessageController::class, 'index'])->name('admin.contact-form-message.index-status');
    Route::get('admin/contact_form_message/{id}/edit', [ContactFormMessageController::class, 'edit'])->name('admin.contact-form-message.edit');
    Route::put('admin/contact_form_message/{id}', [ContactFormMessageController::class, 'update'])->name('admin.contact-form-message.update');
    Route::get('admin/contact_form_message/{id}/show', [ContactFormMessageController::class, 'show'])->name('admin.contact-form-message.show');
    Route::get('admin/api/dt/contact_form_message/{status}', [ContactFormMessageController::class, 'apiDatatables'])->name('admin.api.dt.contact-form-message.status');
    Route::get('admin/api/count/contact_form_message/{status}', [ContactFormMessageController::class, 'apiCount'])->name('admin.api.count.contact-form-message.status');


    /**
     * Partners
     */
    Route::get('admin/partners', [PartnerController::class, 'index'])->name('admin.partners.index');
    Route::get('crm/partners', [PartnerController::class, 'index'])->name('crm.partners.index');

    Route::get('admin/partners/{id}/edit', [PartnerController::class, 'edit'])->name('admin.partners.edit');
    Route::get('crm/partners/{id}/edit', [PartnerController::class, 'edit'])->name('crm.partners.edit');

    Route::put('admin/partners/{id}', [PartnerController::class, 'update'])->name('admin.partners.update');
    Route::put('crm/partners/{id}', [PartnerController::class, 'update'])->name('crm.partners.update');

    Route::get('admin/api/dt/partners', [PartnerController::class, 'apiDatatables'])->name('admin.api.dt.partners');
    Route::get('crm/api/dt/partners', [PartnerController::class, 'apiDatatables'])->name('crm.api.dt.partners');

    /**
     * Partner's price list
     */
    Route::get('admin/api/dt/partners-price-list-gmx/{partnerId}', [PartnerPriceListController::class, 'apiDatatablesAdminGmx'])->name('admin.api.dt.partners-price-list-gmx');
    Route::post('admin/api/editablePost/partners-price-list', [PartnerPriceListController::class, 'apiEditablePost'])->name('admin.api.editablePost.partners-price-list');


    /**
     * Partner about
     */

    Route::get('admin/crm/partner-about', [PartnerAboutController::class, 'index'])->name('admin.partners.partner-about');
    Route::get('admin/crm/partner-about/create', [PartnerAboutController::class, 'create'])->name('admin.partners.partner-about.create');
    Route::post('admin/crm/partner-about/{id}', [PartnerAboutController::class, 'store'])->name('admin.partners.partner-about.store');
    Route::get('admin/crm/partner-about/{id}/edit', [PartnerAboutController::class, 'edit'])->name('admin.partners.partner-about.edit');
    Route::put('admin/crm/partner-about/{id}', [PartnerAboutController::class, 'update'])->name('admin.partner-about.update');


    /**
     ****************************************************************************************************************************************
     * CRM
     ****************************************************************************************************************************************
     */


    /**
     * CRM Project
     */
    Route::get('crm/projects', [ProjectController::class, 'index'])->name('crm.projects.index');
    Route::get('crm/projects/create', [ProjectController::class, 'create'])->name('crm.projects.create');
    Route::post('crm/projects', [ProjectController::class, 'store'])->name('crm.projects.store');
    Route::get('crm/projects/{id}/edit', [ProjectController::class, 'edit'])->name('crm.projects.edit');
    Route::put('crm/projects/{id}', [ProjectController::class, 'update'])->name('crm.projects.update');
    Route::delete('crm/projects/{id}', [ProjectController::class, 'destroy'])->name('crm.projects.destroy');
    Route::get('crm/api/dt/projects', [ProjectController::class, 'apiDatatables'])->name('crm.api.dt.projects');
    Route::get('crm/api/tree-items/projects', [ProjectController::class, 'apiItems'])->name('crm.api.tree-items.projects');

    /**
     * Partner working hours
     */
    Route::get('crm/partner/working-hours/{partnerId}',	[WorkingHoursController::class, 'edit'])->name('crm.partner.working-hours.edit');
    Route::get('crm/api/dt/partner/working-hours/{partnerId}', [WorkingHoursController::class, 'apiDatatables'])->name('crm.api.dt.partner-working-hours');
    Route::post('crm/api/editablePost/partner/working-hours/', [WorkingHoursController::class, 'apiEditablePost'])->name('crm.api.editablePost.partner-working-hours');


    /**
     * Member working hours
     */
    Route::get('crm/member-working-hours/{memberId}', [MemberWorkingHoursController::class, 'edit'])->name('crm.member-working-hours.edit');
    Route::get('crm/api/dt/member-working-hours/{memberId}', [MemberWorkingHoursController::class, 'apiDatatables'])->name('crm.api.dt.member-working-hours');
    Route::post('crm/api/editablePost/member-working-hours/', [MemberWorkingHoursController::class, 'apiEditablePost'])->name('crm.api.editablePost.member-working-hours');


    /**
     * CRM Members
     */

    Route::get('crm/project/members/{projectId}', [MemberController::class, 'projectIndex'])->name('crm.project.members.index');

    Route::get('crm/member/create',	[MemberController::class, 'create'])->name('crm.member.create');
    Route::post('crm/member', [MemberController::class, 'store'])->name('crm.member.store');
    Route::get('crm/member/{id}/edit', [MemberController::class, 'edit'])->name('crm.member.edit');
    Route::put('crm/member/{id}', [MemberController::class, 'update'])->name('crm.member.update');
    Route::delete('crm/member/{id}', [MemberController::class, 'destroy'])->name('crm.member.destroy');
    Route::get('crm/api/dt/member/{projectId}', [MemberController::class, 'apiDatatables'])->name('crm.api.dt.member');


    Route::get('crm/api/dt/member/users/{projectId}', [MemberController::class, 'apiDtUsers'])->name('crm.api.dt.member.users');
    Route::get('crm/api/dt/member/partners/{projectId}', [MemberController::class, 'apiDtPartners'])->name('crm.api.dt.member.partners');
    Route::get('crm/api/dt/member/partners-users/{projectId}', [MemberController::class, 'apiDtPartnersUsers'])->name('crm.api.dt.member.partners-users');
    Route::post('crm/api/add_erp_partner_id/member', [MemberController::class, 'apiAddMemberByErpPartnerId'])->name('crm.api.add_erp_partner_id.member');

    /**
     * CRM Project Member information
     */
    Route::get('crm/member-information/{id}/edit', [MemberInformationController::class, 'edit'])->name('crm.member-information.edit');
    Route::get('crm/api/dt/member-information/{id}/edit', [MemberInformationController::class, 'apiDatatables'])->name('crm.api.dt.member-information');
    Route::post('crp/api/editable/member-information', [MemberInformationController::class, 'apiEditable'])->name('crm.api.editable.member-information');

    /**
     * Member Payment method
     */
    Route::put('crm/member-payment-method/{memberId}', [MemberPaymentMethodController::class, 'update'])->name('crm.member-payment-method');

    /**
     * Project member price list
     */
    Route::get('crm/member-price-list/{memberId}/edit', [MemberPriceListController::class, 'edit'])->name('crm.member-price-list.edit');
    Route::post('crm/api/editablePost/member-price-list', [MemberPriceListController::class, 'apiEditablePost'])->name('crm.editablePost.member-price-list');
    Route::get('crm/api/dt/member-price-list/{memberId}', [MemberPriceListController::class, 'apiDatatables'])->name('crm.api.dt.member-price-list');


    /**
     * Member page
     */
    Route::get('crm/member-page/{memberId}/edit', [MemberPageController::class, 'edit'])->name('crm.member-page.edit');
    Route::post('crm/member-page', [MemberPageController::class, 'store'])->name('crm.member-page.store');
    Route::put('crm/member-page/{id}', [MemberPageController::class, 'update'])->name('crm.member-page.update');
    Route::delete('crm/member-page/{id}', [MemberPageController::class, 'destroy'])->name('crm.member-page.destroy');

    /**
     * Member address
     */
    Route::get('crm/member-address/{memberId}', [MemberAddressController::class, 'index'])->name('crm.member-address.index');
    Route::get('crm/api/dt/member-address/{memberId}', [MemberAddressController::class, 'apiDatatables'])->name('crm.api.dt.member-address');
    Route::post('crm/api/editablePost/member-address', [MemberAddressController::class, 'apiEditablePost'])->name('crm.editablePost.member-address');
    Route::put('crm/member-address/{memberId}', [MemberAddressController::class, 'update'])->name('crm.member-address.update');

    /**
     * Crm Address
     */
    Route::get('crm/address', [CrmAddressController::class, 'index'])->name('crm.address.index');
    Route::get('crm/address/create', [CrmAddressController::class, 'create'])->name('crm.address.create');
    Route::post('crm/address', [CrmAddressController::class, 'store'])->name('crm.address.store');
    Route::get('crm/address/{id}/edit', [CrmAddressController::class, 'edit'])->name('crm.address.edit');
    Route::put('crm/address/{id}', [CrmAddressController::class, 'update'])->name('crm.address.update');


    /**
     * Information
     */
    Route::get('crm/information', [InformationController::class, 'index'])->name('crm.information.index');
    Route::get('crm/information/create', [InformationController::class, 'create'])->name('crm.information.create');
    Route::post('crm/information', [InformationController::class, 'store'])->name('crm.information.store');
    Route::get('crm/information/{id}/edit', [InformationController::class, 'edit'])->name('crm.information.edit');
    Route::put('crm/information/{id}', [InformationController::class, 'update'])->name('crm.information.update');
    Route::delete('crm/information/{id}', [InformationController::class, 'destroy'])->name('crm.information.destroy');
    Route::get('crm/api/dt/information', [InformationController::class, 'apiDatatables'])->name('crm.api.dt.information');

    /**
     * Information groups
     */
    Route::get('crm/information-groups', [InformationGroupController::class, 'index'])->name('crm.information-groups.index');

    /**
     * Project Information templates
     */
    Route::get('crm/project-information-templates', [ProjectInformationTemplatesController::class, 'index'])->name('crm.project-information-templates.index');
    Route::get('crm/api/dt/project-information-templates', [ProjectInformationTemplatesController::class, 'apiDatatables'])->name('crm.api.dt.project-information-templates');


    /**
     * Logo
     */
    Route::post('crm/logo/upload', [LogoController::class, 'upload'])->name('crm.logo.upload');
    Route::post('crm/logo/delete/{id}', [LogoController::class, 'delete'])->name('crm.logo.delete');

    /**
     * Cover
     */
    Route::post('crm/cover/upload', [CoverController::class, 'upload'])->name('crm.cover.upload');
    Route::post('crm/cover/delete/{id}', [CoverController::class, 'delete'])->name('crm.cover.delete');

    /**
     * Photos
     */
    Route::post('crm/photo/upload', [PhotoController::class, 'upload'])->name('crm.photo.upload');
    Route::post('crm/photo/delete/{id}', [PhotoController::class, 'delete'])->name('crm.photo.delete');
    Route::get('crm/migrate-photos', [PhotoController::class, 'migratePhotos']);

    /**
     * Users
     */
    Route::get('crm/api/s2/users', [UserController::class, 'apiSelect2Users'])->name('api.s2.users');

    /**
     * Member user role
     */
    Route::post('crm/member-user-role', [MemberUserRoleController::class, 'store'])->name('crm.member-user-role.store');
    Route::delete('crm/member-user-role/{id}', [MemberUserRoleController::class, 'destroy'])->name('crm.member-user-role.delete');
});
