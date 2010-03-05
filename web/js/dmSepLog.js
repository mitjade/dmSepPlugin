(function($) {
  
var logs = new Array();
  
$.each(['sep'], function()
{
  logs[this] = {
    $wrapper: $('div.'+this+'_log')
  };

  logs[this].$wrapper.find('div.dm_box_inner').height(200);
});

setTimeout(refresh, 300);
	
function refresh()
{
  $.ajax({
    dataType: 'json',
    url:   $.dm.ctrl.getHref('+/dmSepLog/refresh'),
    data:  {
      dm_nolog: 1
    },
    success: function(data)
    {
      $.each(['sep'], function()
      {
        if (data[this])
        {
          var current = this;
          logs[current].$wrapper.find('div.dm_box_inner').block();
          setTimeout(function() {
            logs[current].$wrapper.find('tbody').html(data[current].html).end().find('div.dm_box_inner').height('auto').unblock();
          }, 200);
        }
      });
      setTimeout(refresh, 500000);
    },
    error: function()
    {
      setTimeout(refresh, 500000);
    }
  })
}

})(jQuery);