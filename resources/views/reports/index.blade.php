@extends('layouts.app')

@section('content')
    <x-section-heading class="border-emerald-700 mb-5">
        Reports - Index
    </x-section-heading>

    @foreach ($reports as $category => $group)
        <div class="text-xl my-3 text-gray-900 dark:text-gray-100">{{ $category }}</div>
        <ul>
            @foreach ($group as $report)
                <div class="mb-3"><a class="text-blue-800 hover:text-blue-600 dark:text-gray-300 dark:hover:text-gray-400 pl-5" href="{{ route('reports.view', ['id' => $report->id, 'report_name' => $report->report_name] ) }}">{{ $report->report_name }}</a></div>
            @endforeach
        </ul>
    @endforeach
    <div class="mt5-3"><button class="text-red-800 hover:text-red-400" onclick="history.back()">Back</button></div>


@endsection


