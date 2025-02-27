<?php
return [
    'BASIC' => [
        'SUCCESS' => array(
            'code' => 0,
            'description' => 'Operation performed successfully',
        ),
        'NOT_FOUND' => array(
            'code' => 1,
            'description' => 'Entity or Resource not found',
        ),
        'ACCESS_DENIED' => array(
            'code' => 97,
            'description' => 'You are not allowed to use resource',
        ),
        'VALIDATION_FAIL' => array(
            'code' => 98,
            'description' => 'Validation Error',
        ),
        'SYSTEM_MALFUNCTION' => array(
            'code' => 99,
            'description' => 'System Malfunction',
        ),
    ],
    'OTHERS' => [
        'USER_INACTIVE' => array(
            'code' => 10,
            'description' => 'The User is inactive',
        ),
        'USER_LOCKED' => array(
            'code' => 11,
            'description' => 'The User is locked',
        ),
        'MISSING_INFO' => array(
            'code' => 12,
            'description' => 'The Required information is missing',
        ),
        'USER_NOT_FOUND' => array(
            'code' => 13,
            'description' => 'User not found',
        ),
        'GATEWAY_NOT_CONFIGURED' => array(
            'code' => 14,
            'description' => 'The Gateway is not configured',
        ),
        'INVALID_USERNAME_PASSWORD' => array(
            'code' => 15,
            'description' => 'Invalid Username/Email  or Password',
        ),
        'USERNAME_ALREADY_EXISTS' => array(
            'code' => 16,
            'description' => 'Username or Email already exsists',
        ),
        'EXTERNAL_LOGIN_NOT_ALLOWED' => array(
            'code' => 17,
            'description' => 'External login not allowed',
        ),
        'DOMAIN_INFORMATION_MISSING' => array(
            'code' => 18,
            'description' => 'Domain information is missing',
        ),
        'PASSWORD_MATCH_FAILED' => array(
            'code' => 19,
            'description' => 'The Password and Confirm Password does not match',
        ),
        'EMAIL_TEMPLATE_NOT_FOUND' => array(
            'code' => 20,
            'description' => 'Email template not found',
        ),
        'USER_PROFILE_NOT_FOUND' => array(
            'code' => 21,
            'description' => 'User Profile does not exist',
        ),
        'DB_RECORD_CREATION_FAILED' => array(
            'code' => 22,
            'description' => 'Database Record not created',
        ),
        'USE_DBPROFILE_MECHANISM_FOR_ADMIN' => array(
            'code' => 23,
            'description' => 'Database Record not created',
        ),
        'USER_CAN_NOT_BE_CREATED' => array(
            'code' => 24,
            'description' => 'User cannot be created',
        ),
        'ACCESS_NOT_FOUND' => array(
            'code' => 25,
            'description' => 'Access not found',
        ),
        'GATEWAY_NOTFOUND' => array(
            'code' => 26,
            'description' => 'Gateway not found',
        ),
        'INVALID_CLIENT_ID_CLIENT_SECRET' => array(
            'code' => 27,
            'description' => 'Invalid Client ID or Client Secret',
        ),
        'CHANGE_PASSWORD_FAILED' => array(
            'code' => 29,
            'description' => 'Change Password request failed',
        ),
        'CHANGE_PASSWORD_NOT_ALLOWED' => array(
            'code' => 30,
            'description' => 'Change Password not allowed',
        ),
        'RESET_PASSWORD_NOT_ALLOWED' => array(
            'code' => 31,
            'description' => 'Reset Password not allowed',
        ),
        'EXISTING_PASSWORD_INVALID' => array(
            'code' => 32,
            'description' => 'Current Password is invalid',
        ),
        'PASSWORD_POLICY_FAILED' => array(
            'code' => 33,
            'description' => 'Password Policy failed',
        ),
        'RESET_PASSWORD_TOKEN_EXPIRED' => array(
            'code' => 36,
            'description' => 'Reset Password token expired',
        ),
        'ACCESS_TOKEN_INVALID' => array(
            'code' => 37,
            'description' => 'Access Token is invalid or expired',
        ),
        'FILE_TYPE_NOT_ALLOWED' => array(
            'code' => 38,
            'description' => 'File Type not allowed',
        ),
        'FILE_TOO_BIG' => array(
            'code' => 39,
            'description' => 'File Size exceeds the maximum limit',
        ),
        'USER_PROFILE_EXIST' => array(
            'code' => 40,
            'description' => 'User Profile exist',
        ),
        'RECORD_INACTIVATED' => array(
            'code' => 45,
            'description' => 'Record is inactive',
        ),
        'RECORD_NOT_DELETED' => array(
            'code' => 46,
            'description' => 'Record not deleted',
        ),
        'RECORD_ALREADY_EXISTS' => array(
            'code' => 55,
            'description' => 'Record already exists',
        ),
        'DOMAIN_NOT_EXIST' => array(
            'code' => 57,
            'description' => 'Domain does not exist',
        ),
        'NEW_PASSWORD_FAILED' => array(
            'code' => 58,
            'description' => 'New Password failed',
        ),
        'REQUEST_CANNOT_BE_COMPLETED' => array(
            'code' => 59,
            'description' => 'Request cannot be completed',
        ),
        'EMAIL_ATTACHMENT_REQUIRED' => array(
            'code' => 60,
            'description' => 'Email attachment is required',
        ),
        'RECORD_NOT_SAVED' => array(
            'code' => 61,
            'description' => 'Record not saved',
        ),
    ],
    'HTTP' => [
        'SUCCESS' => 200,
        'CREATED' => 201,
        'UPDATED' => 201,
        'BAD_REQUEST' => 400,
        'UN_AUTHORIZES' => 401,
        'NOT_FOUND' => 404,
        'PAYMENT_REQUIRED' => 402,
        'FORBIDDEN' => 403,
        'SERVER_ERROR' => 500,
        "DELETED" => 204,
    ],
];