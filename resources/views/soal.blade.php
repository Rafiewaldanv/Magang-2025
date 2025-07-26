<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Soal Ujian</title>
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
            text-align: left;
        }

        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            text-align: center;
        }

        .soal {
            margin-bottom: 30px;
        }

        button {
            background-color: #e67e22;
            color: white;
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        button:hover {
            background-color: #d35400;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📝 Soal Ujian</h1>

        <div class="soal">
            <p><strong>1. Apa ibu kota Indonesia?</strong></p>
            <form method="POST" action="{{ route('soal.submit') }}">
                @csrf
                <label><input type="radio" name="jawaban" value="Jakarta"> Jakarta</label><br>
                <label><input type="radio" name="jawaban" value="Bandung"> Bandung</label><br>
                <label><input type="radio" name="jawaban" value="Surabaya"> Surabaya</label><br>
                <br>
                <button type="submit">Kumpulkan Jawaban</button>
            </form>
        </div>
    </div>
</body>
</html>
