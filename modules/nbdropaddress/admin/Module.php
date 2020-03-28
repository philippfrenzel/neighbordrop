<?php

namespace app\modules\nbdropaddress\admin;

/**
 * Nbdropaddress Admin Module.
 *
 * File has been created with `module/create` command. 
 * 
 * @author
 * @since 1.0.0
 */
class Module extends \luya\admin\base\Module
{

    public $apis = [
        'api-nbdropaddress-member' => 'app\modules\nbdropaddress\admin\apis\NbAddressController',
    ];

}