<!DOCTYPE html>
<html>
<head>
    <title>Lossy Count</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    @vite('resources/css/lossycount/lossycount.css')

</head>
<body>


<div class="container">

    <div class="content-container">

        <div class="module left">
            <a href="{{ route('main-menu.index') }}">Вернуться</a>
            <div class="form-container">
                <h3>Lossy Count Алгоритм</h3>
                <div class="additional-info">
                    @if (session('success'))
                        <div class="message success">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
                <form method="POST" action="{{ route('lossycount.process') }}">
                    @csrf
                    <input type="text" name="elements" id="elements" placeholder="Введите элементы (через запятую)" required>
                    <button type="submit">Обработать</button>
                </form>
            </div>

        </div>


        <div class="right">

            <div class="module hashes-info">
                <h2>Частота элементов:</h2>
                @if ($frequencies->isEmpty())
                    <p>Нет данных</p>
                @else
                    <table>
                        <thead>
                        <tr>
                            <th>Элемент</th>
                            <th>Частота</th>
                            <th>Bucket</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($frequencies as $frequency)
                            <tr>
                                <td>{{ $frequency->element }}</td>
                                <td>{{ $frequency->frequency }}</td>
                                <td>{{ $frequency->bucket }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif
            </div>

        </div>
    </div>
</div>
</body>
</html>