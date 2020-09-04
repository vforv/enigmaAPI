<?php


Route::group(
    ['prefix' => 'pages/'],
    function () {
        Route::group(
            ['middleware' => 'auth:api'],
            function () {
                Route::post('/addCategory', 'Pages\PagesCategoryController@addCategory');
                Route::post('/deleteCategory', 'Pages\PagesCategoryController@deleteCategory');
                Route::post('/editCategory', 'Pages\PagesCategoryController@editCategory');
            });

        Route::post("/allCategories", 'Pages\PagesCategoryController@allCategories');
    });
