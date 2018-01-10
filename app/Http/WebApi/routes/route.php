<?php
use App\Api\Contracts\ApiContract;
use App\Core\AuthContract;

$app->group(['prefix' => 'web'], function ($app) {
    include 'pay.php';
});