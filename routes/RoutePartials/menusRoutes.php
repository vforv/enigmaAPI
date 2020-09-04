<?php
Route::group(
    ['prefix' => 'menus/'],
    function () {
        Route::group(
            ['middleware' => 'auth:api'],
            function () {
                Route::post('/addMenu', 'Menus\MenusController@addMenu');
                Route::post('/addMenuItem', 'Menus\MenusController@addMenuItem');
                Route::post('/deleteMenu', 'Menus\MenusController@deleteMenu');
                Route::post('/sortMenus', 'Menus\MenusController@sortMenus');
                Route::post('/getAllParentMenus', 'Menus\MenusController@getAllParentMenus');
                Route::post('/deleteMenuItem', 'Menus\MenusController@deleteMenuItem');
                Route::post('/editMenu', 'Menus\MenusController@editMenu');
                Route::post('/editMenuItem', 'Menus\MenusController@editMenuItem');
            });
        Route::post('/getAllMenus', 'Menus\MenusController@getAllMenus');
        Route::post('/getAllMenuItems', 'Menus\MenusController@getAllMenuItems');
    });
