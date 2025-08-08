<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selamat Datang | Jadwal Tugas</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #e0f7fa, #c7d2fe);
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .emoji {
            font-size: 80px;
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 100% {
                transform: translateY(0);
            }
            50% {
                transform: translateY(-10px);
            }
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center px-4">

    <div class="bg-white rounded-3xl shadow-2xl border border-indigo-200 p-10 max-w-md w-full text-center animate-fade-in">
        <div class="emoji mb-4">📚</div>
        <h1 class="text-4xl font-extrabold text-indigo-600 mb-2">Welcome!</h1>
        <p class="text-lg text-gray-600 mb-6">to my schedule</p>

        <a href="tugas.php"
           class="inline-block bg-gradient-to-r from-indigo-500 to-purple-500 text-white px-6 py-3 rounded-full text-lg font-semibold shadow-lg hover:scale-105 hover:shadow-2xl transition-all duration-300">
            Let's Go 🚀
        </a>
    </div>

</body>
</html>
