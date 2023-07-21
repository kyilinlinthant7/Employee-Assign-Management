<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login Account</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container" id="msg-box">
    @if (session('success'))
        <p id="success-message" class="alert alert-success">
            {{ session('success') }}
        </p>
    @endif
    </div>
    {{-- to hide success or error message automatically after 5s --}}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
    jQuery(document).ready(function() {
        setTimeout(function() {
        jQuery('#success-message, #error-message').fadeOut('slow');
        }, 2000);
    });
    </script>
    
    <div>
        <h2 class="text-center pt-4" style="color: #C0C0C0;">SYSTEM LOGIN</h2>
    </div>
    <div class="login-dark">
        <form action="{{ route('login') }}" method="POST">
            @csrf
            <!-- login error -->
            @if (session('error'))
                <p id="error-message" class="alert alert-danger">
                    {{ session('error') }}
                </p>
            @endif
            <!-- inputs -->
            <div class="illustration"><i class="icon ion-ios-locked-outline"></i></div>
            <div class="form-group"><input class="form-control" maxlength="5" type="text" name="login_id" placeholder="Employee ID" required></div>
            <div class="form-group"><input class="form-control" maxlength="16" type="password" name="password" placeholder="Password" required></div>
            <!-- button -->
            <div class="form-group"><button class="btn btn-primary btn-block" type="submit">Log In</button></div><a href="#" class="forgot">Forgot your username or password?</a>
        </form>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
