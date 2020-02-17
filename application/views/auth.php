<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>{title}</title>
	<!-- Tell the browser to be responsive to screen width -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Font Awesome -->
	<link rel="stylesheet" href="{base_url}assets/adminlte/plugins/fontawesome-free/css/all.min.css">
	<!-- Ionicons -->
	<link rel="stylesheet" href="{base_url}assets/adminlte/dist/css/ionicons.min.css">
	<!-- icheck bootstrap -->
	<link rel="stylesheet" href="{base_url}assets/adminlte/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
	<!-- Theme style -->
	<link rel="stylesheet" href="{base_url}assets/adminlte/dist/css/adminlte.min.css">
	<!-- Google Font: Source Sans Pro -->
	<!-- <link href="{base_url}assets/adminlte/dist/css/fonts.googleapis.com.css" rel="stylesheet"> -->
	<!-- Favicon-->
  	<link rel="icon" href="{base_url}{icon}" type="image/x-icon">
	<style  type="text/css">
   
		body {
		/*opacity: 0.2;
		filter: alpha(opacity=20);*/
		background-image:url({base_url}{bg_login}) !important;
		background-size: 100% !important;
		background-color: #D2D6DE !important;
		
			background-attachment: fixed !important;
			background-repeat: no-repeat !important;
			background-position:center center !important;
		}

		.preloader {
			position: fixed;
			top: 0;
			left: 0;
			width: 100%;
			height: 100%;
			z-index: 9999;
			background-color: #fff;
		}
		.preloader .loading {
			position: absolute;
			left: 50%;
			top: 50%;
			transform: translate(-50%,-50%);
			font: 14px arial;
		}

    </style>

	<!-- jQuery -->
	<script src="{base_url}assets/adminlte/plugins/jquery/jquery.min.js"></script>
	<!-- Bootstrap 4 -->
	<script src="{base_url}assets/adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
	<!-- AdminLTE App -->
	<script src="{base_url}assets/adminlte/dist/js/adminlte.min.js"></script>
	<script src="{base_url}assets/adminlte/dist/js/jquery.cookie.js"></script>
	<script>

		$(document).ready(function () {          

			$(".preloader").delay(500).fadeOut();
			$('#username').val($.cookie("username"));
			$('#password').val($.cookie("password"));
			$('#remember').prop('checked', $.cookie("remember"));

			$( "#form_auth" ).submit(function( event ) {
				
				event.preventDefault();
				var form = $(this);
				var url = form.attr('action');

				$.ajax({
					type: "POST",
					url: url,
					data: form.serialize(), // serializes the form's elements.
					dataType: 'json',
					success: function(data)
					{
						if(data.status=="success"){
							document.location.href="profile/data/update";
							if($('#remember').is(':checked')){
								$.cookie("username", $('#username').val());
								$.cookie("password", $('#password').val());
								$.cookie("remember", true);
							}else{
								$.removeCookie("username");
								$.removeCookie("password");
								$.removeCookie("remember");
							}
						}else{
							$('#alert').show(); 
							$('#error').append(data.messages); 
							setTimeout(function() {
								$('#alert').slideUp("slow");
								$('#error').empty(); 
							}, 2000);
						}
					}
					});

			});

			
		});
    </script>
</head>
<body class="hold-transition login-page">
<div class="preloader">
	<div class="loading">
		<img src="{base_url}assets/adminlte/dist/img/loading.gif" width="88">
		<!--       <div>
		<p>Harap Tunggu...</p>
		</div>-->
	</div>
</div>
<div class="login-box">
	<div class="login-logo">
	<img src="{base_url}assets/adminlte/dist/img/logo.png" height="100px"><br />
		<a href="#"><b>{title}</b></a>
	</div>
	<!-- /.login-logo -->
	<div class="card">
		<div class="card-body login-card-body">
		<p class="login-box-msg">Sign in to start your session</p>

		<form id="form_auth" action="auth/authentication" method="post">
			<div class="input-group mb-3">
			<input id="username" name="username" type="text" class="form-control" placeholder="Email">
			<div class="input-group-append">
				<div class="input-group-text">
				<span class="fas fa-envelope"></span>
				</div>
			</div>
			</div>
			<div class="input-group mb-3">
			<input id="password" name="password" type="password" class="form-control" placeholder="Password">
			<div class="input-group-append">
				<div class="input-group-text">
				<span class="fas fa-lock"></span>
				</div>
			</div>
			</div>
			<div class="row">
			<div class="col-8">
				<div class="icheck-primary">
				<input type="checkbox" id="remember">
				<label for="remember">
					Remember Me
				</label>
				</div>
			</div>
			<!-- /.col -->
			<div class="col-4">
				<button type="submit" class="btn btn-primary btn-block">Sign In</button>
			</div>
			<!-- /.col -->
			</div>
		</form>

		<!-- <div class="social-auth-links text-center mb-3">
			<p>- OR -</p>
			<a href="#" class="btn btn-block btn-primary">
			<i class="fab fa-facebook mr-2"></i> Sign in using Facebook
			</a>
			<a href="#" class="btn btn-block btn-danger">
			<i class="fab fa-google-plus mr-2"></i> Sign in using Google+
			</a>
		</div> -->
		<!-- /.social-auth-links -->

		<!-- <p class="mb-1">
			<a href="forgot-password.html">I forgot my password</a>
		</p>-->
		<p class="mb-0">
			<a href="{base_url}auth/register" class="text-center">Register</a>
		</p> 
		</div>
		<!-- /.login-card-body -->
	</div>
  	<div id="alert" class="alert alert-danger alert-dismissible" style="display:none;">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
		<h5><i class="icon fas fa-ban"></i> Alert!</h5>
		<span id="error"></span>
	</div>
</div>
<!-- /.login-box -->

</body>
</html>