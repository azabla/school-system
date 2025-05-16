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

  

