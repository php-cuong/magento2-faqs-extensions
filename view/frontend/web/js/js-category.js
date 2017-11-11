/*
* @Author: Ngo Quang Cuong
* @Date:   2016-12-22 04:49:34
* @Last Modified by:   https://www.facebook.com/giaphugroupcom
* @Last Modified time: 2017-11-11 17:31:22
*/

require([
  'jquery'
], function ($) {
  "use strict";

  $(document).ready(function () {
    $('.faq-category').find('ol').find('li').first().addClass('active');
    $('.faqs-list .item > a').on('click', function () {
      if ($(this).parent().children('.description').css('display') !== 'none') {
        $(this).parents('li').removeClass('active');
      } else {
        $(this).parents('.faq-category').find('ol li').removeClass('active');
        $(this).parents('li').addClass('active');
      }
      return false;
    });
    $('.faq-category').find('ol li .read-more').click(function() {
      window.location.href = $(this).parent().find('a').attr('href');
    });
  });
});
