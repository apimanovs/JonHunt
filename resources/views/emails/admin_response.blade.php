<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Response from Administration</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.5;">
    <h2 style="color: #e3342f;">Hello!</h2>

    <p><strong>Your message:</strong></p>
    <p style="background-color: #f1f1f1; padding: 10px; border-radius: 6px;">
        {{ $userMessage }}
    </p>

    <p><strong>Response from the administration:</strong></p>
    <p style="background-color: #d1f0d1; padding: 10px; border-radius: 6px;">
        {{ $response }}
    </p>

    <br>
    <p>Best regards,<br>The <strong>JobHunt</strong> Team</p>
</body>
</html>
