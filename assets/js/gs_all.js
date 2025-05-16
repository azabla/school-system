/**
 *
 * You can write your JS code here, DO NOT touch the default style file
 * because it will make it harder for you to update.
 * 
 */

"use strict";
/* check internet connection*/
var status = 'online';
var current_status = 'online';
function check_internet_connection()
{
    if(navigator.onLine)
    {
        status = 'online';
    }
    else
    {
        status = 'offline';
    }
    if(current_status != status)
    {
        if(status == 'online')
        {
          iziToast.success({
            title: ' Hurray! Internet is connected.',
            message: '',
            position: 'topRight'
          });
        }
        else
        {
            iziToast.show({
              title:' Opps! Internet is disconnected.',
              message: '',
              position: 'topRight'
            });
        }
        current_status = status;

        $('.toast').toast({
            autohide:false
        });

        $('.toast').toast('show');
    }
}

check_internet_connection();

setInterval(function(){
    check_internet_connection();
}, 1000);

  $(document).ready(function() { 
    /*checkNotificationFound();
    checkNewUserFound();
    function checkNotificationFound() { 
        $.ajax({
            url: "<?php echo base_url() ?>home/sendNotification/",
            method: "POST"
        });
    }
    function checkNewUserFound() { 
        $.ajax({
            url: "<?php echo base_url() ?>home/checkNewUserFound/",
            method: "POST"
        });
    }*/
    function birth_date(view = '') {
      $.ajax({
        url: baseURL + "birthdate/",
        method: "POST",
        data: ({
          view: view
        }),
        dataType:"json",
        success: function(data) {
          $('.birthdate').html(data.notification);
        }
      });
    }
    function users_online(view = '') {
      $.ajax({
        url: baseURL +  "Users_online/",
        method: "POST",
        data: ({
          view: view
        }),
        dataType:"json",
        success: function(data) {
          $('.chatnamees').html(data.notification);
        }
      });
    }
    function unseen_notification(view = '') { 
      $.ajax({
        url: baseURL + "fetch_unseen_notification/",
        method: "POST",
        data: ({
          view: view
        }),
        dataType: "json",
        success: function(data) {
          $('.notification-show').html(data.notification);
          if (data.unseen_notification > 0) {
            $('.count-new-notification').html(data.unseen_notification);
            $('.count-new-incident-report').html(data.unseen_notification);
          }
        }
      });
    }  
    function inbox_unseen_notification(view = '') { 
      $.ajax({
        url: baseURL + "fetch_unseen_message_notification/",
        method: "POST",
        data: ({
          view: view
        }),
        dataType: "json",
        success: function(data) {
          $('.inbox-show').html(data.notification);
          if (data.unseen_notification > 0) {
            $('.count-new-inbox').html(data.unseen_notification);
          }
        }
      });
    }
    birth_date();
    unseen_notification();
    users_online();
    inbox_unseen_notification();
    $(document).on('click', '.seen_incident_report', function() {
        $('.count-new-notification').html('');
        inbox_unseen_notification('yes');
    });
    $(document).on('click', '.seen', function() {
        $('.count-new-inbox').html('');
        inbox_unseen_notification('yes');
    });
    $(document).on('click', '.gs-tab-status-2', function() {
        $('.count-new-notification').html('');
        unseen_notification('yes');
    });
    setInterval(function() {
      unseen_notification();
      users_online();
      inbox_unseen_notification();
    }, 5000);
    setInterval(function() {
      birth_date();
    }, 360000);
  });

/*book request script start*/

  // enable this if you want to make only one call and not repeated calls automatically
  // pushNotify();
  var notification='';
  // following makes an AJAX call to PHP to get notification every 10 secs
  setInterval(function(){pushNotify();}, 60000);

      function pushNotify() {
        if (!("Notification" in window)) {
              
          }
          if (Notification.permission !== "granted")
              Notification.requestPermission();
          else {
              $.ajax({
              url: baseURL + "Unseen_itemrequest_notification/",
              type: "POST",
              success: function(data, textStatus, jqXHR) {
                  // if PHP call returns data process it and show notification
                  // if nothing returns then it means no notification available for now
                if ($.trim(data)){
                      var data = jQuery.parseJSON(data);
                      console.log(data);
                      notification = createNotification( data.title,  data.icon,  data.body, data.url);

                      // closes the web browser notification automatically after 5 secs
                      setTimeout(function() {
                        notification.close();
                      }, 20000);
                  }
              },
              error: function(jqXHR, textStatus, errorThrown) {}
              });
          }
      };

      function createNotification(title, icon, body, url) {
          var notification = new Notification(title, {
              icon: icon,
              body: body,
          });
          // url that needs to be opened on clicking the notification
          // finally everything boils down to click and visits right
          notification.onclick = function() {
              window.open(url);
          };
          return notification;
      }

  // enable this if you want to make only one call and not repeated calls automatically
   //pushNotify();
      var notification='';
  // following makes an AJAX call to PHP to get notification every 10 secs
  

      function pushNotify() {
        if (!("Notification" in window)) {
              
          }
          if (Notification.permission !== "granted")
              Notification.requestPermission();
          else {
              $.ajax({
              url: baseURL + "Unseen_bookrequest_notification/",
              type: "POST",
              success: function(data, textStatus, jqXHR) {
                  // if PHP call returns data process it and show notification
                  // if nothing returns then it means no notification available for now
                if ($.trim(data)){
                      var data = jQuery.parseJSON(data);
                      console.log(data);
                      notification = createNotification1( data.title,  data.icon,  data.body, data.url);

                      // closes the web browser notification automatically after 5 secs
                      setTimeout(function() {
                        notification.close();
                      }, 20000);
                  }
              },
              error: function(jqXHR, textStatus, errorThrown) {}
              });
          }
      };
      setInterval(function(){pushNotify();}, 60000);
      function createNotification1(title, icon, body, url) {
          var notification = new Notification(title, {
              icon: icon,
              body: body,
          });
          // url that needs to be opened on clicking the notification
          // finally everything boils down to click and visits right
          notification.onclick = function() {
              window.open(url);
          };
          return notification;
      }

/*book request script end*/
/* notify season end date*/
    $(document).ready(function(){
      checkQuarterEnd();
      function checkQuarterEnd()
      {
        $.ajax({
          url:baseURL + "checkQuarterEndDate/",
          method:"POST",
          dataType:'json',
          success:function(data){
            if(data.data1<='30' && data.data1 >='0'){
              if(data.data2 >='0'){
                iziToast.show({
                  title: data.data1 +' Days and '+ data.data2 +' Hours has left for this season.',
                  message: '',
                  position: 'topRight'
                });
              }else{
                iziToast.show({
                  title: data.data1 +' Days and '+ data.data2 +' Hours passed to end season.',
                  message: '',
                  position: 'topRight'
                });
              }  
            }
          }
        })
      }
    });