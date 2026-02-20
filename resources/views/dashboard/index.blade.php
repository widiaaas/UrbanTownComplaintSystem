@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

@php
    $user = (object)[
        'name' => 'Jun',
        'role' => 'admin', // admin | departemen | tenant_relation | penghuni
    ];
@endphp

<div class="space-y-6">

    {{-- HEADER UMUM --}}
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-600">
            Selamat datang, {{ $user->name }}
        </p>
    </div>

    {{-- KONTEN DINAMIS --}}
    @if($user->role === 'admin')
        @include('dashboard.partials.admin')
    @endif

    @if(in_array($user->role, ['departemen','tenant_relation']))
        @include('dashboard.partials.tenant_relation')
    @endif

    @if($user->role === 'penghuni')
        @include('dashboard.partials.penghuni')
    @endif

</div>
@endsection
