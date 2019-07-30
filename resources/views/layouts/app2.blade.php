<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <style>
      table.greenTable {
        font-family: Arial, Helvetica, sans-serif;
        background-color: #EEEEEE;
        width: 80%;
        text-align: left;
      }
      table.greenTable td, table.greenTable th {
        border: 1px solid #24943A;
        padding: 3px 2px;
      }
      table.greenTable tbody td {
        font-size: 13px;
      }
      table.greenTable th {
          border-bottom: 1px solid #24943A;
      }
      table.greenTable th {
        font-size: 13px;
        font-weight: normal;
        text-align: left;
      }
      h3 {
        font-family: Arial, Helvetica, sans-serif;
      }
    </style>

</head>
<body>
    <div>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>