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

    'form_organization' => [
        'validation_rules' => [
            'npwp' => 'required|unique:organization,npwp',
            'name' => 'required',
            'type' => 'required',
            'website' => 'required',
            'email' => 'required',
            'telp' => 'required',
            'address' => 'required',
            'city_id' => 'required',
            'state_id' => 'required',
            'country_id' => 'required',
            'postcode' => 'required|min:5|max:5'
        ]
    ],

    'form_edit_organization' => [
        'validation_rules' => [
            'npwp' => 'required',
            'name' => 'required',
            'type' => 'required',
            'website' => 'required',
            'email' => 'required',
            'telp' => 'required',
            'address' => 'required',
            'city_id' => 'required',
            'state_id' => 'required',
            'country_id' => 'required',
            'postcode' => 'required|min:5|max:5'
        ]
    ],

    'form_auditi' => [
        'validation_rules' => [
            'organization_id' => 'required|exists:organization,id',
            'name' => 'required',
            'type' => 'required',
            'website' => 'required',
            'email' => 'required',
            'telp' => 'required',
            'address' => 'required',
            'city_id' => 'required',
            'state_id' => 'required',
            'country_id' => 'required',
            'postcode' => 'required'
        ]
    ],

    'form_edit_auditi' => [
        'validation_rules' => [
            'organization_id' => 'required|exists:organization,id',
            'name' => 'required',
            'type' => 'required',
            'website' => 'required',
            'email' => 'required',
            'telp' => 'required',
            'address' => 'required',
            'city_id' => 'required',
            'state_id' => 'required',
            'country_id' => 'required',
            'postcode' => 'required'
        ]
    ],

];