<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f3f4f6; margin: 0; padding: 0; }
        .container { max-width: 600px; margin: 40px auto; background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); overflow: hidden; }
        .header { background-color: #4f46e5; padding: 30px 20px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 600; }
        .content { padding: 40px 30px; text-align: center; }
        .content h2 { color: #111827; margin-top: 0; font-size: 20px; }
        .content p { color: #4b5563; line-height: 1.6; font-size: 16px; margin-bottom: 25px; }
        .code-box { background-color: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 8px; padding: 20px; margin: 30px 0; }
        .code { font-size: 36px; font-weight: 700; color: #4f46e5; letter-spacing: 8px; margin: 0; }
        .footer { background-color: #f9fafb; padding: 20px; text-align: center; border-top: 1px solid #e5e7eb; }
        .footer p { color: #9ca3af; font-size: 14px; margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>UniPark</h1>
        </div>
        <div class="content">
            <h2>Hello, {{ $user->name }}!</h2>
            <p>Thank you for registering with UniPark. To complete your registration and secure your account, please verify your email address using the code below:</p>
            
            <div class="code-box">
                <p class="code">{{ $verificationCode }}</p>
            </div>
            
            <p>Enter this code on the verification page to activate your account. This code is valid for a limited time.</p>
            <p>If you did not create an account, no further action is required.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} UniPark. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
