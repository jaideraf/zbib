<?php

    /**
     * Z39.50 client for Wikincat
     * 
     * php version 7.4
     * 
     * @category Z39.50
     * @package  Zbib
     * @author   jaideraf <jaideraf@gmail.com>
     * @author   Vítor S Rodrigues <vitor.silverio.rodrigues@gmail.com>
     * @license  https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
     * @link     https://wikincat.org/zbib/
     */
    
function formatterRecordToWikincat($record)
{
    $result = $record;
    $pattern0 = '/^\(.*?\)\n/m';            // remove warnings for bad records
    $subst0 = '';
    $pattern1 = '/(^\d{5}[acdnp])/';        // format leader
    $subst1 = '000 $1';
    $pattern2 = '/^9\d\d\s.*?\n/m';         // remove 9XX fields
    $subst2 = '';
    $result = preg_replace($pattern0, $subst0, $result);
    $result = preg_replace($pattern1, $subst1, $result);
    $result = preg_replace($pattern2, $subst2, $result);
    $result = str_replace('|', ' ', $result);
    return htmlentities($result);
}

function formatterRecordToPresentation($record)
{
    $result = $record;
    $pattern0 = '/^\(.*?\)\n/m';            // remove warnings for bad records
    $subst0 = '';
    $pattern1 = '/(^\d{5}[acdnp])/';        // format leader
    $subst1 = '000 $1';
    $pattern2 = '/^9\d\d\s.*?\n/m';         // remove 9XX fields
    $subst2 = '';
    $pattern3 = '/\s\$(.\s)/';              // change ' $a ' to ' |a '
    $subst3 = ' <b>|$1</b>';
    $pattern4 = '/(\n\d{3}\s)[\s#-]/';      // change ind1 ' ' to '_'
    $subst4 = '$1_';
    $pattern5 = '/(\n\d{3}\s.)[\s#-]/';     // change ind2 ' ' to '_'
    $subst5 = $subst4; 
    $pattern6 = '/(^00\d\s)/m';             // Pergamum style control fields
    $subst6 = '$1   ';
    $pattern7 = '/^(26[04])(.*)/m';   // highlight the publication info
    $subst7 = '<span style="border: 2px solid #457bff; border-radius: .2rem"><b>$1</b>$2</span>';
    $pattern8 = '/(^\d{3}\s|\n\d{3}\s)/';   // bold field tag
    $subst8 = '<b>$1</b>';

    $result = preg_replace($pattern0, $subst0, $result);
    $result = preg_replace($pattern1, $subst1, $result);
    $result = preg_replace($pattern2, $subst2, $result);
    $result = preg_replace($pattern3, $subst3, $result);
    $result = preg_replace($pattern4, $subst4, $result);
    $result = preg_replace($pattern5, $subst5, $result);
    $result = preg_replace($pattern6, $subst6, $result);
    $result = preg_replace($pattern7, $subst7, $result);
    $result = preg_replace($pattern8, $subst8, $result);

    return $result;
}
?>