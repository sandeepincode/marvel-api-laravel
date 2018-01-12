<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Marvel API</title>

    <link rel="stylesheet" href="./assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="./assets/fonts/ionicons.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <link rel="stylesheet" href="assets/css/Login-Form-Clean.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean1.css">
    <link rel="stylesheet" href="assets/css/Social-Icons.css">
    <link rel="stylesheet" href="assets/css/styles.css">

    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>

</head>

<body>
<div class="login-clean">
    <form method="POST" action="">
        <h2 class="sr-only">Login Form</h2>

        <div class="illustration">
            <h1 class="text-uppercase" style="font-family:Montserrat, sans-serif;color:rgb(224,25,60);"><strong>Marvel API</strong></h1>
        </div>

        @if($error)
            <div class="form-group">
                <div class="alert text-center" style="background-color: #e01a3c;color:  white;">
                    <b>{{$error}}</b>
                </div>
            </div>
        @endif

        <div class="form-group">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">
            <input type="text" name="character" placeholder="Character Name" class="form-control input-lg" />
        </div>

        <div class="form-group">
            <select class="form-control input-lg" name="type">
                <option selected value="">Select A Type</option>
                @foreach($options as $option)
                    <option  value={{$option}}>{{$option}}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <button class="btn btn-primary btn-block" type="submit" style="background-color:rgb(224,25,60);">Download</button>
        </div>
    </form>
</div>
<div class="social-icons">
    <a href="https://www.sandeepincode.com" target="_blank"><i class="icon ion-android-color-palette"></i></a>
    <a href="https://www.github.com/sandeepincode" target="_blank"><i class="icon ion-social-github"></i></a>
</div>
<div class="text-center">
    <span>Made with ❤ by @Sandeepincode </span>
</div>
</body>

</html>