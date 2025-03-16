@extends('layouts.app')

@section('content')
<div class="max-w-7xl mx-auto py-8">
    <h2 class="text-2xl font-bold">📋 Danh sách bài test</h2>
    
    <a href="{{ route('admin.tests.create') }}" class="bg-blue-500 text-white px-4 py-2 rounded mt-4 inline-block">
        ➕ Thêm bài test
    </a>

    <table class="w-full mt-6 bg-white shadow rounded-lg overflow-hidden">
        <thead>
            <tr class="bg-gray-200">
                <th class="px-4 py-2">Tên bài test</th>
                <th class="px-4 py-2">Mô tả</th>
                <th class="px-4 py-2">Hành động</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tests as $test)
            <tr class="border-t">
                <td class="px-4 py-2">{{ $test->title }}</td>
                <td class="px-4 py-2">{{ $test->description }}</td>
                <td class="px-4 py-2">
                    <a href="{{ route('admin.tests.edit', $test) }}" class="text-blue-500">✏️ Sửa</a> |
                    <form method="POST" action="{{ route('admin.tests.destroy', $test) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-500">🗑️ Xóa</button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
