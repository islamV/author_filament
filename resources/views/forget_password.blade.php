<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>One Time Password</title>
</head>
<body>
<p>Your OTP <strong>{{ $otp }}</strong></p>
<p>Please enter this code to verify your identity.</p>
<p>This code is valid for 10 minutes.</p>
<p>Thank you,</p>
<p>{{ config('app.name') }}</p>
</body>
</html>
