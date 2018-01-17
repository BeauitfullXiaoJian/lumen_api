<?php

namespace App\Api\Contracts;

interface CsrfContract
{
    // update csrf token
    public function update($token_key = 'XSRF-TOKEN');

    // check csrf token
    public function check($token_key = 'XSRF-TOKEN');
}
