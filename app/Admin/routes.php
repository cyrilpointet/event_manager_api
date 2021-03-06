<?php

use Illuminate\Routing\Router;

Admin::routes();

Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'as'            => config('admin.route.prefix') . '.',
], function (Router $router) {

    $router->get('/', 'HomeController@index')->name('home');
    $router->resource('users', UserController::class);
    $router->resource('teams', TeamController::class);
    $router->resource('invitations', InvitationController::class);
    $router->resource('happenings', HappeningController::class);
    $router->resource('comments', CommentController::class);
    $router->resource('surveys', SurveyController::class);
});
