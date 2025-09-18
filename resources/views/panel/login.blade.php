@php

$siteUrl = env('APP_URL');

@endphp

@extends('layout.admin.login')

@section('content')



<div class="authentication-wrapper authentication-cover">

  <!-- Logo -->

  <a href="{{route('admin.login')}}" class="app-brand auth-cover-brand">

    <span class="app-brand-logo demo">

      <span class="text-primary">

        <img src="{{$siteUrl}}public/images/logo.png" width="247" alt="">

      </span>

    </span>

  </a>

  <!-- /Logo -->

  <div class="authentication-inner row m-0">

    <!-- /Left Text -->

    <div class="d-none d-xl-flex col-xl-8 p-0">

      <div class="auth-cover-bg d-flex justify-content-center align-items-center"></div>

    </div>

    <!-- /Left Text -->



    <!-- Login -->

    <div class="d-flex col-12 col-xl-4 align-items-center authentication-bg p-sm-12 p-6">

      <div class="w-px-400 mx-auto mt-12 pt-5">

        <h4 class="mb-1">Welcome to MSP</h4>

        <p class="mb-6">Please sign-in to your account and start the adventure</p>



        <form id="adminLoginForm" class="mb-6" >

          <div class="mb-6 form-control-validation">

            <label for="email" class="form-label">Login ID</label>

            <input type="text" class="form-control" id="email" name="email" placeholder="Enter your Login ID" autofocus />

          </div>

          <div class="mb-6 form-password-toggle form-control-validation">

            <label class="form-label" for="password">Password</label>

            <div class="input-group input-group-merge">

              <input type="password" id="password" class="form-control" name="password" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7" aria-describedby="password" />

              <span class="input-group-text cursor-pointer"><i class="icon-base ti tabler-eye-off"></i></span>

            </div>

          </div>

          <div class="my-8"> 

            <div class="d-flex justify-content-between">

              <div class="form-check mb-0 ms-2">

                <input class="form-check-input" type="checkbox" id="remember-me" />

                <label class="form-check-label" for="remember-me"> Remember Me </label>

              </div>

              <?php /*?><a href="#">

                <p class="mb-0">Forgot Password?</p>

              </a><?php */?>

            </div>

          </div>

          <a id="loginSubmit" style="color:#FFF" class="btn btn-primary d-grid w-100">Sign in</a>

        </form>



        <!--<p class="text-center">

          <span>New on our platform?</span>

          <a href="#">

            <span>Create an account</span>

          </a>

        </p>-->



      </div>

    </div>

    <!-- /Login -->

  </div>

</div>

<script type="text/javascript">

	let adminLoginURL = "{{url('/panel/admin-login')}}";

	let dashboardURL = "{{url('/panel/dashboard')}}";

	$(document).ready(function(){

		$("#email, #password").on('keyup', function (e) {

			if (e.keyCode == 13) {

				$('#loginSubmit').trigger('click');

			}

		});	

		$('#loginSubmit').click(function(e){

			var flag = 0;

			if($.trim($("#email").val()) == ''){

				flag = 1;

				showMessage('Please Enter Account Username.');

				return false;

			}

			if($.trim($("#password").val()) == ''){

				flag = 1;

				showMessage('Please Enter Account Password.');

				return false;

			}

			if(flag == 0){

				$('#loginSubmit').html('Processing...');

				$.ajax({

					type: 'POST',

					headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},

					url: adminLoginURL,

					data: $('#adminLoginForm').serialize(),

					beforeSend:function(){$('#loginSubmit').removeClass('login-btn').addClass('login-btn-processing').val('Processing...'); },

					success: function(msg){

						var obj = JSON.parse(msg);

						$('#loginSubmit').html('Log In');
						
						if(obj['heading'] == "Success"){

							window.location.assign(dashboardURL);

						}else{

							showMessage(obj['msg']);

							return false;

						}

					},error: function(ts) {

						showMessage('Something went wrong, please try after sometime.');

						return false;

					}

				});
				return false;

			}

		});

	});

	function showMessage(msg){

		swal("Error!", msg, "error");

	}

</script>

@endsection