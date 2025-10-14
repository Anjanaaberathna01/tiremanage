<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Password Reset OTP</title>
</head>
<body style="font-family: Arial, sans-serif; background-color: #f8f9fa; margin: 0; padding: 20px;">

  <div style="max-width: 600px; background-color: #ffffff; margin: 0 auto; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    <h2 style="color: #333;">Password Reset Request</h2>

    <p style="font-size: 16px; color: #555;">
      Hi {{ $name ?? 'Driver' }},
    </p>

    <p style="font-size: 15px; color: #555;">
      You (or someone using your email) requested to reset your password. Use the OTP below to continue:
    </p>

    <div style="text-align: center; margin: 25px 0;">
      <span style="display: inline-block; background-color: #007bff; color: #fff; padding: 12px 24px; border-radius: 6px; font-size: 22px; letter-spacing: 3px;">
        {{ $otp }}
      </span>
    </div>

    <p style="font-size: 14px; color: #555;">
      This code will expire in <strong>10 minutes</strong>.
    </p>

    <p style="font-size: 14px; color: #555;">
      If you didn’t request this, please ignore this email.
    </p>

    <hr style="margin: 20px 0; border: none; border-top: 1px solid #eee;">

    <p style="font-size: 13px; color: #777; text-align: center;">
      — Tyre Management System <br>
      <small>This is an automated email. Please do not reply.</small>
    </p>
  </div>

</body>
</html>
