<?php

namespace app\modules\nbdropaddress\models;

use Yii;
use luya\admin\ngrest\base\NgRestModel;

/**
 * Nb Address.
 * 
 * File has been created with `crud/create` command. 
 *
 * @property integer $address_id
 * @property string $zipcode
 * @property string $city
 * @property string $street
 * @property string $country
 * @property float $latitude
 * @property float $longitude
 */
class NbAddress extends NgRestModel
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'd_address';
    }

    /**
     * @inheritdoc
     */
    public static function ngRestApiEndpoint()
    {
        return 'api-nbdropaddress-nbaddress';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'address_id' => Yii::t('app', 'Address ID'),
            'zipcode' => Yii::t('app', 'Zipcode'),
            'city' => Yii::t('app', 'City'),
            'street' => Yii::t('app', 'Street'),
            'country' => Yii::t('app', 'Country'),
            'latitude' => Yii::t('app', 'Latitude'),
            'longitude' => Yii::t('app', 'Longitude'),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['latitude', 'longitude'], 'number'],
            [['zipcode', 'city', 'street', 'country'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function genericSearchFields()
    {
        return ['zipcode', 'city', 'street', 'country'];
    }

    /**
     * @inheritdoc
     */
    public function ngRestAttributeTypes()
    {
        return [
            'zipcode' => 'text',
            'city' => 'text',
            'street' => 'text',
            'country' => 'text',
            'latitude' => 'decimal',
            'longitude' => 'decimal',
        ];
    }

    /**
     * @inheritdoc
     */
    public function ngRestScopes()
    {
        return [
            ['list', ['zipcode', 'city', 'street', 'country', 'latitude', 'longitude']],
            [['create', 'update'], ['zipcode', 'city', 'street', 'country', 'latitude', 'longitude']],
            ['delete', false],
        ];
    }
}