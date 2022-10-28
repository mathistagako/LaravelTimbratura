<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        "http://localhost:3000/registrazione",
        "http://localhost:3000/login",
        "http://localhost:3000/timbro",
        "http://localhost",
        "http://localhost/register",
        "http://localhost/login",
        "http://localhost/timbroEntrata",
        "http://localhost/timbroUscita",
        "http://localhost/getGiornate",
    ];
}
