<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Hash Driver
    |--------------------------------------------------------------------------
    |
    | This option controls the default hash driver that will be used to hash
    | passwords for your application. By default, the bcrypt algorithm is
    | used; however, you remain free to modify this option if you wish.
    |
    | Supported: "bcrypt", "argon", "argon2id"
    |
    */

    'driver' => 'bcrypt',

    /*
    |--------------------------------------------------------------------------
    | Bcrypt Options
    |--------------------------------------------------------------------------
    |
    | Here you may specify the configuration options for the Bcrypt hasher,
    | such as the number of rounds used to hash the given password. This
    | will allow you to determine how long it takes to calculate the hash.
    |
    */

    'bcrypt' => [
        'rounds' => (int) (env('BCRYPT_ROUNDS') ?: 12),
        'verify' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Argon Options
    |--------------------------------------------------------------------------
    |
    | Here you may specify the configuration options for the Argon hasher,
    | such as the memory, threads, and time factors to use. These options
    | will allow you to customize how your passwords are being hashed.
    |
    */

    'argon' => [
        'memory' => (int) (env('ARGON_MEMORY') ?: 65536),
        'threads' => (int) (env('ARGON_THREADS') ?: 1),
        'time' => (int) (env('ARGON_TIME') ?: 4),
        'verify' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Rehash On Login
    |--------------------------------------------------------------------------
    |
    | When set to true, passwords will be checked to see if they need to be
    | rehashed according to the current configuration when a user logs in.
    | If they do, the password will automatically be updated in the database.
    |
    */

    'rehash_on_login' => true,

];
