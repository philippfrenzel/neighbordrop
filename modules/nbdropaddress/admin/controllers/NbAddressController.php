<?php

namespace app\modules\nbdropaddress\admin\controllers;

/**
 * Nb Address Controller.
 * 
 * File has been created with `crud/create` command. 
 */
class NbAddressController extends \luya\admin\ngrest\base\Controller
{
    /**
     * @var string The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = 'app\modules\nbdropaddress\models\NbAddress';

    public $globalButtons = [
        [
            'icon' => 'extension', 
            'label' => 'Address', 
            'ui-sref' => "default.route({moduleRouteId:'nbaddressadmin', controllerId:'member', actionId:'hello-world'})",
        ]
    ];

    public function actionHelloWorld()
    {
        return $this->render('hello-world');
    }

}