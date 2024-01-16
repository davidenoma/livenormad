<?php

use Botble\Base\Facades\AdminHelper;
use Botble\Ecommerce\Models\Product;
use Botble\Slug\Facades\SlugHelper;
use Botble\Theme\Facades\Theme;
use Illuminate\Support\Facades\Route;

Route::group(['namespace' => 'Botble\Ecommerce\Http\Controllers'], function () {
    AdminHelper::registerRoutes(function () {
        Route::group(['prefix' => 'reviews', 'as' => 'reviews.'], function () {
            Route::match(['GET', 'POST'], '/', [
                'as' => 'index',
                'uses' => 'ReviewController@index',
                'permission' => 'reviews.index',
            ]);

            Route::get('create', [
                'as' => 'create',
                'uses' => 'ReviewController@create',
                'permission' => 'reviews.create',
            ]);

            Route::post('create', [
                'as' => 'store',
                'uses' => 'ReviewController@store',
                'permission' => 'reviews.create',
            ]);

            Route::get('/ajax-search-customers', [
                'as' => 'ajax-search-customers',
                'uses' => 'ReviewController@ajaxSearchCustomers',
                'permission' => 'reviews.create',
            ]);

            Route::get('/ajax-search-products', [
                'as' => 'ajax-search-products',
                'uses' => 'ReviewController@ajaxSearchProducts',
                'permission' => 'reviews.create',
            ]);

            Route::get('{review}', [
                'as' => 'show',
                'uses' => 'ReviewController@show',
                'permission' => 'reviews.index',
            ]);

            Route::delete('{review}', [
                'as' => 'destroy',
                'uses' => 'ReviewController@destroy',
                'permission' => 'reviews.destroy',
            ]);

            Route::post('{review}/publish', [
                'as' => 'publish',
                'uses' => 'PublishedReviewController@store',
                'permission' => 'reviews.publish',
            ]);

            Route::post('{review}/unpublish', [
                'as' => 'unpublish',
                'uses' => 'PublishedReviewController@destroy',
                'permission' => 'reviews.publish',
            ]);
        });
    });
});

Theme::registerRoutes(function () {
    Route::group([
        'namespace' => 'Botble\Ecommerce\Http\Controllers\Fronts',
        'middleware' => ['customer'],
    ], function () {
        Route::post('review/create', [
            'as' => 'public.reviews.create',
            'uses' => 'ReviewController@store',
        ]);

        Route::delete('review/delete/{id}', [
            'as' => 'public.reviews.destroy',
            'uses' => 'ReviewController@destroy',
        ])->wherePrimaryKey();

        Route::get(SlugHelper::getPrefix(Product::class, 'products') . '/{slug}/review', [
            'uses' => 'ReviewController@getProductReview',
            'as' => 'public.product.review',
            'middleware' => 'customer',
        ]);
    });
});