@extends('layouts.app')

@section('title', 'Manage Tickets')

@section('content')
<div class="container py-4">
    <div class="row align-items-center mb-4">
        <div class="col-md-6">
            <h2 class="fw-bold m-0"><i class="fa-solid fa-ticket me-2 text-primary"></i>Support Tickets</h2>
            <p class="text-muted small mb-0">Manage and respond to customer inquiries</p>
        </div>
        
        <div class="col-md-6 mt-3 mt-md-0">
            <div class="input-group shadow-sm">
                <span class="input-group-text bg-white border-end-0">
                    <i class="fa-solid fa-magnifying-glass text-muted"></i>
                </span>
                <input type="text" 
                       id="search-input" 
                       class="form-control border-start-0 ps-0" 
                       placeholder="Search by customer name..."
                       value="{{ $search }}"
                       autocomplete="off">
                <button class="btn btn-primary px-4" type="button" onclick="searchTickets()">
                    Search
                </button>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-body p-0">
            <div id="tickets-container" class="position-relative">
                <div id="loading-spinner" class="d-none position-absolute top-50 start-50 translate-middle" style="z-index: 10;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
                
                <div id="tickets-content">
                    @include('agent.partials.ticket-list', ['tickets' => $tickets])
                </div>
            </div>
        </div>
    </div>

    <div id="pagination-container" class="mt-4 d-flex justify-content-center">
        @include('agent.partials.pagination', ['tickets' => $tickets])
    </div>
</div>
@endsection

@push('scripts')
<script>
let searchTimeout;
const loadingSpinner = document.getElementById('loading-spinner');
const ticketsContent = document.getElementById('tickets-content');

document.getElementById('search-input').addEventListener('input', function() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        searchTickets();
    }, 500);
});

document.getElementById('search-input').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        searchTickets();
    }
});

async function searchTickets(page = 1) {
    const search = document.getElementById('search-input').value;
    const url = `{{ route('agent.tickets') }}?search=${encodeURIComponent(search)}&page=${page}`;
    
    // Show loading state
    loadingSpinner.classList.remove('d-none');
    ticketsContent.style.opacity = '0.5';
    
    try {
        const response = await fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('tickets-content').innerHTML = data.html;
            document.getElementById('pagination-container').innerHTML = data.pagination;
        }
    } catch (error) {
        console.error('Search error:', error);
    } finally {
        // Hide loading state
        loadingSpinner.classList.add('d-none');
        ticketsContent.style.opacity = '1';
    }
}

// Handle pagination clicks
document.addEventListener('click', function(e) {
    if (e.target.closest('.page-link')) {
        e.preventDefault();
        const link = e.target.closest('.page-link');
        if(link.href) {
            const url = new URL(link.href);
            const page = url.searchParams.get('page');
            searchTickets(page);
        }
    }
});
</script>
@endpush