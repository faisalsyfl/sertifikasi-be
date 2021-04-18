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

    'form_location' => [
        'validation_rules' => [
            'location_type' => 'required|in:KEGIATAN_UTAMA,KEGIATAN_LAIN,KEGIATAN_NON_PERMANEN',
            'address' => 'required',
            'location' => 'required',
            'form_id' => 'required',
            'country_id' => 'required',
            'state_id' => 'required',
            'city_id' => 'required',
            'postcode' => 'required|min:5|max:5'
        ]
    ],

    'mate_list_commers' => [
        'validation_rules' => [
            'status' => 'in:MISSION_IN_REVIEW,MISSION_ON_PROGRESS,MISSION_APPROVED,MISSION_REJECTED',
        ]
    ],

];