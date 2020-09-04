<?php
Route::group(
    ['prefix' => 'videos/'],
    function () {
        Route::group(
            ['middleware' => 'auth:api'],
            function () {
                Route::post('/addVideo', 'Videos\VideosController@addVideo');
                Route::post('/deleteVideo', 'Videos\VideosController@deleteVideo');
                Route::post('/editVideo', 'Videos\VideosController@editVideo');
            });
        Route::post('/getAllVideos', 'Videos\VideosController@getAllVideos');
    });
