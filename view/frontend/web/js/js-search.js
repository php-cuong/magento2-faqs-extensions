/*
* @Author: Abraham Osorio
* @Date:   2019-03-26 10:54:18
* @Last Modified by:   Abraham Osorio
* @Last Modified time: 2019-03-26 10:54:18
*/

require([
    'jquery'
], function ($) {
    "use strict";

    $(document).ready(function () {
        $('.faq-content').find('ol').find('li').first().addClass('active');
        $('.faqs-list .item > a').on('click', function () {
            if ($(this).parent().children('.description').css('display') !== 'none') {
                $(this).parents('li').removeClass('active');
            } else {
                $(this).parents('.faq-content').find('ol li').removeClass('active');
                $(this).parents('li').addClass('active');
            }
            return false;
        });
        $('.faq-content').find('ol li .read-more').click(function() {
            window.location.href = $(this).parent().find('a').attr('href');
        });
    });
});
