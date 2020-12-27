<?php

return [

    // these options are related to the sign-up procedure
    'sign_up' => [

        // here you can specify some validation rules for your sign-in request
        'validation_rules' => [
            'username' => 'required',
            'name' => 'required',
            'password' => 'required',
            'email' => 'required|email',
            'role' => 'required'
        ]
    ],

    // these options are related to the login procedure
    'login' => [

        // here you can specify some validation rules for your login request
        'validation_rules' => [
            // 'username' => 'required',
            // 'password' => 'required'
        ]
    ],

    // these options are related to the password recovery procedure
    'forgot_password' => [

        // here you can specify some validation rules for your password recovery procedure
        'validation_rules' => [
            'email' => 'required|email'
        ]
    ],

    'update_profile' => [
        'validation_rules' => [
            'name' => 'required',
            'email' => 'required|email',
            'gender' => 'required'
        ]
    ],

    // these options are related to the password recovery procedure
    'reset_password' => [
        'validation_rules' => [
            'email' => 'required|email',
            'password' => 'required|confirmed'
        ]
    ],

    'task_finish' => [
        'validation_rules' => [
            'id_task' => 'required',
            'review' => 'required',
            'file' => 'mimes:jpg,jpeg,png,bmp,tiff,pdf,doc,docx,xls,xlsx,ppt,pptx |max:10240'
        ]
    ],

];