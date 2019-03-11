(function($) {
  showSuccessToast = function(e) {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Success',
      text: e.msg,
      showHideTransition: 'slide',
      icon: 'success',
      loaderBg: '#f96868',
      position: 'top-right'
    })
  };
  showWarningToast = function(e) {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Warning',
      text: e.msg,
      showHideTransition: 'slide',
      icon: 'warning',
      loaderBg: '#57c7d4',
      position: 'top-right'
    })
  };
  showDangerToast = function(e) {
    'use strict';
    resetToastPosition();
    $.toast({
      heading: 'Error',
      text: e.msg,
      showHideTransition: 'slide',
      icon: 'error',
      loaderBg: '#f2a654',
      position: 'top-right'
    })
  };
  showToast = function(e){
    switch (e.code){
        case 1:
          showSuccessToast(e);
          break;
        case 0:
          showDangerToast(e);
          break;
        default:
          showWarningToast(e);
          break;
    }
  };

  resetToastPosition = function() {
    $('.jq-toast-wrap').removeClass('bottom-left bottom-right top-left top-right mid-center'); // to remove previous position class
    $(".jq-toast-wrap").css({
      "top": "",
      "left": "",
      "bottom": "",
      "right": ""
    }); //to remove previous position style
  }
})(jQuery);