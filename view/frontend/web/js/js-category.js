/*
* @Author: Ngo Quang Cuong
* @Date:   2016-12-22 04:49:34
* @Last Modified by:   nquangcuong
* @Last Modified time: 2016-12-23 05:24:13
*/

require([
  'jquery',
  'jquery/ui',
  'jquery/validate',
  'mage/translate'
], function($, mageTemplate){
  "use strict";
  $(document).ready(function() {
    $('.faqs-list .item > a').on('click', function() {
      $(this).children('span').toggleClass('faq-iconplus');
      $(this).children('span').toggleClass('faq-iconminus');
      $(this).parent().children('.description').toggle(300);
      return false;
    });
  });
});
