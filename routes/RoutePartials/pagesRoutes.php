<?php
Route::group(
    ['prefix' => 'pages/'],
    function () {
        Route::group(
            ['middleware' => 'auth:api'],
            function () {
                Route::post('/deletePage', 'Pages\PagesController@deletePage');
                Route::post('/addPage', 'Pages\PagesController@addPage');
                Route::post('/updatePage', 'Pages\PagesController@updatePage');
                Route::post('/addImages', 'Pages\PagesController@addImages');
                Route::post('/deleteImage', 'Pages\PagesController@deleteImage');
                Route::post('/sortImages', 'Pages\PagesController@sortImages');
            });
        Route::post('/getPage', 'Pages\PagesController@getPage');
        Route::post('/getAllPages', 'Pages\PagesController@getAllPages');
    });
