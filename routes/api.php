<?php

require_once base_path("routes/RoutePartials/authRoutes.php");
require_once base_path("routes/RoutePartials/pagesRoutes.php");
require_once base_path("routes/RoutePartials/pageCategoryRoutes.php");
require_once base_path("routes/RoutePartials/videosRoutes.php");
require_once base_path("routes/RoutePartials/menusRoutes.php");
require_once base_path("routes/RoutePartials/productCategoryRoutes.php");
require_once base_path("routes/RoutePartials/productRoutes.php");
require_once base_path("routes/RoutePartials/customersRoutes.php");
require_once base_path("routes/RoutePartials/ordersRoutes.php");
require_once base_path("routes/RoutePartials/discountsRoutes.php");

Route::post('/register', 'User\UsersController@register');
Route::post('/login', 'User\UsersController@login');

Route::post('confirmRegistration', 'Mail\MailController@confirmRegistration');
Route::post('sendattachmentemail', 'Mail\MailController@attachment_email');
