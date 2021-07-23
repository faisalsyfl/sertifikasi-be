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
            'email' => 'required',
            'address' => 'required',
            'city_id' => 'required',
            'state_id' => 'required',
            'country_id' => 'required',
            'postcode' => 'required'
        ]
    ],

    'form_contact' => [
        'validation_rules' => [
            'auditi_id' => 'required|exists:auditi,id',
            'name' => 'required',
            'email' => 'required',
            'telp' => 'required',
            'jabatan' => 'required',
        ]
    ],

    'form_qsc_2' => [
        'validation_rules' => [
            'mode' => 'required|in:QSC1,QSC2,QSC3,QSC4,QSC5,QSC6,QSC7',
            'transaction_id' => 'required|exists:transaction,id',
            'section_status_id' => 'required|exists:section_status,id',
        ]
    ],

];