<?php
Route::group(
    ['prefix' => 'products/'],
    function () {
        Route::group(
            ['middleware' => 'auth:api'],
            function () {
                Route::post('/addProduct', 'Products\ProductsController@addProduct');
                Route::post('/deleteProduct', 'Products\ProductsController@deleteProduct');
                Route::post('/updateProduct', 'Products\ProductsController@updateProduct');
                Route::post('/addImages', 'Products\ProductsController@addImages');
                Route::post('/deleteImage', 'Products\ProductsController@deleteImage');
                Route::post('/sortProducts', 'Products\ProductsController@sortProducts');
                Route::post('/toggleSpecialOffer', 'Products\ProductsController@toggleSpecialOffer');
            });
        Route::post('/addProductCategory', 'Products\ProductsController@addProductCategory');
        Route::post('/getAllProductsForSorting', 'Products\ProductsController@getAllProductsForSorting');
        Route::post('/getAllProducts', 'Products\ProductsController@getAllProducts');
        Route::post('/getProduct', 'Products\ProductsController@getProduct');
    });
