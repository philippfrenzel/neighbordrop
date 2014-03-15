<?php

namespace app\modules\dms\models;

use Yii;
use yii\base\Model;

/**
 * PartyForm is the model behind the create party form.
 */

class PartyForm extends Model
{
  //from party
  public $organisationName;
  public $taxNumber;
  //from address
  public $postCode;
  public $addressLine;
  public $cityName;
  public $countryCode;
  //from contact
  public $email;
  public $fax;
  public $mobile;
  public $id;

  /**
   * @return array the validation rules.
   */
  public function rules()
  {
    return [
      // username and password are both required
      [['organisationName'], 'required'],
      [['organisationName','taxNumber'], 'string', 'max' => 255],
      [['countryCode'], 'integer'],
      [['email','addressLine'], 'string', 'max' => 200],
      [['mobile', 'fax','postCode', 'cityName'], 'string', 'max' => 100],
    ];
  }

  /**
   * @inheritdoc
   */
  public function attributeLabels()
  {
    return [
      'organisationName' => \Yii::t('app','Organisation Name'),
      'taxNumber'        => \Yii::t('app','Tax Number'),
      'postCode'         => \Yii::t('app','Post Code'),
      'addressLine'      => \Yii::t('app','Address Line'),
      'cityName'         => \Yii::t('app','City Name'),
      'countryCode'      => \Yii::t('app','Country Code'),
      'email'            => \Yii::t('app','Email'),
      'mobile'           => \Yii::t('app','Mobile'),
      'fax'              => \Yii::t('app','Fax'),      
    ];
  }

  public function save($runValidation = true, $attributes = null)
  {
    $party = new \app\modules\parties\models\Party;
    $party->organisationName = $this->organisationName;
    $party->taxNumber = $this->taxNumber;
    $party->registrationCountryCode = $this->countryCode;
    if(!$party->save())
    {
      return false;
    }
    $address = new \app\modules\parties\models\Address;
    $address->postCode = $this->postCode;
    $address->addressLine = $this->addressLine;
    $address->cityName = $this->cityName;
    $address->countryCode = $this->countryCode;
    $address->party_id = $party->id;
    $address->save();

    $contact = new \app\modules\parties\models\Contact;
    $contact->email = $this->email;
    $contact->fax = $this->fax;
    $contact->mobile = $this->mobile;
    $contact->save();
    $this->id = $party->id;
    return true;
  }

}
