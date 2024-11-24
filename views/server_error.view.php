<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error | Server Issue</title>
    <style>
        :root {
            --primary-bg: #2b2b2b;
            --secondary-bg: #520601;
            --text-color: #ffffff;
            --accent-color: #ff4c4c;
            --font-family: 'Arial', sans-serif;
        }

        body {
            margin: 0;
            font-family: var(--font-family);
            background-color: var(--primary-bg);
            color: var(--text-color);
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
        }

        .error-container {
            max-width: 90%;
            width: 600px;
            padding: 20px;
            background: var(--secondary-bg);
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        h1 {
            font-size: 2.8rem;
            color: var(--accent-color);
            margin-bottom: 10px;
        }

        p {
            font-size: 1.1rem;
            line-height: 1.6;
            margin-bottom: 20px;
        }

        .illustration {
            max-width: 100%;
            height: auto;
            margin: 20px auto;
        }

        .btn {
            display: inline-block;
            padding: 12px 24px;
            font-size: 1rem;
            color: var(--text-color);
            background-color: var(--accent-color);
            border: none;
            border-radius: 8px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .btn:hover {
            background-color: #e24444;
        }

        @media (max-width: 768px) {
            h1 {
                font-size: 2.2rem;
            }

            p {
                font-size: 1rem;
            }

            .btn {
                font-size: 0.9rem;
                padding: 10px 20px;
            }
        }

        @media (max-width: 480px) {
            h1 {
                font-size: 1.8rem;
            }

            p {
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="error-container">
        <h1>Server Error</h1>
        <p>We’re sorry, but it seems something went wrong on our end. Our team is working to fix the issue. Please try again later.</p>
        <img src="<?= $root_directory . 'assets/illustrations/server_down.svg' ?>" alt="Server Down Illustration" class="illustration">
    </div>
</body>
</html>
