<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Event::listen('Aacotroneo\Saml2\Events\Saml2LoginEvent', function (\Aacotroneo\Saml2\Events\Saml2LoginEvent $event) {
    $messageId = $event->getSaml2Auth()->getLastMessageId();
    // your own code preventing reuse of a $messageId to stop replay attacks
    $user = $event->getSaml2User();
    $userData = [
        'id' => $user->getUserId(),
        'attributes' => $user->getAttributes(),
        'assertion' => $user->getRawSamlAssertion()
    ];

    dd($userData);
    $laravelUser = 1;
    //find user by ID or attribute
    //if it does not exist create it and go on or show an error message
    Auth::login($laravelUser);
});

