/*
* @Author: Ngo Quang Cuong
* @Date:   2016-12-21 03:13:56
* @Last Modified by:   nquangcuong
* @Last Modified time: 2017-01-05 08:22:22
*/

require([
  'jquery',
  'jquery/ui',
  'jquery/validate',
  'mage/translate'
], function ($, mageTemplate) {
  "use strict";
  $(document).ready(function () {
    addFeedBackPHPCuongFaq(1);
    addFeedBackPHPCuongFaq(0);
    function addFeedBackPHPCuongFaq(type)
    {
      var BASE_URL = $('#feedback #BASE_URL').text();
      var selector = null;
      if (type === 1) {
        selector = '#feedback #btn-like';
      } else if (type === 0) {
        selector = '#feedback #btn-dislike';
      }
      $(document).on('click', selector, function () {
        $('#feedback').text($('#feedback #message').text()).addClass('green');
        var formData = new FormData();
        formData.append('type', type);
        $.ajax({
          url: BASE_URL,
          data: formData,
          processData: false,
          contentType: false,
          type: 'POST',
          dataType: 'json',
          success: function (response) {

          }
        });
      });
    }
  });
});
