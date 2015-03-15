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

////////////////////////////////////////////////////////////

// TEST JAVA CLIENT RECEIVE
Route::group(array('prefix' => 'client'), function()
{
    Route::post('author', 'AuthorsController@basicTest');
    Route::post('authorCache', 'AuthorsController@authorCache');
    Route::post('authorSave', 'AuthorsController@authorSave');
    Route::post('getAuthorToProcessFromServer', 'AuthorsController@getAuthorToProcessFromServer');
});

// Route group for API versioning
Route::group(array('prefix' => 'auth', 'before' => 'auth.basic'), function()
{
	Route::post('author', 'AuthorsController@saveAuthorData');
});

Route::group(array('prefix' => 'admin'), function()
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

// Test the database is up and running as expected
Route::get('/dbtest', function(){
   $name = '';
   try{
       DB::connection()->getDatabaseName();

       $name = Author::find(8)->name;

   }catch(Exception $e){
      return $e->getMessage().".     ";
   }
   return "Database connection ok.    ".$name;
});

Route::post('foo/bar', function()
{
    return 'Foo Bar OK!!!';
});

Route::get('/authtest', array('before' => 'auth.basic', function()
{
    return View::make('hello');
}));

Route::get('logout', "auth@logout");