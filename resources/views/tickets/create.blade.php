@extends('layouts.app')

@section('title', 'Create Support Ticket')

@section('content')
<div class="bg-primary bg-gradient text-white mb-5 rounded-4 shadow-sm">
    <div class="container text-center py-3">
        <h1 class="display-5 fw-bold mb-3">Need Help? <span class="text-warning">We're Here</span></h1>
        <p class="lead opacity-75">Submit a support ticket and our team will get back to you shortly.</p>
    </div>
</div>

<div class="container mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-header bg-white border-0 pt-4 px-4">
                    <h3 class="fw-bold text-dark mb-1">Create Support Ticket</h3>
                    <p class="text-muted small">Fill in the details below and we'll assist you as soon as possible</p>
                </div>
                
                <div class="card-body p-4">
                    <form id="ticket-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="customer_name" class="form-label fw-semibold">Your Name <span class="text-danger">*</span></label>
                                <input type="text" id="customer_name" name="customer_name" class="form-control py-2 shadow-none" placeholder="John Doe" required>
                                <div class="text-danger small mt-1" id="error-customer_name"></div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
                                <input type="email" id="email" name="email" class="form-control py-2 shadow-none" placeholder="john@example.com" required>
                                <div class="text-danger small mt-1" id="error-email"></div>
                            </div>

                            <div class="col-12 mb-3">
                                <label for="mobile" class="form-label fw-semibold">Phone Number <span class="text-danger">*</span></label>
                                <input type="tel" id="mobile" name="mobile" class="form-control py-2 shadow-none" placeholder="0771234567" required>
                                <div class="text-danger small mt-1" id="error-mobile"></div>
                            </div>

                            <div class="col-12 mb-4">
                                <label for="description" class="form-label fw-semibold">Problem Description <span class="text-danger">*</span></label>
                                <textarea id="description" name="description" class="form-control shadow-none" rows="5" placeholder="Please describe your issue in detail..." required></textarea>
                                <div class="text-danger small mt-1" id="error-description"></div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-3 fw-bold shadow-sm" id="submit-btn">
                            <span class="btn-text">Submit Ticket <i class="fa-solid fa-paper-plane ms-2"></i></span>
                            <span class="btn-loader d-none">
                                <span class="spinner-border spinner-border-sm me-2"></span> Processing...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="successModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow rounded-4">
            <div class="modal-body text-center p-5">
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-inline-flex align-items-center justify-content-center mb-4" style="width: 80px; height: 80px;">
                    <i class="fa-solid fa-check fs-1"></i>
                </div>
                <h2 class="fw-bold mb-3">Ticket Created!</h2>
                <p class="text-muted mb-4">Your ticket has been submitted. Please save your reference number to track progress.</p>
                
                <div class="bg-light p-3 rounded-3 mb-4 border border-dashed">
                    <span class="d-block text-muted small text-uppercase fw-bold mb-1">Reference Number</span>
                    <span class="h4 fw-bold text-primary mb-0" id="reference-number"></span>
                </div>

                <div class="d-grid gap-2">
                    <a href="{{ route('tickets.status') }}" class="btn btn-outline-primary rounded-pill py-2">Check Ticket Status</a>
                    <button type="button" class="btn btn-link text-muted text-decoration-none" onclick="location.reload()">Create Another</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('ticket-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const submitBtn = document.getElementById('submit-btn');
    const btnText = submitBtn.querySelector('.btn-text');
    const btnLoader = submitBtn.querySelector('.btn-loader');
    const successModal = new bootstrap.Modal(document.getElementById('successModal'));
    
    // UI Reset
    document.querySelectorAll('.text-danger').forEach(el => el.textContent = '');
    document.querySelectorAll('.form-control').forEach(el => el.classList.remove('is-invalid'));
    btnText.classList.add('d-none');
    btnLoader.classList.remove('d-none');
    submitBtn.disabled = true;
    
    try {
        const response = await fetch('{{ route("tickets.store") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: new FormData(this)
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('reference-number').textContent = data.reference_number;
            successModal.show();
            this.reset();
        } else if (data.errors) {
            Object.keys(data.errors).forEach(key => {
                const errorEl = document.getElementById(`error-${key}`);
                const inputEl = document.getElementById(key);
                if (errorEl) errorEl.textContent = data.errors[key][0];
                if (inputEl) inputEl.classList.add('is-invalid');
            });
        }
    } catch (error) {
        alert('An error occurred. Please try again.');
    } finally {
        btnText.classList.remove('d-none');
        btnLoader.classList.add('d-none');
        submitBtn.disabled = false;
    }
});
</script>
@endpush