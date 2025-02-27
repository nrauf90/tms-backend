<?php

/**
 * getStatusCode
 *
 * @param  mixed $type
 * @param  mixed $status
 * @param  mixed $index
 * @return void
 * Description: Get Status Code and Description from Status Codes Config file.
 */
function getStatusCode($type,$status,$index=''){
    if($index!=''){
        return config('status_codes.'.$type.'.'.$status.'.'.$index);
    }
    return config('status_codes.'.$type.'.'.$status);
}