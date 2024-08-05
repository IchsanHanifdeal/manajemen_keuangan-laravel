<!DOCTYPE html>
<html lang="en" data-theme="coffee">

@include('components.head')

<body class="flex flex-col mx-auto min-h-dvh {{ $full ?? 'max-w-screen-2xl' }}">
    <main class="{{ $class ?? 'p-4' }}">
        {{ $slot }}
    </main>
</body>

</html>
