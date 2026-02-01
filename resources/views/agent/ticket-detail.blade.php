@extends('layouts.app')

@section('title', 'Ticket Details')

@section('content')
<div class="container py-4">
    <nav aria-label="breadcrumb" class="mb-4">
        <a href="{{ route('agent.tickets') }}" class="text-decoration-none text-muted fw-medium">
            <i class="fa-solid fa-arrow-left me-2"></i>Back to Tickets list
        </a>
    </nav>

    <div class="row g-4">
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 mb-4 sticky-top" style="top: 20px; z-index: 100;">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 text-primary p-3 rounded-circle me-3">
                            <i class="fa-solid fa-user fs-4"></i>
                        </div>
                        <div>
                            <h4 class="fw-bold mb-0 text-truncate" style="max-width: 200px;">{{ $ticket->customer->name }}</h4>
                            <span class="badge bg-light text-dark border small">{{ $ticket->reference_number }}</span>
                        </div>
                    </div>

                    <hr class="text-muted opacity-25">

                    <div class="mb-3">
                        <label class="text-muted small fw-bold text-uppercase d-block mb-1">Email Address</label>
                        <p class="mb-0 fw-medium text-break">{{ $ticket->customer->email }}</p>
                    </div>
                    <div class="mb-3">
                        <label class="text-muted small fw-bold text-uppercase d-block mb-1">Mobile Number</label>
                        <p class="mb-0 fw-medium">{{ $ticket->customer->mobile ?? 'N/A' }}</p>
                    </div>
                    <div class="mb-4">
                        <label class="text-muted small fw-bold text-uppercase d-block mb-1">Created At</label>
                        <p class="mb-0 fw-medium">{{ $ticket->created_at->format('M d, Y h:i A') }}</p>
                    </div>

                    <div class="bg-light p-3 rounded-3 border">
                        <h6 class="fw-bold mb-2 text-uppercase small text-primary">Problem Description</h6>
                        <p class="text-dark small lh-base mb-0" style="white-space: pre-line;">{{ $ticket->description }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-header bg-white border-0 p-4 pb-0 d-flex justify-content-between align-items-center">
                    <h5 class="fw-bold mb-0">Conversation History</h5>
                    <div class="status-indicator">
                         @php 
                            $statusColor = $ticket->status == 'closed' ? 'success' : 'warning';
                         @endphp
                        <span class="badge bg-{{ $statusColor }}-subtle text-{{ $statusColor }} border border-{{ $statusColor }} text-uppercase">
                            {{ str_replace('_', ' ', $ticket->status) }}
                        </span>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div id="replies-container" class="pe-2" style="max-height: 500px; overflow-y: auto;">
                        @forelse($ticket->replies as $reply)
                            <div class="d-flex mb-4 {{ $reply->agent_id ? 'justify-content-end' : '' }}">
                                <div class="p-3 shadow-sm rounded-4 {{ $reply->agent_id ? 'bg-primary text-white' : 'bg-light text-dark border' }}" style="max-width: 85%;">
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <small class="fw-bold me-3">{{ $reply->agent->name ?? 'Customer' }}</small>
                                        <small class="opacity-75" style="font-size: 0.7rem;">{{ $reply->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-0 small">{{ $reply->message }}</p>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-5 text-muted opacity-50">
                                <i class="fa-solid fa-comments fs-1 mb-3"></i>
                                <p>No replies yet. Start the conversation below.</p>
                            </div>
                        @endforelse
                    </div>

                    <hr class="my-4 opacity-25">

                    <div class="reply-form-wrapper">
                        <form id="reply-form">
                            @csrf
                            <div class="mb-3">
                                <label for="reply-message" class="form-label fw-bold small text-muted">SEND A REPLY</label>
                                <textarea id="reply-message" 
                                          name="message" 
                                          class="form-control border-0 bg-light p-3" 
                                          rows="4" 
                                          placeholder="Type your response here..."
                                          style="border-radius: 1rem; resize: none;"
                                          required></textarea>
                                <div class="text-danger small mt-2" id="error-message"></div>
                            </div>

                            <div class="row align-items-center g-3">
                                <div class="col-sm-6">
                                    <div class="input-group input-group-sm">
                                        <label class="input-group-text bg-info-subtle border-0 text-muted small fw-bold" for="status">UPDATE STATUS:</label>
                                        <select id="status" name="status" class="form-select border-0 bg-light rounded-pill">
                                            <option value="in_progress" {{ $ticket->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="closed" {{ $ticket->status == 'closed' ? 'selected' : '' }}>Closed</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-sm-6 text-sm-end">
                                    <button type="submit" class="btn btn-primary px-4 py-2  shadow-sm fw-bold" id="reply-btn">
                                        <span class="btn-text">Send Reply <i class="fa-solid fa-paper-plane ms-2"></i></span>
                                        <span class="btn-loader d-none">
                                            <span class="spinner-border spinner-border-sm me-2"></span> Sending...
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.toast-notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background: #10b981; 
        color: white;
        padding: 12px 24px;
        border-radius: 50px;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        z-index: 9999;
        transform: translateY(100px);
        opacity: 0;
        transition: all 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        font-weight: 600;
        display: flex;
        align-items: center;
    }

    .toast-notification.show {
        transform: translateY(0);
        opacity: 1;
    }

    
    .toast-notification::before {
        content: "\f00c"; 
        font-family: "Font Awesome 6 Free";
        margin-right: 10px;
        font-weight: 900;
    }

    #replies-container::-webkit-scrollbar { width: 4px; }
    #replies-container::-webkit-scrollbar-track { background: transparent; }
    #replies-container::-webkit-scrollbar-thumb { background: #dee2e6; border-radius: 10px; }
    #replies-container::-webkit-scrollbar-thumb:hover { background: #adb5bd; }
</style>
@endsection
@push('scripts')
<script>
    const repliesContainer = document.getElementById('replies-container');
    repliesContainer.scrollTop = repliesContainer.scrollHeight;

    document.getElementById('reply-form').addEventListener('submit', async function(e) {
        e.preventDefault();

        const replyBtn = document.getElementById('reply-btn');
        const btnText = replyBtn.querySelector('.btn-text');
        const btnLoader = replyBtn.querySelector('.btn-loader');
        const errorEl = document.getElementById('error-message');

        errorEl.textContent = '';
        btnText.classList.add('d-none');
        btnLoader.classList.remove('d-none');
        replyBtn.disabled = true;

        const formData = new FormData(this);

        try {
            const response = await fetch('{{ route("agent.tickets.reply", $ticket->id) }}', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.success) {
              
                const replyDiv = document.createElement('div');
                replyDiv.className = 'd-flex mb-4 justify-content-end';
                replyDiv.innerHTML = `
                    <div class="p-3 shadow-sm rounded-4 bg-primary text-white" style="max-width: 85%;">
                        <div class="d-flex justify-content-between align-items-center mb-1">
                            <small class="fw-bold me-3">${data.reply.author_name}</small>
                            <small class="opacity-75" style="font-size: 0.7rem;">Just now</small>
                        </div>
                        <p class="mb-0 small">${data.reply.message}</p>
                    </div>
                `;
                repliesContainer.appendChild(replyDiv);
                repliesContainer.scrollTo({ top: repliesContainer.scrollHeight, behavior: 'smooth' });

              
                document.getElementById('reply-message').value = '';

                
                showStatusToast('Reply sent successfully!');

            } else {
                errorEl.textContent = data.message || 'An error occurred';
            }
        } catch (error) {
            errorEl.textContent = 'An error occurred. Please try again.';
        } finally {
            btnText.classList.remove('d-none');
            btnLoader.classList.add('d-none');
            replyBtn.disabled = false;
        }
    });

    // Function to create and show the success toast
    function showStatusToast(message) {
       
        let toast = document.getElementById('status-toast');
        if (!toast) {
            toast = document.createElement('div');
            toast.id = 'status-toast';
            toast.className = 'toast-notification';
            document.body.appendChild(toast);
        }

        toast.textContent = message;
        toast.classList.add('show');

        
        setTimeout(() => {
            toast.classList.remove('show');
        }, 3000);
    }
</script>
@endpush