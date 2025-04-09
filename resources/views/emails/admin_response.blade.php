<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Ответ от администрации</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.5;">
    <h2 style="color: #e3342f;">Здравствуйте!</h2>

    <p><strong>Ваш вопрос:</strong></p>
    <p style="background-color: #f1f1f1; padding: 10px; border-radius: 6px;">
        {{ $userMessage }}
    </p>

    <p><strong>Ответ от администрации:</strong></p>
    <p style="background-color: #d1f0d1; padding: 10px; border-radius: 6px;">
        {{ $response }}
    </p>

    <br>
    <p>С уважением,<br>Команда <strong>JobHunt</strong></p>
</body>
</html>
