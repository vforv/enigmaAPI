<?php

Route::get('/', function () {
    return view('welcome');
});
Route::get('/confirm/{token}', 'Customers\CustomersController@confirmUser');
Route::get('/pdfStyle', function () {
    return view("orderPDF");
});
Route::get('/test', 'Orders\OrdersController@renderPDF');

Route::get('{any}', function () {
    return view('welcome');
});

Route::get('/{any}', function () {
    return view('welcome');
})->where('any', '.*');


