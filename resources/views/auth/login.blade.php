@extends('layouts.app')

@section('title', 'Agent Login')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 80vh;">
        <div class="col-md-5">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
                <div class="card-body p-5">
                    <div class="text-center mb-5">
                        <div class="bg-primary bg-opacity-10 d-inline-block p-3 rounded-circle mb-3">
                            <i class="fa-solid fa-lock text-primary fs-3"></i>
                        </div>
                        <h2 class="fw-bold text-dark">Agent Login</h2>
                        <p class="text-muted">Access the support dashboard</p>
                    </div>

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        
                        <div class="mb-4">
                            <label for="email" class="form-label small fw-bold text-muted text-uppercase">Email Address</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-regular fa-envelope"></i></span>
                                <input type="email" 
                                       id="email" 
                                       name="email" 
                                       class="form-control bg-light border-start-0 @error('email') is-invalid @enderror" 
                                       value="{{ old('email') }}"
                                       placeholder="agent@support.com"
                                       required 
                                       autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label small fw-bold text-muted text-uppercase">Password</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0 text-muted"><i class="fa-solid fa-key"></i></span>
                                <input type="password" 
                                       id="password" 
                                       name="password" 
                                       class="form-control bg-light border-start-0 @error('password') is-invalid @enderror" 
                                       placeholder="••••••••"
                                       required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    

                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-3 fw-bold shadow-sm mb-4">
                            Sign In <i class="fa-solid fa-arrow-right-to-bracket ms-2"></i>
                        </button>
                    </form>

                    <div class="bg-light p-3 rounded-3 text-center border">
                        <p class="small text-muted mb-0">
                            <i class="fa-solid fa-circle-info me-1"></i> Default credentials:
                        </p>
                        <code class="small fw-bold">agent@support.com / agent123</code>
                    </div>
                </div>
            </div>
            
            <div class="text-center mt-4">
                <p class="text-muted small">Not an agent? <a href="{{ route('home') }}" class="text-primary text-decoration-none">Back to Customer Support</a></p>
            </div>
        </div>
    </div>
</div>

<style>
    /* Styling to make inputs feel more premium */
    .form-control:focus {
        box-shadow: none;
        border-color: #0d6efd;
    }
    .input-group-text {
        color: #adb5bd;
    }
</style>
@endsection