<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Selamat Datang</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f4f4;
            color: #333;
            text-align: center;
            padding: 50px;
        }

        .container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            display: inline-block;
            max-width: 600px;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
        }

        p {
            font-size: 18px;
            margin-bottom: 30px;
        }

        form button {
            background-color: #2ecc71;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        form button:hover {
            background-color: #27ae60;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📘 Selamat Datang di Tes Sederhana</h1>
        <p>Tekan tombol di bawah untuk memulai ujian.</p>
        <form method="POST" action="{{ route('mulai-ujian') }}">
            @csrf
            <button type="submit">Mulai Ujian</button>
        </form>
    </div>
</body>
</html>
