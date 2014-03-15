<?php

namespace app\modules\app\components;

use yii\helpers\Html;

class XmlImporter
{
  private $filePath;
  private $file;

  public function __construct($filePath = '')
  {
    $this->filePath = $filePath;
    if($this->filePath !== '')
      $this->file = simplexml_load_file($this->filePath);
  }

  public function getData()
  {
    return $this->xmlToArray($this->file);
  }

  protected function xmlToArray($xml, $options = array())
  {
      $defaults = array(
          'namespaceSeparator' => ':',//you may want this to be something other than a colon
          'attributePrefix' => '@',   //to distinguish between attributes and nodes with the same name
          'alwaysArray' => array(),   //array of xml tag names which should always become arrays
          'autoArray' => true,        //only create arrays for tags which appear more than once
          'textContent' => '$',       //key used for the text content of elements
          'autoText' => true,         //skip textContent key if node has no attributes or child nodes
          'keySearch' => false,       //optional search and replace on tag and attribute names
          'keyReplace' => false       //replace values for above search values (as passed to str_replace())
      );
      $options = array_merge($defaults, $options);
      $namespaces = $xml->getDocNamespaces();
      $namespaces[''] = null; //add base (empty) namespace

      //get attributes from all namespaces
      $attributesArray = array();
      foreach ($namespaces as $prefix => $namespace) {
          foreach ($xml->attributes($namespace) as $attributeName => $attribute) {
              //replace characters in attribute name
              if ($options['keySearch']) $attributeName =
                      str_replace($options['keySearch'], $options['keyReplace'], $attributeName);
              $attributeKey = $options['attributePrefix']
                      . ($prefix ? $prefix . $options['namespaceSeparator'] : '')
                      . $attributeName;
              $attributesArray[$attributeKey] = (string)$attribute;
          }
      }
      //get child nodes from all namespaces
      $tagsArray = array();
      foreach ($namespaces as $prefix => $namespace) {
          foreach ($xml->children($namespace) as $childXml) {
              //recurse into child nodes
              $childArray = $this->xmlToArray($childXml, $options);
              list($childTagName, $childProperties) = each($childArray);
              //replace characters in tag name
              if ($options['keySearch']) $childTagName =
                      str_replace($options['keySearch'], $options['keyReplace'], $childTagName);
              //add namespace prefix, if any
              if ($prefix) $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;
              if (!isset($tagsArray[$childTagName])) {
                  //only entry with this key
                  //test if tags of this type should always be arrays, no matter the element count
                  $tagsArray[$childTagName] =
                          in_array($childTagName, $options['alwaysArray']) || !$options['autoArray']
                          ? array($childProperties) : $childProperties;
              } elseif (
                  is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName])
                  === range(0, count($tagsArray[$childTagName]) - 1)
              ) {
                  //key already exists and is integer indexed array
                  $tagsArray[$childTagName][] = $childProperties;
              } else {
                  //key exists so convert to integer indexed array with previous value in position 0
                  $tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
              }
          }
      }
      //get text content of node
      $textContentArray = array();
      $plainText = trim((string)$xml);
      if ($plainText !== '') $textContentArray[$options['textContent']] = $plainText;
      //stick it all together
      $propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || ($plainText === '')
              ? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;
      //return node as array
      return array(
          $xml->getName() => $propertiesArray
      );
  }

  /**
     * Converts a multidimensional associative array to an XML document.
     *
     * @param array $data
     * @param string $rootNodeName - what you want the root node to be - defaultsto data.
     * @param SimpleXMLElement $xml - should only be used recursively
     * @return string XML
     */
    public static function toXML($data, $rootNodeName = 'entity', &$xml=null )
    {

        // turn off compatibility mode
        if ( ini_get('zend.ze1_compatibility_mode') == 1 ) ini_set ( 'zend.ze1_compatibility_mode', 0 );
        if ( is_null( $xml ) ) //$xml = simplexml_load_string( "" );
            $xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><$rootNodeName />");

        // loop through the data passed in.
        foreach( $data as $key => $value ) {
            $numeric = false;
            // no numeric keys in our xml please!
            if ( is_numeric( $key ) ) {
                $numeric = 1;
                $key = $rootNodeName;
            }
            // delete any char not allowed in XML element names
            $key = preg_replace('/[^a-z0-9\-\_\.\:]/i', '', $key);
            // if there is another array found recrusively call this function
            if ( is_array( $value ) ) {
                $node = self::isAssoc( $value ) || $numeric ? $xml->addChild( $key ) : $xml;
                // recrusive call.
                if ($numeric) $key = 'anon';
                self::toXml( $value, $key, $node );
            } else {
                // add single node.
                $value = Html::encode($value);
                $xml->addChild($key, $value);
            }
        }
        // pass back as XML
        return $xml->asXML();
    }

    /**
     * Checks wheter an array is an associative one
     *
     * @param array The array to check
     * @return bool Wheter the array is associative or not
     */
    protected static function isAssoc( $array ) {
        return (is_array($array) && 0 !== count(array_diff_key($array, array_keys(array_keys($array)))));
    }
}
