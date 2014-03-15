<?php

namespace app\modules\app\helpers;

use Yii;
use \yii\helpers\StringHelper;

/**
 * @highlight words
 *
 * @param string $text
 * @param array $words
 * @param array $colors
 * @return string
 *
 */

class HighlightHelper extends StringHelper
{

        public static function highlightWords($text, $words, $colors=null)
        {
                if(is_null($colors) || !is_array($colors))
                {
                        $colors = array('pink', 'grass', 'orange','red','greenLight');
                }

                $i = 0;
                /*** the maximum key number ***/
                $num_colors = max(array_keys($colors));

                /*** loop of the array of words ***/
                foreach ($words as $word)
                {
                        /*** quote the text for regex ***/
                        $word = preg_quote($word);
                        /*** highlight the words ***/
                        $text = preg_replace("/($word)/i", '<span class="c_'.$colors[$i].'">\1</span>', $text);
                        if($i==$num_colors){ $i = 0; } else { $i++; }
                }
                /*** return the text ***/
                return $text;
        }

}