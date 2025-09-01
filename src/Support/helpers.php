<?php

use Knackline\Listmonk\Facades\Listmonk;

if (! function_exists('listmonk')) {
    /**
     * Get the Listmonk client instance.
     *
     * @return \Knackline\Listmonk\ListmonkClient|mixed
     */
    function listmonk()
    {
        if (func_num_args() === 0) {
            return app('listmonk');
        }

        return Listmonk::__call(
            'request', 
            [
                'method' => func_get_arg(0),
                'endpoint' => func_get_arg(1),
                'data' => func_get_arg(2) ?? []
            ]
        );
    }
}
