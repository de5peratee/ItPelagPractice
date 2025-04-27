<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <title>Document</title>
    @vite('resources/css/mainmenu.css')

</head>
<body>

<a href="{{route('leaky-bucket.index')}}">
    LeakyBucket
</a>
<a href="{{route('bloom.index')}}">
    BloomFilter
</a>
<a href="{{route('lossycount.index')}}">
    LossyCount
</a>


</body>
</html>