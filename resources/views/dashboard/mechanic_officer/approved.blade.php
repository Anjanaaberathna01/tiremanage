@extends('layouts.mechanic_officer')

@section('title', 'Approved Tire Requests')

@section('content')
<div class="container mx-auto p-6">
    <h2 class="text-2xl font-bold text-green-600 mb-6"> Approved Tire Requests</h2>

    @if($requests->isEmpty())
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
            No approved requests.
        </div>
    @else
        <table class="min-w-full border border-gray-300 rounded-lg overflow-hidden">
            <thead class="bg-green-100 text-green-800">
                <tr>
                    <th class="px-4 py-2">#</th>
                    <th class="px-4 py-2">Driver</th>
                    <th class="px-4 py-2">Vehicle</th>
                    <th class="px-4 py-2">Tire Count</th>
                    <th class="px-4 py-2">Description</th>
                    <th class="px-4 py-2">Status</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($requests as $request)
                    <tr class="border-b hover:bg-gray-100 transition">
                        <td class="px-4 py-2">{{ $request->id }}</td>
                        <td class="px-4 py-2">{{ $request->driver->name ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $request->vehicle->plate_number ?? 'N/A' }}</td>
                        <td class="px-4 py-2">{{ $request->tire_count }}</td>
                        <td class="px-4 py-2">{{ $request->description }}</td>
                        <td class="px-4 py-2 text-green-700 font-semibold">{{ ucfirst($request->status) }}</td>
                        <td class="px-4 py-2">
                            <a href="{{ route('mechanic_officer.requests.edit', $request->id) }}"
                               class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 transition">
                                ✏️ Edit
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
