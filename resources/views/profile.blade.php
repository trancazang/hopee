@extends('layouts.app')

@section('content')



    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight text-center">
                {{ __('Profile') }}
            </h2>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <h2 class="text-lg font-medium text-gray-900">Ảnh đại diện</h2>
        
                    <div class="mt-4 flex items-center gap-4">
                        <img src="{{ Auth::user()->avatar_url }}" class="w-16 h-16 rounded-full shadow">
        
                        <form method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data">
                            @csrf
                            <input type="file" name="avatar" accept="image/*" class="text-sm">
                            <button class="ml-2 bg-gray-800 text-white px-3 py-1 rounded hover:bg-blue-600 text-sm">
                                Update
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.update-profile-information-form />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.update-password-form />
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    <livewire:profile.delete-user-form />
                </div>
            </div>
        </div>
    </div>
@endsection
