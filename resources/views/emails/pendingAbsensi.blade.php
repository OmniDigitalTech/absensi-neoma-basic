<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Absensi Notification</title>
    <style>
        body {
            background-color: #f9f9f9; /* Light gray background */
            font-family: Arial, Helvetica, sans-serif;
            margin: 0;
            padding: 15px;
        }
        .email-container {
            max-width: 600px;
            background: #ffffff; /* White email background */
            padding: 20px;
            margin: 0 auto;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Nice shadow */
        }
        h1 {
            color: #333333;
            font-size: 24px;
            text-align: center;
        }
        p {
            font-size: 16px;
            color: #555555;
            line-height: 1.6;
        }
        .button-link {
            display: inline-block;
            text-decoration: none;
            background-color: #007bff; /* Bootstrap blue */
            color: #ffffff;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
            text-align: center;
        }
        .button-link:hover {
            background-color: #0056b3; /* Darker blue on hover */
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            text-align: center;
            color: #777777;
        }
    </style>
</head>
<body>
<div class="email-container">
    <h1>{{ $greeting }}, {{ $user->name }}!</h1>
    <p>{{ $notif }}</p>
    <p>Untuk melakukan absensi, klik tombol di bawah ini:</p>
    <div style="text-align: center;">
        <a href="{{ $url }}" class="button-link" target="_blank">Halaman Absensi</a>
    </div>
    <p>Thank you!</p>
    <p><i>- Neoma - Self Service Employee -</i></p>
    <div class="footer">
        <p>Jika Anda memiliki pertanyaan, hubungi <a href="mailto:support@neoma.com">support@neoma.com</a>.</p>
        <p>&copy; {{ date('Y') }} NEOMA. All rights reserved.</p>
    </div>
</div>
</body>
</html>
