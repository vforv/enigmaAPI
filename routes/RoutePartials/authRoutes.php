<?php
Route::group(['prefix' => 'auth/'],
    function () {
        Route::group(['middleware' => 'auth:api'],
            function () {
                Route::post('/authenticate', 'Auth\AuthController@authenticate');
            });
    });
