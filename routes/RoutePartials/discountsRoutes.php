<?php

Route::group(
    ['prefix' => 'discounts/'],
    function () {
        Route::group(
            ['middleware' => 'auth:api'],
            function () {
                Route::post('/addDiscount', 'Discounts\DiscountsController@addDiscount');
                Route::post('/deleteDiscount', 'Discounts\DiscountsController@deleteDiscount');
                Route::post('/editDiscount', 'Discounts\DiscountsController@editDiscount');
            });
        Route::post('/getAllDiscounts', 'Discounts\DiscountsController@getAllDiscounts');

    });
