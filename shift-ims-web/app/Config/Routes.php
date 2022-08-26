<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (is_file(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
//$routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'DashboardController::index');

$routes->group('customers', static function($routes) {
    $routes->get('', 'CustomerController::index');
    $routes->match(['get', 'post'], 'edit/(:num)', 'CustomerController::edit/$1');
    $routes->get('delete/(:num)', 'CustomerController::delete/$1');
});

$routes->group('suppliers', static function($routes) {
    $routes->get('', 'SupplierController::index');
    $routes->match(['get', 'post'], 'edit/(:num)', 'SupplierController::edit/$1');
    $routes->get('delete/(:num)', 'SupplierController::delete/$1');
});


$routes->group('product-categories', function($routes) {
    $routes->get('', 'ProductCategoryController::index');
    $routes->match(['get', 'post'], 'add', 'ProductCategoryController::edit/0');
    $routes->match(['get', 'post'], 'edit/(:num)', 'ProductCategoryController::edit/$1');
    $routes->get('delete/(:num)', 'ProductCategoryController::delete/$1');
});

$routes->group('products', function($routes) {
    $routes->get('', 'ProductController::index');
    $routes->match(['get', 'post'], 'edit/(:num)', 'ProductController::edit/$1');
    $routes->match(['get', 'post'], 'duplicate/(:num)', 'ProductController::duplicate/$1');
    $routes->get('delete/(:num)', 'ProductController::delete/$1');
});

$routes->group('service-orders', function($routes) {
    $routes->get('', 'ServiceOrderController::index');
    $routes->match(['get', 'post'], 'edit/(:num)', 'ServiceOrderController::edit/$1');
    $routes->match(['get', 'post'], 'duplicate/(:num)', 'ServiceOrderController::duplicate/$1');
    $routes->get('delete/(:num)', 'ServiceOrderController::delete/$1');
    $routes->get('view/(:num)', 'ServiceOrderController::view/$1');
});

$routes->group('stock-adjustments', function($routes) {
    $routes->get('', 'StockAdjustmentController::index');
    $routes->match(['get', 'post'], 'add', 'StockAdjustmentController::add');
});

$routes->group('purchase-orders', function($routes) {
    $routes->get('', 'PurchaseOrderController::index');
    $routes->match(['get', 'post'], 'add', 'PurchaseOrderController::add');
});

$routes->group('sales-orders', function($routes) {
    $routes->get('', 'SalesOrderController::index');
    $routes->match(['get', 'post'], 'add', 'SalesOrderController::add');
    $routes->get('view/(:num)', 'SalesOrderController::view/$1');
});

$routes->group('reports', function($routes) {
    $routes->get('daily-income-statement', 'ReportsController::dailyIncomeStatement');
    $routes->get('stock-assets', 'ReportsController::stockAssets');
    $routes->get('sales-by-category', 'ReportsController::salesByCategory');
});

$routes->group('stock-updates', function($routes) {
    $routes->get('', 'StockUpdateController::index');
    $routes->match(['get'], 'view/(:num)', 'StockUpdateController::view/$1');
    $routes->post('delete/(:num)', 'StockUpdateController::delete/$1');
});


/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
