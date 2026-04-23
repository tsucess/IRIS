<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'iris') }}</title>

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        #particles-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: -1;
        }


        body {
            margin: 0;
            background: #000;
            min-height: 100vh;
        }

        .page-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            background: rgb(255, 255, 255);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 99999;
            transition: opacity 0.5s ease, visibility 0.5s ease;
        }

        .page-loader.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader-content {
            text-align: center;
            animation: slideDownFadeOut 0.6s ease forwards;
        }
    </style>
</head>

<body class="font-sans text-gray-900 antialiased">
    <div id="page-loader" class="page-loader">
        <div class="loader-content">
            <div class="spinner-border text-primary mx-auto" style="width: 4rem;" role="status">
                {{-- <span class="visually-hidden">Loading...</span> --}}
                <img class="animation__shake" src="{{ asset('images/logo.gif') }}" alt="iris Logo" height="60" width="100">
            </div>
            <p class="text-primary mt-3">Loading, please wait...</p>
        </div>
    </div>


    <div class="min-h-screen flex flex-col sm:justify-center items-center py-6 sm:pt-0 px-4">
        <div id="particles-background"></div>
        @vite('resources/js/particles.js')
        <div class="bg-white p-2 mx-auto text-center" style="border-radius: 50%;">
            <a href="/" >
                <x-application-logo2 class="w-24 h-24 sm:w-40 sm:h-40 fill-current text-gray-500" />
            </a>
        </div>

        <div class="w-full sm:max-w-md mt-6 px-4 sm:px-6 py-4 bg-white shadow-md overflow-hidden rounded-lg sm:rounded-lg z-10">

            {{ $slot }}
        </div>
    </div>


    <script>
        window.addEventListener('load', function() {
            const loader = document.getElementById('page-loader');
            setTimeout(() => {
                loader.classList.add('hidden');
            }, 1000); // wait a bit to make transition smoother
        });



        document.addEventListener('DOMContentLoaded', () => {
            const loader = document.getElementById('page-loader');

            document.querySelectorAll('form').forEach(form => {
                form.addEventListener('submit', () => {
                    loader.classList.remove('hidden');
                });
            });

        });
    </script>
</body>

</html>
