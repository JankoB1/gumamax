<?php
/**
 * Created by PhpStorm.
 * User: nikola
 * Date: 8/7/14
 * Time: 11:04 PM
 */

return [
        'hosts'=>[
            'localhost:9200'
    ],

    'indices'=>[

        'gumamax' =>[
            'description' => 'Products',
            'type'=>'tyres_2016',
            'id_field'=>'es_id'
        ],

        'michelin'=>[
            'description'=>'Vehicles',
            'type'=>'vehicles',
            'id_field'=>'es_id'
        ],

        'tyres_dimensions'=>[
            'description'=>'Sve kombinacije dimenzija guma',
            'type'=>'available_dimensions',
            'id_field'=>'es_id'
        ]
    ]
];