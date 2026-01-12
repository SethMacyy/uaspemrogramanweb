<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UAS Project - Splash</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #1a1a1a; color: white; height: 100vh; display: flex; align-items: center; justify-content: center; font-family: sans-serif; }
        .loader { border: 5px solid #f3f3f3; border-top: 5px solid #3498db; border-radius: 50%; width: 50px; height: 50px; animation: spin 1s linear infinite; margin: 20px auto; }
        @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    </style>
</head>
<body>
    <div class="text-center">
        <h1>UAS PROJECT</h1>
        <div class="loader"></div>
        <p>Hyperspace Animation Loading...</p>
        <a href="login" class="btn btn-primary mt-3">Masuk ke Aplikasi</a>
    </div>
</body>
</html>