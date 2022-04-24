<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="pt-5">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg">
                <div class="flex flex-wrap">
                    <a href="{{ route('dashboard') }}" class="w-full cursor-pointer md:w-1/2 xl:w-1/3 p-6">
                        <!--Metric Card-->
                        <div
                            class="bg-gradient-to-b from-blue-200 to-blue-100 border-b-4 border-blue-500 rounded-lg shadow-xl p-5">
                            <div class="flex flex-row items-center">
                                <div class="flex-shrink pr-4">
                                    <div class="rounded-full p-5 bg-blue-600"><i
                                            class="fas fa-users fa-2x fa-inverse"></i></div>
                                </div>
                                <div class="flex-1 text-right md:text-center">
                                    <h5 class="font-bold uppercase text-gray-600">Total Contacts</h5>
                                    <h3 class="font-bold text-3xl">124</h3>
                                </div>
                            </div>
                        </div>
                        <!--/Metric Card-->
                    </a>
                    <a href="{{ route('dashboard') }}" class="w-full cursor-pointer md:w-1/2 xl:w-1/3 p-6">
                        <!--Metric Card-->
                        <div
                            class="bg-gradient-to-b from-green-200 to-green-100 border-b-4 border-green-600 rounded-lg shadow-xl p-5">
                            <div class="flex flex-row items-center">
                                <div class="flex-shrink pr-4">
                                    <div class="rounded-full p-5 bg-green-600"><i
                                            class="fa fa-wallet fa-2x fa-inverse"></i></div>
                                </div>
                                <div class="flex-1 text-right md:text-center">
                                    <h5 class="font-bold uppercase text-gray-600">Total Quotes</h5>
                                    <h3 class="font-bold text-3xl">1230 </h3>
                                </div>
                            </div>
                        </div>
                        <!--/Metric Card-->
                    </a>
                    <a href="{{ route('dashboard') }}" class="w-full cursor-pointer md:w-1/2 xl:w-1/3 p-6">
                        <!--Metric Card-->
                        <div
                            class="bg-gradient-to-b from-pink-200 to-pink-100 border-b-4 border-pink-500 rounded-lg shadow-xl p-5">
                            <div class="flex flex-row items-center">
                                <div class="flex-shrink pr-4">
                                    <div class="rounded-full p-5 bg-pink-600"><i
                                            class="fas fa-server fa-2x fa-inverse"></i></div>
                                </div>
                                <div class="flex-1 text-right md:text-center">
                                    <h5 class="font-bold uppercase text-gray-600">Total Contracts</h5>
                                    <h3 class="font-bold text-3xl">335</h3>
                                </div>
                            </div>
                        </div>
                        <!--/Metric Card-->
                    </a>
                </div>
            </div>
        </div>
    </div>
    @if ($logs->count())
        <div class="py-2">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-xl">
                    <div class="flex flex-wrap">
                        <a class="w-full cursor-pointer md:w-1/2 xl:w-full px-6">
                            <div class="px-5 py-1">
                                <div class="flex flex-row items-center">

                                    <div class="flex-1 text-right md:text-left">
                                        <h5 class="font-bold text-2xl text-gray-600">BED NUMBER</h5>
                                    </div>
                                    <div class="flex-1 text-right md:text-left">
                                        <h5 class="font-bold text-2xl text-gray-600">WARD NAME</h5>
                                    </div>
                                    <div class="flex-1 text-right md:text-center">
                                        <h5 class="font-bold text-3xl text-gray-600">%SpO<sub>2</sub></h5>
                                    </div>
                                    <div class="flex-1 text-right md:text-center">
                                        <h5 class="font-bold text-3xl text-gray-600">PR<sub>bpm</sub></h5>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
            @foreach ($logs as $log)
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white overflow-hidden shadow-xl">
                        <div class="flex flex-wrap">
                            <a class="w-full cursor-pointer md:w-1/2 xl:w-full px-6 py-1">
                                <div
                                    class="bg-gradient-to-b {{ (int) ($log->spo2 >= 0 && $log->spo2 <= 92)? 'from-red-200 to-red-100 border-b-4 border-red-500': ((int) $log->spo2 >= 93 && (int) $log->spo2 <= 94? 'from-yellow-200 to-yellow-100 border-b-4 border-yellow-500': ((int) $log->spo2 >= 95? 'from-green-200 to-green-100 border-b-4 border-green-500': '')) }} rounded-lg shadow-xl p-5">
                                    <div class="flex flex-row items-center">
                                        <div class="flex-1 text-right md:text-left">
                                            <div class="flex-1 flex-row items-center">
                                                <div class="flex flex-row items-center pr-4">
                                                    <div
                                                        class="rounded-full p-4 {{ (int) ($log->spo2 >= 0 && $log->spo2 <= 92)? 'bg-red-600': ((int) $log->spo2 >= 93 && (int) $log->spo2 <= 94? 'bg-yellow-600': ((int) $log->spo2 >= 95? 'bg-green-600': '')) }} mr-3">
                                                        <i class="fas fa-procedures fa-2x fa-inverse"></i>
                                                    </div>
                                                    <h3 class="font-bold text-5xl"> {{ $log->room_name }}</h3>
                                                </div>

                                            </div>
                                        </div>
                                        <div class="flex-1 text-right md:text-left">
                                            <h3 class="font-normal text-3xl">{{ $log->ward_name }}</h3>
                                        </div>
                                        <div class="flex-1 text-right md:text-center">
                                            <h3 class="font-bold text-6xl">
                                                {{ $log->spo2 == '999' ? '-' : $log->spo2 }}</h3>
                                        </div>
                                        <div class="flex-1 text-right md:text-center">
                                            <h3 class="font-bold text-4xl">
                                                {{ $log->spo2 == '999' ? '-' : $log->pulse }}</h3>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-5 bg-white overflow-hidden">
                <h3 class="font-semibold text-2xl text-gray-800 leading-tight text-center pt-4">Logs</h3>
                <div class="container mb-2 flex mx-auto w-full items-center justify-center">
                    <ul class="flex flex-col p-4">
                        <li class="border-gray-400 flex flex-row">
                            <div
                                class="select-none flex flex-1 items-center p-4 transition duration-500 ease-in-out transform hover:-translate-y-2 rounded-2xl border-2 p-3 hover:shadow-2xl border-red-400">
                                <div
                                    class="w-1/4 text-wrap text-center flex text-white text-bold flex-col rounded-md bg-red-500 justify-center items-center mr-10 p-2">
                                    24 Apr 2022 18:00:00
                                </div>
                                <div class="flex-1 pl-1 mr-16">
                                    <div class="font-medium">
                                        Contact scheduler job was started
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="border-gray-400 flex flex-row mb-2">
                            <div
                                class="select-none rounded-md flex flex-1 items-center p-4 transition duration-500 ease-in-out transform hover:-translate-y-2 rounded-2xl border-2 p-3 mt-3 border-red-400 hover:shadow-2xl">
                                <div
                                    class="w-1/4 text-wrap text-center text-white text-bold flex flex-col rounded-md bg-red-500 justify-center items-center mr-10 p-2">
                                    24 Apr 2022 18:06:00
                                </div>
                                <div class="flex-1 pl-1 mr-16">
                                    <div class="font-medium">
                                        134 contacts were created in Point of Sale System
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="border-gray-400 flex flex-row mb-2">
                            <div
                                class="select-none rounded-md flex flex-1 items-center p-4 transition duration-500 ease-in-out transform hover:-translate-y-2 rounded-2xl border-2 p-3 mt-3 border-red-400 hover:shadow-2xl">
                                <div
                                    class="flex w-1/4 text-wrap text-center flex-col text-white text-bold rounded-md bg-red-500 justify-center items-center mr-10 p-2">
                                    24 Apr 2022 19:26:34
                                </div>
                                <div class="flex-1 pl-1 mr-16">
                                    <div class="font-medium">Quote scheduler job was started</div>
                                </div>
                            </div>
                        </li>
                        <li class="border-gray-400 flex flex-row mb-2">
                            <div
                                class="select-none rounded-md flex flex-1 items-center p-4 transition duration-500 ease-in-out transform hover:-translate-y-2 rounded-2xl border-2 p-3 mt-3 border-red-400 hover:shadow-2xl">
                                <div
                                    class="w-1/4 text-wrap text-center flex flex-col text-white text-bold rounded-md bg-red-500 justify-center items-center mr-10 p-2">
                                    24 Apr 2022 19:34:22
                                </div>
                                <div class="flex-1 pl-1 mr-16">
                                    <div class="font-medium">
                                        Contract details updated in the pipeline.
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="border-gray-400 flex flex-row mb-2">
                            <div
                                class="select-none rounded-md flex flex-1 items-center p-4 transition duration-500 ease-in-out transform hover:-translate-y-2 rounded-2xl border-2 p-3 mt-3 border-red-400 hover:shadow-2xl">
                                <div
                                    class="w-1/4 text-wrap text-center flex flex-col text-white text-bold rounded-md bg-red-500 justify-center items-center mr-10 p-2">
                                    24 Apr 2022 19:44:22
                                </div>
                                <div class="flex-1 pl-1 mr-16">
                                    <div class="font-medium">
                                        Contract scheduler job was started
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="border-gray-400 flex flex-row mb-2">
                            <div
                                class="select-none rounded-md flex flex-1 items-center p-4 transition duration-500 ease-in-out transform hover:-translate-y-2 rounded-2xl border-2 p-3 mt-3 border-red-400 hover:shadow-2xl">
                                <div
                                    class="w-1/4 text-wrap text-center flex flex-col text-white text-bold rounded-md bg-red-500 justify-center items-center mr-10 p-2">
                                    24 Apr 2022 19:45:22
                                </div>
                                <div class="flex-1 pl-1 mr-16">
                                    <div class="font-medium">
                                        Contract details updated in the pipeline.
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        {{-- <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="mt-4 bg-white overflow-hidden">
                <div class="header-container container flex justify-between p-10">
                    <p class="w-full text-center">No Data Found</p>
                </div>
            </div>
        </div> --}}
    @endif
</x-app-layout>
