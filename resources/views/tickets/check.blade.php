@extends('layouts.app')

@section('title', 'Check Ticket Status')

@section('content')
<div class="bg-primary bg-gradient text-white py-4 mb-5 rounded-4 shadow-sm">
    <div class="container text-center">
        <h2 class="fw-bold mb-2">Check Your <span class="text-warning">Ticket Status</span></h2>
        <p class="opacity-75 mb-0 small">Enter your reference number to track your support request</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4">
                    <form id="status-form">
                        @csrf
                        <div class="label fw-bold text-muted small text-uppercase mb-2">Ticket Reference Number</div>
                        <div class="input-group input-group-lg shadow-sm">
                            <span class="input-group-text bg-white border-end-0 text-primary">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="text" 
                                   id="reference_number" 
                                   name="reference_number" 
                                   class="form-control border-start-0 ps-0 text-uppercase fw-bold" 
                                   placeholder="TKT-XXXXXXXXXX"
                                   required>
                            <button type="submit" class="btn btn-primary px-4" id="check-btn">
                                <span class="btn-text">Track</span>
                                <span class="btn-loader d-none">
                                    <span class="spinner-border spinner-border-sm"></span>
                                </span>
                            </button>
                        </div>
                        <div class="text-danger small mt-2" id="error-message"></div>
                    </form>
                </div>
            </div>

            <div id="ticket-details" style="display: none;">
                <div class="card border-0 shadow rounded-4 overflow-hidden">
                    <div class="card-header bg-white p-4 border-bottom d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase fw-bold d-block mb-1">Reference Number</span>
                            <h4 class="fw-bold text-primary mb-0" id="ticket-ref"></h4>
                        </div>
                        <span id="ticket-status-badge"></span>
                    </div>
                    
                    <div class="card-body p-4">
                        <div class="row mb-4">
                            <div class="col-sm-6 mb-3 mb-sm-0">
                                <label class="text-muted small fw-bold text-uppercase">Customer Name</label>
                                <p class="h6 fw-bold mb-0" id="ticket-customer"></p>
                            </div>
                            <div class="col-sm-6">
                                <label class="text-muted small fw-bold text-uppercase">Date Submitted</label>
                                <p class="h6 fw-bold mb-0" id="ticket-date"></p>
                            </div>
                        </div>

                        <div class="mb-4 bg-light p-3 rounded-3 border">
                            <label class="text-muted small fw-bold text-uppercase d-block mb-2">Your Problem Description</label>
                            <p class="mb-0 text-dark small" id="ticket-description"></p>
                        </div>

                        <hr class="opacity-25 my-4">

                        <h6 class="fw-bold mb-4 text-uppercase small text-primary"><i class="fa-solid fa-comments me-2"></i>Support Replies</h6>
                        
                        <div id="replies-container"></div>

                        <div id="no-replies" class="text-center py-4 bg-light rounded-4 border border-dashed">
                            <i class="fa-solid fa-clock-rotate-left fs-2 text-muted mb-2"></i>
                            <p class="text-muted small mb-0">No replies yet. Our support team will respond shortly.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .border-dashed { border-style: dashed !important; }
    .status-badge {
        padding: 0.5rem 1rem;
        border-radius: 50px;
        font-size: 0.75rem;
        font-weight: 700;
        text-transform: uppercase;
    }
    .status-new { background-color: #cfe2ff; color: #0d6efd; }
    .status-in_progress { background-color: #fff3cd; color: #856404; }
    .status-closed { background-color: #d1e7dd; color: #0f5132; }
</style>
@endsection

@push('scripts')
<script>
document.getElementById('status-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const checkBtn = document.getElementById('check-btn');
    const btnText = checkBtn.querySelector('.btn-text');
    const btnLoader = checkBtn.querySelector('.btn-loader');
    const errorEl = document.getElementById('error-message');
    const ticketDetails = document.getElementById('ticket-details');
    
    errorEl.textContent = '';
    btnText.classList.add('d-none');
    btnLoader.classList.remove('d-none');
    checkBtn.disabled = true;
    
    try {
        const response = await fetch('{{ route("tickets.status") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: new FormData(this)
        });
        
        const data = await response.json();
        
        if (data.success) {
            displayTicket(data.ticket);
            ticketDetails.style.display = 'block';
            ticketDetails.scrollIntoView({ behavior: 'smooth' });
        } else {
            errorEl.textContent = data.message;
            ticketDetails.style.display = 'none';
        }
    } catch (error) {
        errorEl.textContent = 'Invalid reference number or server error.';
    } finally {
        btnText.classList.remove('d-none');
        btnLoader.classList.add('d-none');
        checkBtn.disabled = false;
    }
});

function displayTicket(ticket) {
    document.getElementById('ticket-ref').textContent = ticket.reference_number;
    document.getElementById('ticket-customer').textContent = ticket.customer_name;
    document.getElementById('ticket-description').textContent = ticket.description;
    document.getElementById('ticket-date').textContent = ticket.created_at;
    
    const badge = document.getElementById('ticket-status-badge');
    badge.textContent = ticket.status;
    badge.className = `status-badge status-${ticket.status}`;

    const repliesContainer = document.getElementById('replies-container');
    const noReplies = document.getElementById('no-replies');
    
    if (ticket.replies && ticket.replies.length > 0) {
        noReplies.style.display = 'none';
        repliesContainer.innerHTML = ticket.replies.map(reply => `
            <div class="mb-3 p-3 bg-white border rounded-4 shadow-sm">
                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-bold text-primary small">${reply.author_name}</span>
                    <span class="text-muted extra-small">${reply.created_at}</span>
                </div>
                <p class="mb-0 small text-dark">${reply.message}</p>
            </div>
        `).join('');
    } else {
        repliesContainer.innerHTML = '';
        noReplies.style.display = 'block';
    }
}
</script>
@endpush