/*
* @Author: Ngo Quang Cuong
* @Date:   2016-12-21 03:13:56
* @Last Modified by:   nquangcuong
* @Last Modified time: 2016-12-21 04:47:00
*/

require([
  'jquery',
  'jquery/ui',
  'jquery/validate',
  'mage/translate'
], function($, mageTemplate){
  "use strict";
  $(document).ready(function() {
    var BASE_URL = $('#feedback #BASE_URL').text();
    var FAQ_ID = $('#feedback #FAQ_ID').text();

    $(document).on('click', '#feedback #btn-like', function() {
      addFeedBackPHPCuongFaq(BASE_URL, FAQ_ID, 1);
    });

    $(document).on('click', '#feedback #btn-dislike', function() {
      addFeedBackPHPCuongFaq(BASE_URL, FAQ_ID, 0);
    });

    function addFeedBackPHPCuongFaq(BASE_URL, FAQ_ID, type) {
      $('#feedback').text($('#feedback #message').text());
      $('#feedback').addClass('green');
      var formData = new FormData();
      formData.append('type', type);
      formData.append('faq_id', FAQ_ID);
      $.ajax({
        url: BASE_URL,
        data: formData,
        processData: false,
        contentType: false,
        type: 'POST',
        dataType: 'json',
        success: function (response) {
          console.log(response);
        }
      });
    }
  });
});
