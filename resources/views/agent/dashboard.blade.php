@extends('layouts.app')

@section('title', 'Agent Dashboard')

@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<div class="container py-4">
    <div class="row mb-4 align-items-center">
        <div class="col-md-8">
            <h1 class="fw-bold text-dark">Welcome back, {{ auth()->user()->name }}!</h1>
            <p class="text-muted mb-0">Here's an overview of your support tickets and performance.</p>
        </div>
        <div class="col-md-4 text-md-end mt-3 mt-md-0">
            <div class="btn-group">
                <a href="{{ route('agent.tickets') }}" class="btn btn-primary px-4 shadow-sm">
                    <i class="bi bi-list-task me-2"></i>View All Tickets
                </a>
                <a href="{{ route('agent.tickets', ['filter' => 'new']) }}" class="btn btn-outline-primary px-4 shadow-sm">
                    <i class="bi bi-plus-circle me-2"></i>New Only
                </a>
            </div>
        </div>
    </div>

    <div class="row g-4 mb-5">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-3">
                            <i class="bi bi-bar-chart-fill fs-4"></i>
                        </div>
                    </div>
                    <h6 class="text-muted fw-semibold text-uppercase small mb-1">Total Tickets</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['total'] }}</h2>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-warning bg-opacity-10 text-warning p-3 rounded-3">
                            <i class="bi bi-envelope-plus-fill fs-4"></i>
                        </div>
                    </div>
                    <h6 class="text-muted fw-semibold text-uppercase small mb-1">New Tickets</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['new'] }}</h2>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-info bg-opacity-10 text-info p-3 rounded-3">
                            <i class="bi bi-hourglass-split fs-4"></i>
                        </div>
                    </div>
                    <h6 class="text-muted fw-semibold text-uppercase small mb-1">In Progress</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['in_progress'] }}</h2>
                </div>
            </div>
        </div>

        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-3">
                        <div class="bg-success bg-opacity-10 text-success p-3 rounded-3">
                            <i class="bi bi-check-circle-fill fs-4"></i>
                        </div>
                    </div>
                    <h6 class="text-muted fw-semibold text-uppercase small mb-1">Closed</h6>
                    <h2 class="fw-bold mb-0">{{ $stats['closed'] }}</h2>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 .5rem 1rem rgba(0,0,0,.15)!important;
    }
</style>
@endsection