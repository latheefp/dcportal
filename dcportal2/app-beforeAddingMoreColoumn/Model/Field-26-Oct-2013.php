<?php

class Field extends AppModel {
    var $name = 'Field';
    function _format($value,$format) {
        //echo debug($value);
        if(trim($value)=='') return null;
        if($format=='') {
            $data= $value;
        }else {
            $data= str_replace('%value%', $value, $format);
        }
        return $data;
    }
    function _AdvFormat($value,$fieldUserInfo,$formathtml=true) {
       // echo debug($value);
        App::import( 'Helper', 'Time' );
        App::import( 'Helper', 'Number' );
        App::import( 'Helper', 'Text' );

        $time = new TimeHelper();
        $number = new NumberHelper();
        $text = new TextHelper();
        if($fieldUserInfo['toCurrency']!='') {
            $value=$number->currency($value,$fieldUserInfo['toCurrency']);
        }
        if($fieldUserInfo['toPrecision']!='') {
            $value=$number->precision($value,$fieldUserInfo['toPrecision']);
        }

        if($fieldUserInfo['toPercentage']!='0') {
            $value=$number->toPercentage($value);
        }
        if($fieldUserInfo['toReadableSize']!='0') {
            $value=$number->toReadableSize($value);
        }
        if($fieldUserInfo['autoLink']!='0') {
            $value=$text->autoLink($value);
        }
        if($formathtml) {
            if($fieldUserInfo['toTruncate']!='') {
                $truncated=$text->truncate($value,$fieldUserInfo['toTruncate'],array('html' => false));
                if($truncated!=$value) {
                    $value = '<div class="hiddenData'.$fieldUserInfo['id'].'" style="display:none;">' .$value;
                    $value .= ' <small>(<a href="#" onclick="javascript:$('."'.".'shortDaa'.$fieldUserInfo['id']."'".').show();$('."'.".'hiddenData'.$fieldUserInfo['id']."'".').hide();return false;">less</a>)</small>';
                    $value .='</div>';
                    $value .= '<div class="shortDaa'.$fieldUserInfo['id'].'">'. $truncated;
                    $value .= '<small>(<a href="#" onclick="javascript:$('."'.".'shortDaa'.$fieldUserInfo['id']."'".').hide();$('."'.".'hiddenData'.$fieldUserInfo['id']."'".').show();return false;">more</a>)</small>';
                    $value .= '</div>';

                }
            }
        }
        if($fieldUserInfo['toHighlight']!='') {
            $value=$text->highlight($value,$fieldUserInfo['toHighlight']);
        }

        if($fieldUserInfo['toDateNice']!='0') {
            $value=$time->nice($value);
        }
        if($fieldUserInfo['toDateNiceShort']!='0') {
            $value=$time->niceShort($value);
        }
        if($fieldUserInfo['toTimeAgoInWords']!='0') {
            $value=$time->timeAgoInWords($value);
        }
        if($fieldUserInfo['toDateformat']!='') {
            $value=$time->format($fieldUserInfo['toDateformat'],$value);
        }
        if($formathtml){
        if($fieldUserInfo['toList']!='') {
            $tmp = explode($fieldUserInfo['toList'], $value);
            $value= '<ul style="text-align:left;">';
            foreach($tmp as $line) {
                $value .= '<li>' . $line . '</li>';
            }
            $value .= '</ul>';
        }
        }
        if($fieldUserInfo['toBR']!='') {
            $value=str_replace($fieldUserInfo['toBR'], '<br>', $value);
        }


        if($fieldUserInfo['nl2br']) {
            $value=nl2br($value);
        }
        return $value;
    }
    function showField($value,$fieldUserInfo,$formathtml=true) {
        App::import( 'Helper', 'html' );

        $html = new HtmlHelper();
        if($fieldUserInfo['type_overwrite'] =='file') {
            $files = explode("\n", $value);
            $value = null;
            foreach($files as $file) {
                $internal = substr($file, 0, strpos($file,',') );
                $Human = substr($file, strlen($internal)+1 );
                if($value!=null) {
                    $value .= '<br>';
                }
                if($html!=null) {
                    $value .= '<a href="'. $html->url(array("controller" => "servers","action" => "download",$fieldUserInfo['field'],$fieldUserInfo['id'],$internal,$Human)). '">'.$Human.'</a>';
                }else {
                    $value .= '<a href="/servers/download/'.$fieldUserInfo['field'].'/'.$fieldUserInfo['id'].'/'.$internal.'/'.$Human.'">'.$Human.'</a>';
                }
            }
        }else {
            $value = $this->_format($value, $fieldUserInfo['format']);
            $value = $this->_AdvFormat($value, $fieldUserInfo,$formathtml);
        }
        return $value;
    }

}

?>