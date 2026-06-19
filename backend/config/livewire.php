<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Livewire Asset URL
    |--------------------------------------------------------------------------
    |
    | This value is used to prefix the Livewire assets, such as the compiled
    | JavaScript file and the update URI. This is especially useful when
    | the application is running in a subdirectory.
    |
    */
    'asset_url'  => null,
    /*
    |--------------------------------------------------------------------------
    | Livewire Update URI
    |--------------------------------------------------------------------------
    |
    | This value is used to specify the URI that Livewire will use to send
    | updates to the server. By default, this is set to '/livewire/update'.
    |
    */

    'update_uri' => '/livewire/update',

    /*
    |--------------------------------------------------------------------------
    | Livewire Script Params
    |--------------------------------------------------------------------------
    |
    | This value is used to specify any parameters that should be appended
    | to the Livewire script tag.
    |
    */

    'script_params' => [],

    /*
    |--------------------------------------------------------------------------
    | Livewire User Agent
    |--------------------------------------------------------------------------
    |
    | This value is used to specify the user agent that Livewire will use
    | when making requests to the server.
    |
    */

    'user_agent' => 'Livewire',

];
