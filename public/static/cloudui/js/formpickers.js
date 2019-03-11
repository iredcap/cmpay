(function($) {
  'use strict';
  if ($("#timepicker-example").length) {
    $('#timepicker-example').datetimepicker({
      format: 'LT'
    });
  }
  if ($(".color-picker").length) {
    $('.color-picker').asColorPicker();
  }
  if ($("#datepicker-popup").length) {
    $('#datepicker-popup').datepicker({
      enableOnReadonly: false,
      todayHighlight: true,
      format: 'yyyy-mm-dd'

    });
  }
  if ($("#inline-datepicker").length) {
    $('#inline-datepicker').datepicker({
      enableOnReadonly: true,
      todayHighlight: true,
    });
  }
  if ($(".datepicker-autoclose").length) {
    $('.datepicker-autoclose').datepicker({
      autoclose: true
    });
  }
  if ($('input[name="date-range"]').length) {
    $('input[name="date-range"]').daterangepicker();
  }
  if ($('input[name="date-time-range"]').length) {
    $('input[name="date-time-range"]').daterangepicker({
      timePicker: true,
      timePickerIncrement: 30,
      locale: {
        format: 'MM/DD/YYYY h:mm A'
      }
    });
  }
  //订单查询开始时间
  if ($("#datepicker-order-start").length) {
      $('#datepicker-order-start').datepicker({
          enableOnReadonly: false,
          todayHighlight: true,
          format: 'yyyy-mm-dd'

      });
  }
  //订单查询结束时间
  if ($("#datepicker-order-end").length) {
      $('#datepicker-order-end').datepicker({
          enableOnReadonly: false,
          todayHighlight: true,
          format: 'yyyy-mm-dd'

      });
  }
  // 设置取现时间
  if ($("#timepicker-balance-set").length) {
      $('#timepicker-balance-set').datetimepicker({
          format: 'HH:mm'
      });
  }
})(jQuery);