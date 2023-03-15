<?php

use System\router\web\Route;

Route::any('400', function () {
    view('err.400');
});
Route::any('401', function () {
    view('err.401');
});
Route::any('403', function () {
    view('err.403');
});
Route::any('404', function () {
    view('err.404');
});
Route::any('408', function () {
    view('err.408');
});
Route::any('414', function () {
    view('err.414');
});
Route::any('419', function () {
    view('err.419');
});
Route::any('429', function () {
    view('err.429');
});
Route::any('500', function () {
    view('err.500');
});
Route::any('502', function () {
    view('err.502');
});
Route::any('503', function () {
    view('err.503');
});
Route::any('504', function () {
    view('err.504');
});