@extends('layouts.main')

@section('content')
    <div class="mr-auto place-self-center lg:col-span-7">
        <h1 class="max-w-2xl mb-4 text-4xl font-normal leading-none tracking-tight md:text-5xl xl:text-6xl dark:text-white">
            @lang('messages.welcome')</h1>
        <p class="max-w-2xl mb-6 font-normal text-gray-500 lg:mb-8 md:text-lg lg:text-xl dark:text-gray-400">
            @lang('messages.description')</p>
        <div class="space-y-4 sm:flex sm:space-y-0 sm:space-x-4">
            <a href="https://ashikov.ru" class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow" target="_blank">
                @lang('messages.button')</a>
        </div>
    </div>
@endsection
