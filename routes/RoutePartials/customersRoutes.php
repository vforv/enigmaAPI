<?php

Route::group(
    ['prefix' => 'customers/'],
    function () {
        Route::group(
            ['middleware' => 'auth:api'],
            function () {
                Route::post('/register', 'Customers\CustomersController@register');
                Route::post('/handleDiscount', 'Customers\CustomersController@handleDiscount');
            });
        Route::post('/register', 'Customers\CustomersController@register');
        Route::post('/login', 'Customers\CustomersController@login');
        Route::post('/getAllCustomers', 'Customers\CustomersController@getAllCustomers');

    });
