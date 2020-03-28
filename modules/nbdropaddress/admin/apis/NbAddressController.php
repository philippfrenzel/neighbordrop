<?php

namespace app\modules\nbdropaddress\admin\apis;

/**
 * Nb Address Controller.
 * 
 * File has been created with `crud/create` command. 
 */
class NbAddressController extends \luya\admin\ngrest\base\Api
{
    /**
     * @var string The path to the model which is the provider for the rules and fields.
     */
    public $modelClass = 'app\modules\nbdropaddress\models\NbAddress';
}