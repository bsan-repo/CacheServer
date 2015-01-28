<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

Route::get('/', function()
{
	return View::make('hello');
});

// Route group for API versioning
Route::group(array('prefix' => 'auth', 'before' => 'auth.basic'), function()
{
	Route::post('author', 'AuthorController@saveAuthorData');
    //Route::resource('author', 'AuthorController');
    	/*
	Route::post('author', function()
    {
    	Route::resource('author', 'AuthorController');
    	//return "Post author  ----- ";
    });
	Route::get('author', function()
    {
    	//Route::resource('author', 'AuthorController');
    	return "Post author  ----- ";
    });
    return "Post auth group  - ---- ";*/
});

Route::group(array('prefix' => 'admin', 'before' => 'auth.basic'), function()
{

    Route::get('user', function()
    {
        return "ADMIN (GET) ------ ";
    });

    Route::post('user', function()
    {
        return "ADMIN (POST) ------ ";
    });

});

Route::post('foo/bar', function()
{
    return 'Foo Bar OK!!!';
});

Route::get('foo', array('https', function()
{
    return 'Must be over HTTPS';
}));

Route::get('/authtest', array('before' => 'auth.basic', function()
{
    return View::make('hello');
}));