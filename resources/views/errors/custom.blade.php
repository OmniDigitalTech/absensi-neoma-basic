<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
    <style>
        body {
            background-color: #f9f9f9;
            font-family: Arial, Helvetica, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .error-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 600px;
            width: 100%;
        }
        h1 {
            color: #333333;
            font-size: 24px;
            margin-bottom: 10px;
        }
        p {
            color: #555555;
            font-size: 16px;
            margin-bottom: 20px;
        }
        a {
            display: inline-block;
            text-decoration: none;
            background-color: #007bff;
            color: #ffffff;
            padding: 10px 15px;
            border-radius: 5px;
            font-size: 16px;
            font-weight: bold;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<div class="error-container">
    <h1>Oops! Something went wrong.</h1>
    <p>We encountered an error while processing your request. Please try again later.</p>
    <a href="{{ url('/') }}">Go to Homepage</a>
</div>
</body>
</html>
