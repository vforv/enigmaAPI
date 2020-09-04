<?php
Route::group(
    ['prefix' => 'products/'],
    function () {
        Route::group(
            ['middleware' => 'auth:api'],
            function () {
                Route::post('/addProductCategory', 'Products\ProductsController@addProductCategory');
                Route::post('/sortCategories', 'Products\ProductsController@sortCategories');
                Route::post('/deleteProductCategory', 'Products\ProductsController@deleteProductCategory');
                Route::post('/editProductCategory', 'Products\ProductsController@editProductCategory');
            });
        Route::post('/getAllProductCategory', 'Products\ProductsController@getAllProductCategory');
    });
