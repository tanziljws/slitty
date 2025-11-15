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
        <input type="hidden" name="_token" id="csrfToken" value="{{ csrf_token() }}">
    </form>

    <script>
        // Function to get fresh CSRF token
        function getCsrfToken() {
            const metaTag = document.querySelector('meta[name="csrf-token"]');
            return metaTag ? metaTag.getAttribute('content') : document.getElementById('csrfToken').value;
        }

        // Function to submit logout form with fresh token
        function submitLogout() {
            const form = document.getElementById('logoutForm');
            const tokenInput = document.getElementById('csrfToken');
            
            // Update token before submit
            const freshToken = getCsrfToken();
            if (tokenInput && freshToken) {
                tokenInput.value = freshToken;
            }
            
            // Submit form
            if (form) {
                form.submit();
            }
        }

        // Try to submit immediately
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', submitLogout);
        } else {
            // DOM already loaded, submit immediately
            submitLogout();
        }
        
        // Fallback: submit after short delay
        setTimeout(submitLogout, 200);
    </script>
</body>
</html>

