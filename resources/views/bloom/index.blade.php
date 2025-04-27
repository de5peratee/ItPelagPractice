<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bloom Filter</title>
    @vite('resources/css/bloom/bloom.css')
</head>
<body>

<div class="container">

    <div class="content-container">
        <div class="module left">
            <a href="{{ route('main-menu.index') }}">Вернуться</a>
            <br>
            <div class="form-container">
                <h3>Bloom Filter</h3>
                <form action="{{ route('bloom.add') }}" method="POST" class="form">
                    @csrf
                    <input type="text" name="item" placeholder="Элемент для добавления">
                    <button type="submit">Добавить</button>
                </form>

                <form action="{{ route('bloom.check') }}" method="POST" class="form">
                    @csrf
                    <input type="text" name="item" placeholder="Элемент для проверки">
                    <button type="submit">Проверить</button>
                </form>
            </div>
        </div>

        <div class="right">
            <div class="additional-info">
                @if(session('success'))
                    <div class="message success">{{ session('success') }}</div>
                @endif

                @if(session('status'))
                    <div class="message status">{{ session('status') }}</div>
                @endif

                @if(session('hashes'))
                    <div class="hashes">
                        <strong>Хеши элемента:</strong>
                        <p>[ {{ implode(', ', session('hashes')) }} ]</p>
                    </div>
                @endif
                <br>
            </div>

            <div class="module hashes-info">
                <table>
                    <thead>
                    <tr>
                        <th>Элемент</th>
                        <th>Хеши</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($itemsWithHashes as $item)
                        <tr>
                            <td class="item-name">{{ $item->item }}</td>
                            <td class="item-hashes">{{ implode(', ', $item->hashes) }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>
</div>
</body>
</html>
