<!DOCTYPE html>
<html lang="en">
<head>
  <script src="js/jquery.min.js"></script>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Bungel Login</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">

    <link rel="stylesheet" href="plugins/toastr/toastr.css">
  <link rel="stylesheet" href="css/adminlte.min.css">



  <style>
    body {
      background-image: url("images/admin.jpg");
      background-size: cover;
      background-position: center;
    }

    .card {
      background-color: rgba(255, 255, 255, 0.6);
      border: none;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);

    }

  </style>

</head>

<body class="hold-transition login-page">

  <div class="login-box mb-3 ">
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="#" class="h1"><b>BUNGEL NIG LTD</b></a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Sign in to start your session</p>

        <form id="loginForm">
          <div class="input-group mb-3">
            <input type="username" id="usernameInput" name="usernameInput" class="form-control" placeholder="User Name">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>
          <div class="input-group mb-5">
            <input type="password" id="passwordInput" name="passwordInput" class="form-control" placeholder="Password">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>
          <div class="row  mb-4">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
<script>
  $(document).ready(function() {
    $('#loginForm').submit(function(e) {
      e.preventDefault();
      var formData = $(this).serialize();

      $.ajax({
        type: 'POST',
        url: 'login.php',
        data: formData,
        dataType: 'json',
        success: function(response) {
          toastr[response.success ? 'success' : 'error'](response.message);
          if (response.success) {
            // Redirect to dashboard after successful login
            setTimeout(function() {
              window.location.href = 'AdminMainPage.php';
            }, 2000);
          }
        },
        error: function(xhr) {
          console.error('Error:', xhr.responseText);
        }
      });
    });
  });
</script>


  <!-- Bootstrap 4 -->
  <script src="plugins/toastr/toastr.min.js"></script>

  <!-- AdminLTE App -->
 <script src="js/adminlte.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

</body>
</html>
