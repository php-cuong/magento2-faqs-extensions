<?php

/**
 *
 * @Author              Ngo Quang Cuong <bestearnmoney87@gmail.com>
 * @Date                2016-12-17 23:04:58
 * @Last modified by:   nquangcuong
 * @Last Modified time: 2017-01-05 09:02:19
 */

namespace PHPCuong\Faq\Model\Config\Source;

class Urlkey
{
    /**
     * Generate identifier from the string
     *
     * @param $string
     * @return string
     */
    public function generateIdentifier($string)
    {
        $string = $this->replaceVietnameseLetters(trim($string));

        $string = strtolower($string);

        while (stristr($string, '-')) {
            $string = str_replace('-', ' ', $string);
        }

        while (stristr($string, '  ')) {
            $string = str_replace('  ', ' ', $string);
        }

        $filter = new \Zend\I18n\Filter\Alnum(true);

        $string = $filter->filter($string);

        $string = str_replace(' ', '-', $string);

        while (stristr($string, '--')) {
            $string = str_replace('--', '-', $string);
        }

        return $string;
    }

    /**
     * Replace Vietnamese letters to latin letters
     *
     * @param $string
     * @return string
     */
    public function replaceVietnameseLetters($string)
    {
        $vietnamese = ["à","á","ạ","ả","ã","â","ầ","ấ","ậ","ẩ","ẫ","ă","ằ","ắ","ặ","ẳ","ẵ","è","é","ẹ","ẻ","ẽ","ê","ề","ế","ệ","ể","ễ","ì","í","ị","ỉ","ĩ","ò","ó","ọ","ỏ","õ","ô","ồ","ố","ộ","ổ","ỗ","ơ","ờ","ớ","ợ","ở","ỡ","ù","ú","ụ","ủ","ũ","ư","ừ","ứ","ự","ử","ữ","ỳ","ý","ỵ","ỷ","ỹ","đ","À","Á","Ạ","Ả","Ã","Â","Ầ","Ấ","Ậ","Ẩ","Ẫ","Ă","Ằ","Ắ","Ặ","Ẳ","Ẵ","È","É","Ẹ","Ẻ","Ẽ","Ê","Ề","Ế","Ệ","Ể","Ễ","Ì","Í","Ị","Ỉ","Ĩ","Ò","Ó","Ọ","Ỏ","Õ","Ô","Ồ","Ố","Ộ","Ổ","Ỗ","Ơ","Ờ","Ớ","Ợ","Ở","Ỡ","Ù","Ú","Ụ","Ủ","Ũ","Ư","Ừ","Ứ","Ự","Ử","Ữ","Ỳ","Ý","Ỵ","Ỷ","Ỹ","Đ"];

        $english = ["a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","a","e","e","e","e","e","e","e","e","e","e","e","i","i","i","i","i","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","o","u","u","u","u","u","u","u","u","u","u","u","y","y","y","y","y","d","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","A","E","E","E","E","E","E","E","E","E","E","E","I","I","I","I","I","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","O","U","U","U","U","U","U","U","U","U","U","U","Y","Y","Y","Y","Y","D"];

        return str_replace($vietnamese, $english, $string);
    }
}
