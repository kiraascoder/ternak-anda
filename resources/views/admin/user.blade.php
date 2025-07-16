@extends('layouts.app')

@section('title', 'Kelola User')
@section('page-title', 'Kelola User')
@section('page-description', 'Kelola dan pantau semua akun pengguna sistem')

@push('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@push('styles')
    <style>
        .card-hover {
            transition: all 0.3s ease;
        }

        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 500;
        }

        .status-aktif {
            background-color: #dcfce7;
            color: #166534;
        }

        .status-nonaktif {
            background-color: #fecaca;
            color: #991b1b;
        }

        .status-suspend {
            background-color: #fef3c7;
            color: #92400e;
        }

        .search-box {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
        }

        .table-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .action-btn {
            padding: 0.5rem;
            border-radius: 0.5rem;
            transition: all 0.2s ease;
        }

        .action-btn:hover {
            transform: scale(1.1);
        }

        .border-red-500 {
            border-color: #ef4444 !important;
            box-shadow: 0 0 0 1px #ef4444 !important;
        }

        .modal-backdrop {
            animation: fadeIn 0.3s ease-out;
        }

        .modal-content {
            animation: slideIn 0.3s ease-out;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: scale(0.95) translateY(-20px);
            }

            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }

        .view-btn-active {
            background-color: white !important;
            color: #111827 !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
        }

        .detail-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 0.25rem;
        }

        .detail-value {
            color: #6b7280;
            margin-bottom: 1rem;
        }

        .user-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 1.5rem;
        }

        .role-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-weight: 500;
        }

        .role-admin {
            background-color: #ddd6fe;
            color: #5b21b6;
        }

        .role-peternak {
            background-color: #bbf7d0;
            color: #166534;
        }

        .role-user {
            background-color: #dbeafe;
            color: #1e40af;
        }
    </style>
@endpush

@section('content')
    <div class="space-y-6">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                    </div>
                    <input type="text" id="searchInput"
                        class="search-box block w-full pl-10 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                        placeholder="Cari user..." onkeyup="searchUser()">
                </div>            
                <select id="roleFilter"
                    class="block px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                    onchange="filterByRole()">
                    <option value="">Semua Role</option>
                    <option value="admin">Admin</option>
                    <option value="peternak">Peternak</option>
                    <option value="user">User</option>
                </select>
            </div>

            <div class="flex items-center space-x-3">
                <button onclick="openAddModal()"
                    class="inline-flex items-center px-4 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary transform hover:scale-105 transition-all">
                    <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Tambah User
                </button>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total User</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $totalUser ?? 150 }}</p>
                    </div>
                    <div class="bg-blue-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Aktif</p>
                        <p class="text-2xl font-bold text-green-600">{{ $userAktif ?? 135 }}</p>
                    </div>
                    <div class="bg-green-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Non-Aktif</p>
                        <p class="text-2xl font-bold text-red-600">{{ $userNonAktif ?? 12 }}</p>
                    </div>
                    <div class="bg-red-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Suspend</p>
                        <p class="text-2xl font-bold text-yellow-600">{{ $userSuspend ?? 3 }}</p>
                    </div>
                    <div class="bg-yellow-100 p-3 rounded-full">
                        <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <span class="text-sm text-gray-600">Tampilan:</span>
                <div class="flex bg-gray-100 rounded-lg p-1">
                    <button onclick="switchView('grid')" id="gridViewBtn"
                        class="px-3 py-1 text-sm font-medium rounded-md transition-colors view-btn-active">
                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z">
                            </path>
                        </svg>
                        Grid
                    </button>
                    <button onclick="switchView('table')" id="tableViewBtn"
                        class="px-3 py-1 text-sm font-medium rounded-md transition-colors text-gray-600 hover:text-gray-900">
                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M3 10h18M3 14h18m-9-4v8m-7 0V4a1 1 0 011-1h3M8 3v2m0 0V3m0 2h8m0-2v2m0-2V3m0 2h3a1 1 0 011 1v16">
                            </path>
                        </svg>
                        Tabel
                    </button>
                </div>
            </div>

            <div class="text-sm text-gray-600">
                Menampilkan {{ $userList->count() ?? 10 }} dari {{ $totalUser ?? 150 }} user
            </div>
        </div>

        <!-- Grid View -->
        <div id="gridView" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @forelse ($userList as $index => $user)
                <div class="bg-white rounded-xl shadow-sm border border-gray-100 card-hover user-card"
                    data-name="{{ $user->name ?? 'User #' . sprintf('%03d', $index + 1) }}"
                    data-status="{{ $user->status ?? 'aktif' }}" data-id="{{ $user->id ?? $index + 1 }}"
                    data-email="{{ $user->email ?? 'user' . ($index + 1) . '@example.com' }}"
                    data-phone="{{ $user->phone ?? '08' . str_pad(rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT) }}"
                    data-role="{{ $user->role ?? ['admin', 'peternak', 'user'][rand(0, 2)] }}"
                    data-created="{{ $user->created_at ?? date('Y-m-d H:i:s', strtotime('-' . rand(1, 365) . ' days')) }}"
                    data-last-login="{{ $user->last_login ?? date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days')) }}">

                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="user-avatar">
                                {{ strtoupper(substr($user->name ?? 'U', 0, 2)) }}
                            </div>
                            <span class="status-badge status-{{ $user->status ?? 'aktif' }}">
                                {{ ucfirst($user->status ?? 'aktif') }}
                            </span>
                        </div>

                        <div class="space-y-2">
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $user->name ?? 'User #' . sprintf('%03d', $index + 1) }}
                            </h3>
                            <p class="text-sm text-gray-600">
                                {{ $user->email ?? 'user' . ($index + 1) . '@example.com' }}
                            </p>
                            <div class="flex items-center justify-between">
                                <span
                                    class="role-badge role-{{ $user->role ?? ['admin', 'peternak', 'user'][rand(0, 2)] }}">
                                    {{ ucfirst($user->role ?? ['admin', 'peternak', 'user'][rand(0, 2)]) }}
                                </span>
                                <div class="flex items-center text-sm text-gray-500">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    {{ \Carbon\Carbon::parse($user->created_at ?? date('Y-m-d'))->diffForHumans() }}
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-between mt-6">
                            <div class="flex space-x-2">
                                <button onclick="openDetailModal(this)"
                                    class="action-btn bg-blue-100 text-blue-600 hover:bg-blue-200" title="Lihat Detail">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </button>
                                <button onclick="openEditModal(this)"
                                    class="action-btn bg-green-100 text-green-600 hover:bg-green-200" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                </button>
                                <button onclick="openDeleteModal('{{ $user->id ?? $index + 1 }}')"
                                    class="action-btn bg-red-100 text-red-600 hover:bg-red-200" title="Hapus"
                                    type="button">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </div>

                            @if (($user->status ?? 'aktif') !== 'aktif')
                                <button onclick="toggleUserStatus('{{ $user->id ?? $index + 1 }}')"
                                    class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full hover:bg-yellow-200 transition-colors">
                                    Aktivasi
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500">
                    Tidak ada data user tersedia.
                </div>
            @endforelse
        </div>

        <!-- Table View -->
        <div id="tableView" class="hidden bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="table-header px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Daftar User</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Bergabung</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="tableBody">
                        @forelse($userList as $index => $user)
                            <tr class="hover:bg-gray-50 user-row" data-name="{{ $user->name }}"
                                data-status="{{ $user->status }}" data-role="{{ $user->role }}"
                                data-id="{{ $user->id ?? $index + 1 }}"
                                data-email="{{ $user->email ?? 'user' . ($index + 1) . '@example.com' }}"
                                data-phone="{{ $user->phone ?? '08' . str_pad(rand(1000000000, 9999999999), 10, '0', STR_PAD_LEFT) }}"
                                data-created="{{ $user->created_at ?? date('Y-m-d H:i:s', strtotime('-' . rand(1, 365) . ' days')) }}"
                                data-last-login="{{ $user->last_login ?? date('Y-m-d H:i:s', strtotime('-' . rand(1, 30) . ' days')) }}">

                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="user-avatar mr-3" style="width: 40px; height: 40px; font-size: 1rem;">
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">ID: #{{ $user->id ?? $index + 1 }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $user->email ?? 'user' . ($index + 1) . '@example.com' }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="role-badge role-{{ $user->role ?? ['admin', 'peternak', 'user'][rand(0, 2)] }}">
                                        {{ ucfirst($user->role ?? ['admin', 'peternak', 'user'][rand(0, 2)]) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="status-badge status-{{ $user->status }}">
                                        {{ ucfirst($user->status) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ \Carbon\Carbon::parse($user->created_at ?? date('Y-m-d'))->format('d M Y') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <div class="flex space-x-2">
                                        <button onclick="openDetailModal(this)"
                                            class="action-btn bg-blue-100 text-blue-600 hover:bg-blue-200"
                                            title="Lihat Detail">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                        <button onclick="openEditModal(this)"
                                            class="action-btn bg-green-100 text-green-600 hover:bg-green-200"
                                            title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                            </svg>
                                        </button>
                                        <button onclick="openDeleteModal('{{ $user->id ?? $index + 1 }}')"
                                            class="action-btn bg-red-100 text-red-600 hover:bg-red-200" title="Hapus"
                                            type="button">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center px-6 py-4 text-gray-500">Tidak ada data user
                                    tersedia.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Pagination -->
        @if (isset($userList) && method_exists($userList, 'links'))
            <div class="flex justify-center">
                {{ $userList->links() }}
            </div>
        @else
            <div class="flex justify-center">
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                    <a href="#"
                        class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Previous</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                    <a href="#"
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-primary text-sm font-medium text-white">1</a>
                    <a href="#"
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">2</a>
                    <a href="#"
                        class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">3</a>
                    <a href="#"
                        class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Next</span>
                        <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                </nav>
            </div>
        @endif
    </div>

    <!-- Add User Modal -->
    <div id="addModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">Tambah User Baru</h3>
                <button onclick="closeAddModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="addUserForm" action="{{ route('user.store') }}" method="POST">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1 md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="name" name="name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Masukkan nama lengkap">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="email" name="email" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="user@example.com">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            No. Telepon
                        </label>
                        <input type="tel" id="phone" name="phone"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="08xxxxxxxxxx">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password" name="password" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Minimal 8 karakter">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                            Konfirmasi Password <span class="text-red-500">*</span>
                        </label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Ulangi password">
                    </div>

                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select id="role" name="role" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="peternak">Peternak</option>
                            <option value="user">User</option>
                        </select>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-4">
                            <label
                                class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="status" value="aktif" checked
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                                        <span class="text-sm font-medium text-gray-900">Aktif</span>
                                    </div>
                                    <p class="text-xs text-gray-500">User dapat login</p>
                                </div>
                            </label>

                            <label
                                class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="status" value="nonaktif"
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                                        <span class="text-sm font-medium text-gray-900">Non-Aktif</span>
                                    </div>
                                    <p class="text-xs text-gray-500">User tidak dapat login</p>
                                </div>
                            </label>

                            <label
                                class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="status" value="suspend"
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                                        <span class="text-sm font-medium text-gray-900">Suspend</span>
                                    </div>
                                    <p class="text-xs text-gray-500">Akun di suspend</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeAddModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="submitBtn"
                        class="px-6 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary transition-colors">
                        <span id="submitText">Simpan User</span>
                        <svg id="submitLoading" class="hidden animate-spin -mr-1 ml-3 h-4 w-4 text-white inline"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Detail User Modal -->
    <div id="detailModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-3xl shadow-lg rounded-lg bg-white modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">Detail User</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                    <div class="flex items-center space-x-4 mb-6">
                        <div class="user-avatar" style="width: 80px; height: 80px; font-size: 2rem;" id="detailAvatar">
                            U
                        </div>
                        <div>
                            <div class="detail-label">Nama Lengkap</div>
                            <div class="detail-value text-lg font-semibold" id="detailNama">-</div>
                        </div>
                    </div>

                    <div>
                        <div class="detail-label">ID User</div>
                        <div class="detail-value" id="detailId">-</div>
                    </div>

                    <div>
                        <div class="detail-label">Email</div>
                        <div class="detail-value" id="detailEmail">-</div>
                    </div>

                    <div>
                        <div class="detail-label">No. Telepon</div>
                        <div class="detail-value" id="detailPhone">-</div>
                    </div>

                    <div>
                        <div class="detail-label">Role</div>
                        <div class="detail-value">
                            <span id="detailRole" class="role-badge">-</span>
                        </div>
                    </div>

                    <div>
                        <div class="detail-label">Status</div>
                        <div class="detail-value">
                            <span id="detailStatus" class="status-badge">-</span>
                        </div>
                    </div>
                </div>

                <div class="space-y-4">
                    <div>
                        <div class="detail-label">Tanggal Bergabung</div>
                        <div class="detail-value" id="detailCreated">-</div>
                    </div>

                    <div>
                        <div class="detail-label">Login Terakhir</div>
                        <div class="detail-value" id="detailLastLogin">-</div>
                    </div>

                    <div>
                        <div class="detail-label">Email Verified</div>
                        <div class="detail-value" id="detailEmailVerified">
                            <span
                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Terverifikasi
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                <button onclick="closeDetailModal()"
                    class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors">
                    Tutup
                </button>
            </div>
        </div>
    </div>

    <!-- Edit User Modal -->
    <div id="editModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
        <div class="relative top-10 mx-auto p-5 border w-full max-w-2xl shadow-lg rounded-lg bg-white modal-content">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-xl font-semibold text-gray-900">Edit User</h3>
                <button onclick="closeEditModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                </button>
            </div>

            <form id="editUserForm" method="POST">
                @csrf
                @method('PUT')
                <input type="hidden" id="editUserId" name="id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="col-span-1 md:col-span-2">
                        <label for="editName" class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Lengkap <span class="text-red-500">*</span>
                        </label>
                        <input type="text" id="editName" name="name" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="Masukkan nama lengkap">
                    </div>

                    <div>
                        <label for="editEmail" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        <input type="email" id="editEmail" name="email" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="user@example.com">
                    </div>

                    <div>
                        <label for="editPhone" class="block text-sm font-medium text-gray-700 mb-2">
                            No. Telepon
                        </label>
                        <input type="tel" id="editPhone" name="phone"
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"
                            placeholder="08xxxxxxxxxx">
                    </div>

                    <div>
                        <label for="editRole" class="block text-sm font-medium text-gray-700 mb-2">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select id="editRole" name="role" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                            <option value="">Pilih Role</option>
                            <option value="admin">Admin</option>
                            <option value="peternak">Peternak</option>
                            <option value="user">User</option>
                        </select>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Reset Password
                        </label>
                        <div class="flex items-center">
                            <input type="checkbox" id="resetPassword" name="reset_password"
                                class="h-4 w-4 text-primary focus:ring-primary border-gray-300 rounded">
                            <label for="resetPassword" class="ml-2 text-sm text-gray-700">
                                Generate password baru dan kirim ke email user
                            </label>
                        </div>
                    </div>

                    <div class="col-span-1 md:col-span-2">
                        <label for="editStatus" class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <div class="grid grid-cols-3 gap-4">
                            <label
                                class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="status" value="aktif" id="editStatusAktif"
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <span class="w-3 h-3 bg-green-500 rounded-full mr-2"></span>
                                        <span class="text-sm font-medium text-gray-900">Aktif</span>
                                    </div>
                                    <p class="text-xs text-gray-500">User dapat login</p>
                                </div>
                            </label>

                            <label
                                class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="status" value="nonaktif" id="editStatusNonaktif"
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <span class="w-3 h-3 bg-red-500 rounded-full mr-2"></span>
                                        <span class="text-sm font-medium text-gray-900">Non-Aktif</span>
                                    </div>
                                    <p class="text-xs text-gray-500">User tidak dapat login</p>
                                </div>
                            </label>

                            <label
                                class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 transition-colors">
                                <input type="radio" name="status" value="suspend" id="editStatusSuspend"
                                    class="h-4 w-4 text-primary focus:ring-primary border-gray-300">
                                <div class="ml-3">
                                    <div class="flex items-center">
                                        <span class="w-3 h-3 bg-yellow-500 rounded-full mr-2"></span>
                                        <span class="text-sm font-medium text-gray-900">Suspend</span>
                                    </div>
                                    <p class="text-xs text-gray-500">Akun di suspend</p>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                    <button type="button" onclick="closeEditModal()"
                        class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 transition-colors">
                        Batal
                    </button>
                    <button type="submit" id="editSubmitBtn"
                        class="px-6 py-2 bg-primary text-white text-sm font-medium rounded-lg hover:bg-secondary focus:outline-none focus:ring-2 focus:ring-primary transition-colors">
                        <span id="editSubmitText">Update User</span>
                        <svg id="editSubmitLoading" class="hidden animate-spin -mr-1 ml-3 h-4 w-4 text-white inline"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal"
        class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50 modal-backdrop">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-lg bg-white modal-content">
            <div class="mt-3 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.664-.833-2.464 0L4.35 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mt-4">Hapus User</h3>
                <p class="text-sm text-gray-500 mt-2">
                    Apakah Anda yakin ingin menghapus user <span id="deleteNamaUser" class="font-semibold"></span>?
                    Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="items-center px-4 py-3 mt-4 flex justify-center space-x-2">
                    <button id="confirmDeleteBtn" type="button"
                        class="px-4 py-2 bg-red-600 text-white text-base font-medium rounded-md hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 min-w-[100px] flex items-center justify-center">
                        <span id="deleteText">Hapus</span>
                        <svg id="deleteLoading" class="hidden animate-spin ml-2 h-4 w-4 text-white" fill="none"
                            viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                            </path>
                        </svg>
                    </button>
                    <button onclick="closeDeleteModal()" type="button"
                        class="px-4 py-2 bg-gray-300 text-gray-900 text-base font-medium rounded-md hover:bg-gray-400 focus:outline-none focus:ring-2 focus:ring-gray-300 min-w-[100px]">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Global variables
        let currentView = "grid";
        let deleteId = null;
        let currentUserData = null;

        // View switching function
        function switchView(view) {
            currentView = view;
            const gridView = document.getElementById("gridView");
            const tableView = document.getElementById("tableView");
            const gridBtn = document.getElementById("gridViewBtn");
            const tableBtn = document.getElementById("tableViewBtn");

            if (view === "grid") {
                gridView.classList.remove("hidden");
                tableView.classList.add("hidden");
                gridBtn.classList.add("view-btn-active", "bg-white", "text-gray-900", "shadow-sm");
                gridBtn.classList.remove("text-gray-600");
                tableBtn.classList.remove("view-btn-active", "bg-white", "text-gray-900", "shadow-sm");
                tableBtn.classList.add("text-gray-600");
            } else {
                gridView.classList.add("hidden");
                tableView.classList.remove("hidden");
                tableBtn.classList.add("view-btn-active", "bg-white", "text-gray-900", "shadow-sm");
                tableBtn.classList.remove("text-gray-600");
                gridBtn.classList.remove("view-btn-active", "bg-white", "text-gray-900", "shadow-sm");
                gridBtn.classList.add("text-gray-600");
            }
        }

        // Search function
        function searchUser() {
            const searchTerm = document.getElementById("searchInput").value.toLowerCase();
            const cards = document.querySelectorAll(".user-card");
            const rows = document.querySelectorAll(".user-row");

            cards.forEach((card) => {
                const name = card.getAttribute("data-name").toLowerCase();
                const email = card.getAttribute("data-email").toLowerCase();
                card.style.display = (name.includes(searchTerm) || email.includes(searchTerm)) ? "block" : "none";
            });

            rows.forEach((row) => {
                const name = row.getAttribute("data-name").toLowerCase();
                const email = row.getAttribute("data-email").toLowerCase();
                row.style.display = (name.includes(searchTerm) || email.includes(searchTerm)) ? "table-row" :
                "none";
            });
        }

        // Filter by status function
        function filterByStatus() {
            const statusFilter = document.getElementById("statusFilter").value;
            const cards = document.querySelectorAll(".user-card");
            const rows = document.querySelectorAll(".user-row");

            cards.forEach((card) => {
                const status = card.getAttribute("data-status");
                card.style.display = !statusFilter || status === statusFilter ? "block" : "none";
            });

            rows.forEach((row) => {
                const status = row.getAttribute("data-status");
                row.style.display = !statusFilter || status === statusFilter ? "table-row" : "none";
            });
        }

        // Filter by role function
        function filterByRole() {
            const roleFilter = document.getElementById("roleFilter").value;
            const cards = document.querySelectorAll(".user-card");
            const rows = document.querySelectorAll(".user-row");

            cards.forEach((card) => {
                const role = card.getAttribute("data-role");
                card.style.display = !roleFilter || role === roleFilter ? "block" : "none";
            });

            rows.forEach((row) => {
                const role = row.getAttribute("data-role");
                row.style.display = !roleFilter || role === roleFilter ? "table-row" : "none";
            });
        }

        // Add modal functions
        function openAddModal() {
            document.getElementById("addModal").classList.remove("hidden");
            document.body.style.overflow = "hidden";
            setTimeout(() => document.getElementById("name").focus(), 100);
        }

        function closeAddModal() {
            const modal = document.getElementById("addModal");
            modal.classList.add("hidden");
            document.body.style.overflow = "auto";
            document.getElementById("addUserForm").reset();

            // Reset status radio button ke "aktif"
            const aktifRadio = document.querySelector('input[name="status"][value="aktif"]');
            if (aktifRadio) aktifRadio.checked = true;
        }

        // Get user data from element
        function getUserDataFromElement(element) {
            let parent = element.closest('[data-id]');
            if (!parent) {
                parent = element.closest('.user-card, .user-row');
            }

            if (!parent) {
                console.error('Parent element dengan data tidak ditemukan');
                return null;
            }

            return {
                id: parent.getAttribute("data-id"),
                name: parent.getAttribute("data-name"),
                email: parent.getAttribute("data-email"),
                phone: parent.getAttribute("data-phone"),
                role: parent.getAttribute("data-role"),
                status: parent.getAttribute("data-status"),
                created: parent.getAttribute("data-created"),
                lastLogin: parent.getAttribute("data-last-login")
            };
        }

        // Detail modal functions
        function openDetailModal(element) {
            const data = getUserDataFromElement(element);
            if (!data) {
                alert('Data user tidak ditemukan');
                return;
            }

            currentUserData = data;
            document.getElementById("detailAvatar").textContent = data.name ? data.name.substring(0, 2).toUpperCase() : 'U';
            document.getElementById("detailNama").textContent = data.name || '-';
            document.getElementById("detailId").textContent = "#" + (data.id || '-');
            document.getElementById("detailEmail").textContent = data.email || '-';
            document.getElementById("detailPhone").textContent = data.phone || '-';

            // Format tanggal
            if (data.created && data.created !== '-') {
                try {
                    const date = new Date(data.created);
                    document.getElementById("detailCreated").textContent = date.toLocaleDateString("id-ID", {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric'
                    });
                } catch (e) {
                    document.getElementById("detailCreated").textContent = data.created;
                }
            } else {
                document.getElementById("detailCreated").textContent = '-';
            }

            if (data.lastLogin && data.lastLogin !== '-') {
                try {
                    const date = new Date(data.lastLogin);
                    document.getElementById("detailLastLogin").textContent = date.toLocaleDateString("id-ID", {
                        year: 'numeric',
                        month: 'long',
                        day: 'numeric',
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                } catch (e) {
                    document.getElementById("detailLastLogin").textContent = data.lastLogin;
                }
            } else {
                document.getElementById("detailLastLogin").textContent = '-';
            }

            const roleBadge = document.getElementById("detailRole");
            if (data.role) {
                roleBadge.textContent = data.role.charAt(0).toUpperCase() + data.role.slice(1);
                roleBadge.className = `role-badge role-${data.role}`;
            } else {
                roleBadge.textContent = '-';
                roleBadge.className = 'role-badge';
            }

            const statusBadge = document.getElementById("detailStatus");
            if (data.status) {
                statusBadge.textContent = data.status.charAt(0).toUpperCase() + data.status.slice(1);
                statusBadge.className = `status-badge status-${data.status}`;
            } else {
                statusBadge.textContent = '-';
                statusBadge.className = 'status-badge';
            }

            document.getElementById("detailModal").classList.remove("hidden");
            document.body.style.overflow = "hidden";
        }

        function closeDetailModal() {
            document.getElementById("detailModal").classList.add("hidden");
            document.body.style.overflow = "auto";
            currentUserData = null;
        }

        // Edit modal functions
        function openEditModal(element) {
            const data = getUserDataFromElement(element);
            if (!data) {
                alert('Data user tidak ditemukan');
                return;
            }

            populateEditForm(data);
            document.getElementById("editModal").classList.remove("hidden");
            document.body.style.overflow = "hidden";
        }

        function populateEditForm(data) {
            document.getElementById("editUserId").value = data.id || '';
            document.getElementById("editName").value = data.name || '';
            document.getElementById("editEmail").value = data.email || '';
            document.getElementById("editPhone").value = data.phone || '';
            document.getElementById("editRole").value = data.role || '';

            // Set status radio button
            if (data.status) {
                const statusRadio = document.getElementById("editStatus" + data.status.charAt(0).toUpperCase() + data.status
                    .slice(1));
                if (statusRadio) {
                    statusRadio.checked = true;
                }
            }

            const form = document.getElementById("editUserForm");
            form.action = `/admin/users/${data.id}`;
        }

        function closeEditModal() {
            document.getElementById("editModal").classList.add("hidden");
            document.body.style.overflow = "auto";
            document.getElementById("editUserForm").reset();
        }

        // Delete modal functions
        function openDeleteModal(id) {
            deleteId = id;
            const element = document.querySelector(`[data-id="${id}"]`);
            const nama = element ? element.getAttribute("data-name") : "user ini";
            document.getElementById("deleteNamaUser").textContent = nama;
            document.getElementById("deleteModal").classList.remove("hidden");
            document.body.style.overflow = "hidden";
        }

        function closeDeleteModal() {
            document.getElementById("deleteModal").classList.add("hidden");
            document.body.style.overflow = "auto";
            deleteId = null;
        }

        async function confirmDelete() {
            if (!deleteId) {
                alert('ID user tidak ditemukan');
                return;
            }

            const deleteBtn = document.getElementById("confirmDeleteBtn");
            const deleteText = document.getElementById("deleteText");
            const deleteLoading = document.getElementById("deleteLoading");

            deleteBtn.disabled = true;
            deleteText.textContent = "Menghapus...";
            deleteLoading.classList.remove("hidden");

            try {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");

                if (!csrf) {
                    throw new Error('CSRF token tidak ditemukan');
                }

                const response = await fetch(`/admin/users/${deleteId}`, {
                    method: "DELETE",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrf,
                        "X-Requested-With": "XMLHttpRequest",
                        "Accept": "application/json"
                    }
                });

                const responseData = await response.json();

                if (response.ok && responseData.success) {
                    closeDeleteModal();
                    alert(responseData.message || "Data berhasil dihapus!");
                    setTimeout(() => location.reload(), 500);
                } else {
                    throw new Error(responseData.message || "Gagal menghapus data");
                }

            } catch (error) {
                console.error("Error:", error);
                alert("Terjadi kesalahan: " + error.message);
            } finally {
                deleteBtn.disabled = false;
                deleteText.textContent = "Hapus";
                deleteLoading.classList.add("hidden");
            }
        }

        // Toggle user status function
        async function toggleUserStatus(id) {
            try {
                const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute("content");

                const response = await fetch(`/admin/users/${id}/toggle-status`, {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": csrf,
                        "X-Requested-With": "XMLHttpRequest",
                        "Accept": "application/json"
                    }
                });

                const responseData = await response.json();

                if (response.ok && responseData.success) {
                    alert(responseData.message || "Status user berhasil diubah!");
                    setTimeout(() => location.reload(), 500);
                } else {
                    throw new Error(responseData.message || "Gagal mengubah status");
                }

            } catch (error) {
                console.error("Error:", error);
                alert("Terjadi kesalahan: " + error.message);
            }
        }

        // Form submission handler
        function handleFormSubmit(form, type) {
            const required = type === "add" ? ["name", "email", "role"] : ["editName", "editEmail", "editRole"];

            let isValid = true;

            required.forEach((fieldId) => {
                const field = document.getElementById(fieldId);
                if (!field || !field.value.trim()) {
                    field?.classList.add("border-red-500");
                    isValid = false;
                } else {
                    field.classList.remove("border-red-500");
                }
            });

            // Validate password for add form
            if (type === "add") {
                const password = document.getElementById("password").value;
                const passwordConfirmation = document.getElementById("password_confirmation").value;

                if (!password || password.length < 8) {
                    document.getElementById("password").classList.add("border-red-500");
                    isValid = false;
                    alert("Password minimal 8 karakter");
                }

                if (password !== passwordConfirmation) {
                    document.getElementById("password_confirmation").classList.add("border-red-500");
                    isValid = false;
                    alert("Konfirmasi password tidak cocok");
                }
            }

            const status = form.querySelector('input[name="status"]:checked');
            if (!status) {
                alert("Mohon pilih status user.");
                isValid = false;
            }

            if (!isValid) {
                alert("Mohon lengkapi semua field yang wajib diisi (bertanda *)");
                return;
            }

            const submitBtn = document.getElementById(type === "add" ? "submitBtn" : "editSubmitBtn");
            const submitText = document.getElementById(type === "add" ? "submitText" : "editSubmitText");
            const submitLoading = document.getElementById(type === "add" ? "submitLoading" : "editSubmitLoading");

            submitBtn.disabled = true;
            submitText.textContent = type === "add" ? "Menyimpan..." : "Mengupdate...";
            submitLoading.classList.remove("hidden");

            const formData = new FormData(form);

            fetch(form.action, {
                    method: "POST",
                    body: formData,
                    headers: {
                        "X-Requested-With": "XMLHttpRequest",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]')?.getAttribute("content"),
                    },
                })
                .then((response) => response.json())
                .then((data) => {
                    if (data.success) {
                        if (type === "add") closeAddModal();
                        else closeEditModal();
                        alert(`Data user berhasil ${type === "add" ? "ditambahkan" : "diupdate"}!`);
                        window.location.reload();
                    } else {
                        alert(data.message ||
                            `Terjadi kesalahan saat ${type === "add" ? "menyimpan" : "mengupdate"} data`);
                    }
                })
                .catch((error) => {
                    console.error("Error:", error);
                    alert(`Terjadi kesalahan saat ${type === "add" ? "menyimpan" : "mengupdate"} data`);
                })
                .finally(() => {
                    submitBtn.disabled = false;
                    submitText.textContent = type === "add" ? "Simpan User" : "Update User";
                    submitLoading.classList.add("hidden");
                });
        }

        // Document ready event
        document.addEventListener("DOMContentLoaded", function() {
            // Form submissions
            const addForm = document.getElementById("addUserForm");
            if (addForm) {
                addForm.addEventListener("submit", function(e) {
                    e.preventDefault();
                    handleFormSubmit(this, "add");
                });
            }

            const editForm = document.getElementById("editUserForm");
            if (editForm) {
                editForm.addEventListener("submit", function(e) {
                    e.preventDefault();
                    handleFormSubmit(this, "edit");
                });
            }

            // Delete confirmation
            const confirmDeleteBtn = document.getElementById("confirmDeleteBtn");
            if (confirmDeleteBtn) {
                confirmDeleteBtn.addEventListener("click", confirmDelete);
            }

            // Modal close on backdrop click
            ["addModal", "editModal", "detailModal", "deleteModal"].forEach((modalId) => {
                const modal = document.getElementById(modalId);
                if (modal) {
                    modal.addEventListener("click", function(e) {
                        if (e.target === modal) {
                            const closeFunction = window[
                                `close${modalId.replace("Modal", "").charAt(0).toUpperCase() + modalId.replace("Modal", "").slice(1)}Modal`
                            ];
                            if (closeFunction) closeFunction();
                        }
                    });
                }
            });
        });

        // Escape key to close modals
        document.addEventListener("keydown", function(e) {
            if (e.key === "Escape") {
                ["addModal", "editModal", "detailModal", "deleteModal"].forEach((modalId) => {
                    const modal = document.getElementById(modalId);
                    if (modal && !modal.classList.contains("hidden")) {
                        const closeFunction = window[
                            `close${modalId.replace("Modal", "").charAt(0).toUpperCase() + modalId.replace("Modal", "").slice(1)}Modal`
                        ];
                        if (closeFunction) closeFunction();
                    }
                });
            }
        });
    </script>
@endpush
