// jQuery Initialization
jQuery(document).ready(function($){
"use strict"; 


        //======================================================================================================
        //      Fancy Box
        //======================================================================================================
        if ($('.lightbox, .button-fullsize, .fullsize').length > 0) {
            $('.lightbox, .button-fullsize, .fullsize').fancybox({
                padding    : 0,
                margin    : 0,
                maxHeight  : '90%',
                maxWidth   : '90%',
                loop       : true,
                fitToView  : false,
                mouseWheel : false,
                autoSize   : false,
                closeClick : false,
                overlay    : { showEarly  : true },
                helpers    : { media : {} }
            });
        }
        //======================================================================================================


        // ----------------- EASING ANCHORS ------------------ //

        $('a[href*=#]').click(function() {

         if (location.pathname.replace(/^\//,'') === this.pathname.replace(/^\//,'') && location.hostname === this.hostname) {

                 var $target = $(this.hash);

                 $target = $target.length && $target || $('[name=' + this.hash.slice(1) +']');

                 if ($target.length) {

                     var targetOffset = $target.offset().top;

                     $('html,body').animate({scrollTop: targetOffset-100}, 1000);

                     return false;

                }

           }

       });


        //======================================================================================================
        //      Go To Top
        //======================================================================================================
        $('#gototop').click(function(e){
            jQuery('html, body').animate({scrollTop:0}, 750, 'linear');
            e.preventDefault();
            return false;
        });
        //======================================================================================================
    




        //======================================================================================================
        //      Submit (Normal 1)
        //======================================================================================================
        $("#subscribe_btn_1").click(function() { 
            //get input field values
            var user_email      = $('.pixfort_normal_1 input[name=email]').val();

            //var user_message    = $('textarea[name=message]').val();
            
            //simple validation at client's end
            //we simply change border color to red if empty field using .css()
            var proceed = true;
            
            if(user_email==""){ 

                $('.pixfort_normal_1 input[name=email]').css('border-color','red'); 
                proceed = false;
            }
            //$.fancybox("#hidden_pix_1");

            //everything looks good! proceed...
            if(proceed) 
            {
                //data to be sent to server
                var post_data;
                var output;
                post_data = {'userEmail':user_email};
                //Ajax post data to server
                $.post('pix_mail/contact_me_1.php', post_data, function(response){  

                    //load json data from server and output message     
                    if(response.type == 'error')
                    {
                        output = '<div class="error">'+response.text+'</div>';
                    }else{
                        $.fancybox("#hidden_pix_1");
                        output = '<div class="success">'+response.text+'</div>';
                        
                        //reset values in all input fields
                        $('.pixfort_normal_1 #contact_form input').val(''); 
                        $('.pixfort_normal_1 #contact_form textarea').val(''); 
                    }
                    
                    $(".pixfort_normal_1 #result").hide().html(output).slideDown();
                }, 'json');
                
            }
        });
        
        //reset previously set border colors and hide all message on .keyup()
        $(".pixfort_normal_1 #contact_form input, .pixfort_normal_1 #contact_form textarea").keyup(function() { 
            $(".pixfort_normal_1 #contact_form input, .pixfort_normal_1 #contact_form textarea").css('border-color',''); 
            $(".pixfort_normal_1 #result").slideUp();
        });
        //======================================================================================================







        //======================================================================================================
        //      Submit (Corporate 2)
        //======================================================================================================
         $("#subscribe_btn_2").click(function() { 
            //get input field values
            var user_name       = $('.pixfort_corporate_2 input[name=name]').val();
            var user_company    = $('.pixfort_corporate_2 input[name=company]').val();
            var user_email      = $('.pixfort_corporate_2 input[name=email]').val();
            var user_message    = $('.pixfort_corporate_2 textarea[name=message]').val();
            
            //simple validation at client's end
            //we simply change border color to red if empty field using .css()
            var proceed = true;
            
            if(user_name==""){ 
                $('.pixfort_corporate_2 input[name=name]').css('border-color','red'); 
                proceed = false;
            }
            if(user_company==""){ 
                $('.pixfort_corporate_2 input[name=company]').css('border-color','red'); 
                proceed = false;
            }
            if(user_email==""){ 
                $('.pixfort_corporate_2 input[name=email]').css('border-color','red'); 
                proceed = false;
            }
            if(user_message==""){ 
                $('.pixfort_corporate_2 textarea[name=message]').css('border-color','red'); 
                proceed = false;
            }
           //$.fancybox("#hidden_pix_2");


            //everything looks good! proceed...
            if(proceed) 
            {

                //data to be sent to server
                var post_data;
                var output;
                post_data = {'user_name':user_name, 'user_company':user_company, 'user_email':user_email, 'user_message':user_message };
                //Ajax post data to server
                $.post('pix_mail/contact_me_2.php', post_data, function(response){  
                    //load json data from server and output message     
                    if(response.type == 'error')
                    {
                        output = '<div class="error">'+response.text+'</div>';
                    }else{
                        $.fancybox("#hidden_pix_2");
                        output = '<div class="success">'+response.text+'</div>';
                        
                        //reset values in all input fields
                        $('.pixfort_corporate_2 #contact_form input').val(''); 
                        $('.pixfort_corporate_2 #contact_form textarea').val(''); 
                    }
                    
                    $(".pixfort_corporate_2 #result").hide().html(output).slideDown();
                }, 'json');
                
            }
        });
        
        //reset previously set border colors and hide all message on .keyup()
        $(".pixfort_corporate_2 #contact_form input, .pixfort_corporate_2 #contact_form textarea").keyup(function() { 
            $(".pixfort_corporate_2 #contact_form input, .pixfort_corporate_2 #contact_form textarea").css('border-color',''); 
            $(".pixfort_corporate_2 #result").slideUp();
        });
        //======================================================================================================







        //======================================================================================================
        //      Submit (Real Esate 4)
        //======================================================================================================
         $("#submit_btn_4").click(function() { 
            //get input field values
            var user_name       = $('.pixfort_real_estate_4 input[name=name]').val();
            var user_email      = $('.pixfort_real_estate_4 input[name=email]').val();
            var user_number    = $('.pixfort_real_estate_4 input[name=number]').val();
           // var user_message    = $('.pixfort_real_estate_4 textarea[name=message]').val();
            
            //simple validation at client's end
            //we simply change border color to red if empty field using .css()
            var proceed = true;
            
            if(user_name==""){ 
                $('.pixfort_real_estate_4 input[name=name]').css('border-color','red'); 
                proceed = false;
            }
            if(user_number==""){ 
                $('.pixfort_real_estate_4 input[name=number]').css('border-color','red'); 
                proceed = false;
            }
            if(user_email==""){ 
                $('.pixfort_real_estate_4 input[name=email]').css('border-color','red'); 
                proceed = false;
            }
            // if(user_message==""){ 
            //     $('.pixfort_real_estate_4 textarea[name=message]').css('border-color','red'); 
            //     proceed = false;
            // }
           //$.fancybox("#hidden_pix_4");


            //everything looks good! proceed...
            if(proceed) 
            {

                //data to be sent to server
                var post_data;
                var output;
                post_data = {'user_name':user_name, 'user_number':user_number, 'user_email':user_email };
                //Ajax post data to server
                $.post('pix_mail/contact_me_4.php', post_data, function(response){  
                    //load json data from server and output message     
                    if(response.type == 'error')
                    {
                        output = '<div class="error">'+response.text+'</div>';
                    }else{
                        $.fancybox("#hidden_pix_4");
                        output = '<div class="success">'+response.text+'</div>';
                        
                        //reset values in all input fields
                        $('.pixfort_real_estate_4 #contact_form input').val(''); 
                        $('.pixfort_real_estate_4 #contact_form textarea').val(''); 
                    }
                    
                    $(".pixfort_real_estate_4 #result").hide().html(output).slideDown();
                }, 'json');
                
            }
        });
        
        //reset previously set border colors and hide all message on .keyup()
        $(".pixfort_real_estate_4 #contact_form input, .pixfort_real_estate_4 #contact_form textarea").keyup(function() { 
            $(".pixfort_real_estate_4 #contact_form input, .pixfort_real_estate_4 #contact_form textarea").css('border-color',''); 
            $(".pixfort_real_estate_4 #result").slideUp();
        });
        //======================================================================================================










        //======================================================================================================
        //      Submit (University 6)
        //======================================================================================================
         $("#submit_btn_6").click(function() { 
            //get input field values
            var user_name       = $('.pixfort_university_6 input[name=name]').val();
            var user_email      = $('.pixfort_university_6 input[name=email]').val();
            var user_number    = $('.pixfort_university_6 input[name=number]').val();

            var e = document.getElementById("countries");
            var strUser = e.options[e.selectedIndex].value;
            var user_country    = strUser;

            //var user_country    = $('.pixfort_university_6 select[name=countries]').val();
           // var user_message    = $('.pixfort_university_6 textarea[name=message]').val();
            
            //simple validation at client's end
            //we simply change border color to red if empty field using .css()
            var proceed = true;
            
            if(user_name==""){ 
                $('.pixfort_university_6 input[name=name]').css('border-color','red'); 
                proceed = false;
            }
            if(user_number==""){ 
                $('.pixfort_university_6 input[name=number]').css('border-color','red'); 
                proceed = false;
            }
            if(user_email==""){ 
                $('.pixfort_university_6 input[name=email]').css('border-color','red'); 
                proceed = false;
            }
            if(user_country==""){ 
                $('.pixfort_university_6 select[name=countries]').css('border-color','red'); 
                proceed = false;
            }
            // if(user_message==""){ 
            //     $('.pixfort_university_6 textarea[name=message]').css('border-color','red'); 
            //     proceed = false;
            // }
           //$.fancybox("#hidden_pix_6");


            //everything looks good! proceed...
            if(proceed) 
            {

                //data to be sent to server
                var post_data;
                var output;
                post_data = {'user_name':user_name, 'user_number':user_number, 'user_email':user_email, 'user_country':user_country };
                //Ajax post data to server
                
                $.post('pix_mail/contact_me_6.php', post_data, function(response){  
                    //load json data from server and output message     
                    if(response.type == 'error')
                    {
                        output = '<div class="error">'+response.text+'</div>';
                    }else{
                        $.fancybox("#hidden_pix_6");
                        output = '<div class="success">'+response.text+'</div>';
                        
                        //reset values in all input fields
                        $('.pixfort_university_6 #contact_form input').val(''); 
                        $('.pixfort_university_6 #contact_form textarea').val(''); 
                    }
                    
                    $(".pixfort_university_6 #result").hide().html(output).slideDown();
                }, 'json');
                
            }
        });
        
        //reset previously set border colors and hide all message on .keyup()
        $(".pixfort_university_6 #contact_form input, .pixfort_university_6 #contact_form textarea,  select[name=countries]").keyup(function() { 
            $(".pixfort_university_6 #contact_form input, .pixfort_university_6 #contact_form textarea,  select[name=countries]").css('border-color',''); 
            $(".pixfort_university_6 #result").slideUp();
        });
        //======================================================================================================











        //======================================================================================================
        //  END OF DOCUMENT
        //=================
});