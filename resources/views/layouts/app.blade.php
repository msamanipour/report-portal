<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Report Portal</title>
    <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}" type="text/css">
    <style>
        .inset-shadow {
            box-shadow: 2px 1px 5px 1px rgba(0,0,0,0.11) inset;
            -webkit-box-shadow: 2px 1px 5px 1px rgba(0,0,0,0.11) inset;
            -moz-box-shadow: 2px 1px 5px 1px rgba(0,0,0,0.11) inset;

            background: transparent;
        }
        .bg-soft-danger {
            background-color: #c7a2a2 !important;
            border-color: transparent !important;
        }
    </style>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    @livewireStyles
</head>
<body>
@include('layouts.includes.header')
{{ $slot }}
<script src="{{ asset('js/bootstrap.bundle.min.js') }}" type="text/javascript"></script>
@stack('scripts')
@livewireScripts
</body>
</html>
