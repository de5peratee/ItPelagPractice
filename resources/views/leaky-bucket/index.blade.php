<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaky Bucket Алгоритм</title>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    @vite([
    'resources/css/leaky-bucket/leaky-bucket.css',
    'resources/js/leaky-bucket/leaky-bucket.js'
    ])

</head>
<body>
<div class="container">
    <div class="bucket-info">
        <h3>Leaky Bucket Алгоритм</h3>
        <p>Ведро: <span id="requests">0</span>/<span id="capacity">0</span></p>
        <p>Скорость утечки: <span id="leak_rate">0</span> запросов/окно</p>
        <p>Временное окно: <span id="time_window">0</span> сек</p>
        <p>Текущее время: <span id="current_time"></span></p>
        <div class="controls">
            <button onclick="sendRequest()">Отправить запрос</button>
            <button id="auto-toggle" onclick="toggleAutoRequest()">Включить автоотправку</button>
            <div class="slider-container">
                <label class="slider-label" for="request-frequency">
                    Частота: <span id="frequency-value">1</span> запросов/сек
                </label>
                <input type="range" id="request-frequency" min="0.1" max="5" step="0.1" value="1">
            </div>
        </div>
    </div>
    <div class="room-container" id="room-container">
        <div class="room" id="room"></div>
    </div>
</div>

</body>
</html>