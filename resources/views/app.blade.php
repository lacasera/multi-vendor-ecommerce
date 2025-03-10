<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <title>Workwize Store</title>
        <script src="https://cdn.tailwindcss.com"></script>

        @viteReactRefresh
        @vite("resources/js/index.tsx")
    </head>
    <body>
        <div id="root"></div>
    </body>
</html>
