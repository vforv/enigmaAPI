<?php

Route::group(
    ['prefix' => 'orders/'],
    function () {
        Route::group(
            ['middleware' => 'auth:api'],
            function () {
                Route::post('/addOrder', 'Orders\OrdersController@addOrder');
                Route::post('/deleteOrder', 'Orders\OrdersController@deleteOrder');
            });
        Route::post('/getAllOrders', 'Orders\OrdersController@getAllOrders');
        Route::post('/getOrder', 'Orders\OrdersController@getOrder');
        Route::post('/addOrder', 'Orders\OrdersController@addOrder');

    });
