@extends('layouts.admin')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Manajemen Pengguna') }}
    </h2>
@endsection

@section('content')
    <div class="container mx-auto">
        {{-- Pesan Sukses --}}
        @if (session('success'))
            <div class="mb-4 p-4 bg-green-100 text-green-700 border border-green-400 rounded" role="alert">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white shadow-md rounded-lg overflow-hidden">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Nama
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Email
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Peran
                        </th>
                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                            Tindakan
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if(isset($users) && $users->count() > 0)
                        @foreach ($users as $user)
                            <tr>
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $user->name }}</p>
                                </td>
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                    <p class="text-gray-900 whitespace-no-wrap">{{ $user->email }}</p>
                                </td>
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                    @forelse ($user->roles as $role)
                                        <span class="bg-blue-200 text-blue-800 text-xs font-semibold mr-2 px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800">
                                            {{ $role->display_name }}
                                        </span>
                                    @empty
                                        <span class="text-gray-500">Tidak ada peran</span>
                                    @endforelse
                                </td>
                                <td class="px-5 py-4 border-b border-gray-200 bg-white text-sm">
                                    <a href="{{ route('admin.users.edit', $user->id) }}" class="text-indigo-600 hover:text-indigo-900">
                                        Edit
                                    </a>
                                    {{-- Nanti bisa ditambahkan tombol Hapus di sini --}}
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="px-5 py-5 border-b border-gray-200 bg-white text-sm text-center">
                                Tidak ada pengguna untuk ditampilkan.
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
            
            {{-- Paginasi Links --}}
            @if(isset($users) && $users->count() > 0)
            <div class="px-5 py-5 bg-white border-t flex flex-col xs:flex-row items-center xs:justify-between">
                {{ $users->links() }}
            </div>
            @endif
        </div>
    </div>
@endsection
