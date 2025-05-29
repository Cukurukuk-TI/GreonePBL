@extends('layouts.admindashboard')

@section('content')
    <h1 class="text-2xl font-bold mb-4 pt-14">Dashboard</h1>
    <p>Selamat datang di halaman dashboard admin.</p>

        <table class="table-fixed w-full border-collapse pt-4">
            <tr>
                <!-- Kolom 1-3 dilebarkan -->
                <td class=" w-[24%] p-2">
                    <div class="bg-white shadow rounded-lg p-4 h-20"></div>
                </td>
                <td class=" w-[24%] p-2">
                    <div class="bg-white shadow rounded-lg p-4 h-20"></div>
                </td>
                <td class=" w-[24%] p-2">
                    <div class="bg-white shadow rounded-lg p-4 h-20"></div>
                </td>
                <!-- Kolom 4+5 digabung dan dikurangi 1/5 ukuran (jadi 28%) -->
                <td class="   w-[28%] p-2" colspan="2" rowspan="2">
                    <div class="bg-white shadow rounded-lg p-4 h-full min-h-[10rem]"></div>
                </td>
            </tr>
            <tr>
                <td class=" p-2">
                    <div class="bg-white shadow rounded-lg p-4 h-20"></div>
                </td>
                <td class=" p-2">
                    <div class="bg-white shadow rounded-lg p-4 h-20"></div>
                </td>
                <td class="  p-2">
                    <div class="bg-white shadow rounded-lg p-4 h-20"></div>
                </td>
            </tr>
        </table>

    </body>

    </html>
@endsection
