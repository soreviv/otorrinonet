@extends('layouts.app')

@section('title', 'Contact Messages')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">Contact Messages</h1>
        <div class="bg-white shadow-md rounded my-6">
            <table class="min-w-full table-auto">
                <thead>
                    <tr class="bg-gray-200 text-gray-600 uppercase text-sm leading-normal">
                        <th class="py-3 px-6 text-left">Name</th>
                        <th class="py-3 px-6 text-left">Email</th>
                        <th class="py-3 px-6 text-left">Phone</th>
                        <th class="py-3 px-6 text-left">Subject</th>
                        <th class="py-3 px-6 text-left">Message</th>
                    </tr>
                </thead>
                <tbody class="text-gray-600 text-sm font-light">
                    @foreach($messages as $message)
                        <tr class="border-b border-gray-200 hover:bg-gray-100">
                            <td class="py-3 px-6 text-left whitespace-nowrap">{{ $message->name }}</td>
                            <td class="py-3 px-6 text-left">{{ $message->email }}</td>
                            <td class="py-3 px-6 text-left">{{ $message->phone }}</td>
                            <td class="py-3 px-6 text-left">{{ $message->asunto }}</td>
                            <td class="py-3 px-6 text-left">{{ $message->message }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
