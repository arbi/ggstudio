$(function() {
	var currdate = new Date();
	var currdate= (currdate.getFullYear()) + '-' + (currdate.getMonth()+ 1) + '-' + currdate.getDate();

	$('.post-dt').append(currdate);
    $('.new-com-bt').click(function(event){    
        $(this).hide();
        $('.new-com-cnt').show();
        $('.the-new-com').focus();
    });

    $('.the-new-com').bind('input propertychange', function() {
       $(".bt-add-com").css({opacity:0.6});
       var checklength = $(this).val().length;
       if(checklength){ $(".bt-add-com").css({opacity:1}); }
    });

    $('.bt-cancel-com').click(function(){
        $('.the-new-com').val('');
        $('.new-com-cnt').fadeOut('fast', function(){
            $('.new-com-bt').fadeIn('fast');
        });
    });

    $('.bt-add-com').click(function(){
        var theCom  = $('.the-new-com');
        var title = $('#name-com');

        if( !theCom.val()){ 
            alert('You need to write a comment!'); 
        }else{ 
            $.ajax({
                type: "POST",
				dataType: "json",
                url: "http://localhost/ggs/api/comment",
                data: { writer: title.val(), comment: theCom.val()},
                success: function(data){
                	if (data != 'false') {
	                    theCom.val('');
	                    title.val('');
	                    var newCmnt = '<div class="cmt-cnt">' +
	                    	'<img src="http://www.gravatar.com/avatar/d41d8cd98f00b204e9800998ecf8427e?d=mm&s=35" alt="" />' +
	                    	'<div class="thecom"><h5>' + data.user + '</h5>' +
	                    	'<span data-utime="1411891939" class="com-dt">' + data.date + '</span><br/>' + 
	                    	'<p>' + data.comment + '</p></div></div>';


	                    $('.new-com-cnt').hide('fast', function(){
	                        $('.new-com-bt').show('fast');
	                        $('.new-com-bt').before(newCmnt);

	                    });
                	}
                }  
            });
        }
    });

	$('.login-bt').click(function(e) {
		// e.preventDefault();
		var username   = $('#username').val();
		var password   = $('#password').val();
		var userStatus = $('.login-bt').attr('data-status');

		if (userStatus == 1) {
			$.ajax({
	            type: "POST",
				dataType: "json",
	            url: "http://localhost/ggs/api/login/logout",
	            data: {status: userStatus},
	            success: function(data){
	            	if (data == 'true') {
	                	$('.login-bt').text('Login !');
	                	$('.welcome-cmt').text('Welcome Stranger');
	                	$('.login-bt').attr('data-status', 0);
	                	$('#username').attr('placeholder', ' Insert Username!');
	            		$('#password').attr('placeholder', ' Insert Password!');
	            	}
	            }  
	        });
		} else {
			$.ajax({
	            type: "POST",
				dataType: "json",
	            url: "http://localhost/ggs/api/login/authenticate",
	            data: { username: username, password: password},
	            success: function(data){

	            	if (data.result == 'true') {
	                	$('.login-bt').text('Logout !');
	                	$('.login-bt').attr('data-status', 1);
	            		$('.login-input').css("border", "");
	            		$('#username').val('');
	            		$('#password').val('');
	            		$('#username').attr('placeholder', ' Welcome Back!');
	            		$('#password').attr('placeholder', ' Welcome Back!');
	                	$('.welcome-cmt').text('Welcome ' + data.name);



	            	} else {
	            		$('.login-input').css("border", "#f35f5f 1px solid");
	            		$('#username').val('');
	            		$('#password').val('');
	            		$('#username').attr('placeholder', ' Wrong User Or Pass!');
	            		$('#password').attr('placeholder', ' Wrong User Or Pass!');
	            		$('.login-bt').attr('data-status', 0);
	            	}
	            }  
	        });
		}
	});
});