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
    
    /* Toast notification styles */
    .toast-container {
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 9999;
    }
    
    .ticket-toast {
        min-width: 350px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border: none;
        box-shadow: 0 10px 30px rgba(0,0,0,0.3);
    }
    
    .ticket-toast .toast-header {
        background: rgba(255,255,255,0.1);
        color: white;
        border-bottom: 1px solid rgba(255,255,255,0.2);
    }
    
    .ticket-toast .btn-close {
        filter: brightness(0) invert(1);
    }
</style>

<!-- Toast Notification for new tickets -->
<div class="toast-container">
    <div id="ticketToast" class="toast ticket-toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <i class="bi bi-bell-fill me-2"></i>
            <strong class="me-auto">New Ticket Alert</strong>
            <small class="text-white-50">Just now</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            <div id="ticketToastContent"></div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script type="module">
    
    function initializeTicketListener() {
        if (window.Echo) {
            console.log('✅ Echo initialized successfully');
            
            window.Echo.channel('tickets')
                .listen('.ticket.created', (e) => {
                    console.log('🎫 New ticket received:', e);
                    
                    const toastContent = document.getElementById('ticketToastContent');
                    toastContent.innerHTML = `
                        <div class="d-flex align-items-start">
                            <div class="flex-shrink-0">
                                <i class="bi bi-ticket-detailed-fill fs-3 me-3"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="mb-1 fw-bold">${e.ticket.reference_number}</h6>
                                <p class="mb-2 small">${e.ticket.description.substring(0, 100)}${e.ticket.description.length > 100 ? '...' : ''}</p>
                                <div class="small opacity-75">
                                    <i class="bi bi-person me-1"></i> ${e.ticket.customer_name}
                                </div>
                            </div>
                        </div>
                        <div class="mt-3">
                            <a href="/agent/tickets/${e.ticket.id}" class="btn btn-sm btn-light w-100">
                                <i class="bi bi-eye me-1"></i> View Ticket
                            </a>
                        </div>
                    `;
                    
                    // Show toast
                    const toastElement = document.getElementById('ticketToast');
                    const toast = new bootstrap.Toast(toastElement, {
                        autohide: false
                    });
                    toast.show();
                    
                    // Update stats (increment new tickets count)
                    const newTicketsCard = document.querySelector('.col-sm-6.col-xl-3:nth-child(2) h2');
                    if (newTicketsCard) {
                        const currentCount = parseInt(newTicketsCard.textContent);
                        newTicketsCard.textContent = currentCount + 1;
                    }
                    
                    // Update total tickets count
                    const totalTicketsCard = document.querySelector('.col-sm-6.col-xl-3:nth-child(1) h2');
                    if (totalTicketsCard) {
                        const currentCount = parseInt(totalTicketsCard.textContent);
                        totalTicketsCard.textContent = currentCount + 1;
                    }
                });
        } else {
            console.warn(' Echo not ready yet, retrying...');
            setTimeout(initializeTicketListener, 100);
        }
    }
    
    
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initializeTicketListener);
    } else {
        initializeTicketListener();
    }
</script>
@endpush
