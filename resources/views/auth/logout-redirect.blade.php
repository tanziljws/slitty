<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Logging out...</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .logout-container {
            text-align: center;
        }
        .spinner {
            border: 4px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top: 4px solid white;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 0 auto 20px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        h1 {
            margin: 0 0 10px 0;
            font-size: 24px;
            font-weight: 600;
        }
        p {
            margin: 0;
            opacity: 0.9;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="spinner"></div>
        <h1>Logging out...</h1>
        <p>Please wait while we log you out.</p>
    </div>

    <!-- Auto-submit logout form -->
    <form id="logoutForm" method="POST" action="{{ route('logout') }}" style="display: none;">
        @csrf
    </form>

    <script>
        // Auto-submit form immediately
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('logoutForm').submit();
        });
        
        // Fallback: submit after 100ms if DOMContentLoaded already fired
        setTimeout(function() {
            if (document.getElementById('logoutForm')) {
                document.getElementById('logoutForm').submit();
            }
        }, 100);
    </script>
</body>
</html>

