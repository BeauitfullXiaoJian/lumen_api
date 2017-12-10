<?php

namespace App\Api\Services;

use App\Api\Contracts\ApiContract;
use App\Api\Traits\ApiTrait;

class ApiService implements ApiContract
{
    use ApiTrait;

    public function __construct(){ }

    function checkParams($params = [], $exp = [], $formate = [], $message = []){

        $params = $this->getParams($params, $exp, $formate, $message);

        if($params['result']){
            return $params['datas'];
        }
        else{
            printf(json_encode($params));
            exit();
        }
    }
}
