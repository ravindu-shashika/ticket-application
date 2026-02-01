<div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
        <thead class="bg-light">
            <tr>
                <th class="ps-4 py-3 border-0 text-uppercase small fw-bold text-muted">Customer</th>
                <th class="py-3 border-0 text-uppercase small fw-bold text-muted">Description</th>
                <th class="py-3 border-0 text-uppercase small fw-bold text-muted">Status</th>
                <th class="py-3 border-0 text-uppercase small fw-bold text-muted">Created</th>
                <th class="pe-4 py-3 border-0 text-end text-uppercase small fw-bold text-muted">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tickets as $ticket)
                <tr class="{{ $ticket->status == 'new' ? 'table-light fw-medium' : '' }}" style="cursor: pointer;" onclick="window.location='{{ route('agent.tickets.show', $ticket->id) }}'">
                    <td class="ps-4 py-3">
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm me-3 bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                {{ strtoupper(substr($ticket->customer->name, 0, 1)) }}
                            </div>
                            <div>
                                <div class="text-dark fw-bold mb-0">{{ $ticket->customer->name }}</div>
                                <div class="text-muted small">{{ $ticket->reference_number }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="py-3">
                        <div class="text-truncate" style="max-width: 300px;">
                            {{ Str::limit($ticket->description, 80) }}
                        </div>
                        <div class="text-muted extra-small" style="font-size: 0.75rem;">{{ $ticket->email }}</div>
                    </td>
                    <td class="py-3">
                        @if($ticket->status == 'new')
                            <span class="badge bg-primary-subtle text-primary rounded-pill px-3">
                                <i class="fa-solid fa-circle me-1 small"></i> NEW
                            </span>
                        @elseif($ticket->status == 'in_progress')
                            <span class="badge bg-warning-subtle text-warning rounded-pill px-3">
                                <i class="fa-solid fa-clock me-1 small"></i> IN PROGRESS
                            </span>
                        @else
                            <span class="badge bg-success-subtle text-success rounded-pill px-3">
                                <i class="fa-solid fa-check me-1 small"></i> CLOSED
                            </span>
                        @endif
                    </td>
                    <td class="py-3 text-muted small">
                        {{ $ticket->created_at->diffForHumans() }}
                    </td>
                    <td class="pe-4 py-3 text-end">
                        <a href="{{ route('agent.tickets.show', $ticket->id) }}" class="btn btn-sm btn-success">
                            View <i class="fa-solid fa-chevron-right ms-1 small"></i>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-5 text-center">
                        <div class="py-4">
                            <i class="fa-regular fa-folder-open fs-1 text-muted mb-3 d-block"></i>
                            <h5 class="fw-bold">No tickets found</h5>
                            <p class="text-muted">{{ $search ? "No results matching '$search'" : 'No tickets have been created yet.' }}</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<style>
    /* Styling for the subtle badge colors */
    .bg-primary-subtle { background-color: #cfe2ff !important; }
    .bg-warning-subtle { background-color: #fff3cd !important; }
    .bg-success-subtle { background-color: #d1e7dd !important; }
    
    .extra-small { font-size: 0.75rem; }
    
    .table-hover tbody tr:hover {
        background-color: #fbfcfe;
    }
</style>