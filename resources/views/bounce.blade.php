<!DOCTYPE html>
<html>
<head>
    <title>Bounce Details</title>
</head>
<body>
    <h1>Download Bounce Report </h1>
    
    <form action="{{ url('/mailgun/bounces')  }}"" method="POST">
        @csrf <!-- This is required for POST requests to protect against CSRF attacks -->
        <button type="submit" class="btn btn-primary">Download</button>
    </form>

</body>
</html>
