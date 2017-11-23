/**
 * Created by Jakharbek on 21.11.2017.
 */
$(document).ready(function(){
    $('[data-query]').each(function(){
        $(this).click(function(e){

            e.preventDefault();

            var data_query = $(e.currentTarget).attr('data-query');
            var data_query_method = $(e.currentTarget).attr('data-query-method');
            var data_query_url = $(e.currentTarget).attr('data-query-url');
            var data_query_params = $(e.currentTarget).attr('data-query-params');
            var data_query_confirm = $(e.currentTarget).attr('data-query-confirm');


            if(data_query_confirm.length > 1){

                var confirm_data = window.confirm(data_query_confirm,'asdasd','sadsad');
                if(!confirm_data){return;}
            }

            if(data_query == "delete"){
                var delete_dom_element = $(e.currentTarget).attr('data-query-delete-selector');
                $.ajax({
                    'url' : data_query_url,
                    'type' : data_query_method,
                    'data' : data_query_params,
                    'success' : function(data){
                        if(data == 'ok'){
                            $(delete_dom_element).css('background-color','#a94442');
                            $(delete_dom_element).css('color','white');
                            $(delete_dom_element).hide(1000);
                        }else{
                            alert('Error!');
                        }
                    },
                });
            }
        });
    });
});
 document.onlinecomponent = function(query_data = 'online_manager=ok',query_success = null,query_method = 'POST',query_interval = 1000){
    $(document).ready(function(){
        if(query_success == null){
            query_success = function(data){}
        }
        document.onlinemanager = {
            last : new Date(),
            timer : false,
            query_data : query_data,
            query_success : query_success,
            query_method : query_method,
            query_interval: query_interval,
        };

        document.onlinemanager.startTimer = function(){
            if(document.onlinemanager.timer !== false){
                return true;
            }
            document.onlinemanager.timer = setInterval(function(){
                $.ajax({
                    'url' : window.location.href,
                    'type' : document.onlinemanager.query_method,
                    'data' : document.onlinemanager.query_data,
                    'success' : document.onlinemanager.query_success,
                });
            },document.onlinemanager.query_interval);
            //console.log('startTimer');
        }
        document.onlinemanager.endTimer = function(){
            clearInterval(document.onlinemanager.timer);
            document.onlinemanager.timer = false;
            //console.log('endTimer');
        }

        $(window).blur(function(){
            document.onlinemanager.endTimer();
            //console.log('blur');

        });
        $(window).focus(function(){
            document.onlinemanager.startTimer();
            //console.log('focus');
        });
        $(window).mouseenter(function(){
            document.onlinemanager.startTimer();
           // console.log('mousemove');
        });
        $(window).load(function(){
            document.onlinemanager.startTimer();
            //console.log('load');
        });
    });
}