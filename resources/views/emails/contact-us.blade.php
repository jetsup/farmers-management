{{-- email template view --}}
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Thank You for contacting us - {{ env('APP_NAME', "Farmer's") }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
    <div class="container">
        <div class="text-center">
            <h1 class="text-3xl font-bold text-green-700 mb-4">{{ env('APP_NAME', "Farmer's") }}</h1>
        </div>
        <p class="text-gray-600 mb-8">
            Hello {{ explode(' ', $sender_name)[0] }},<br>
            Thank you for visiting our site. We have received your message and one of our team members will get back to
            you shortly. We will review your message and respond as soon as possible.<br>In the meantime, feel free to
            check out our services and resources and also learn more about our community.
            <br>

            <br>
            Best regards,<br>
            <b>The {{ env('APP_NAME', "Farmer's") }} Team</b>
        </p>

        <div class="text-center">
            <p>&copy; {{ date('Y') }} {{ env('APP_NAME', "Farmer's") }}.
                All rights reserved.</p>
        </div>
    </div>
</body>
</html>
