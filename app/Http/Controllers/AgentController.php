<?php

namespace App\Http\Controllers;

use App\Mail\TicketMail;
use App\Mail\TicketReplyMail;
use App\Models\Agent;
use App\Models\Ticket;
use App\Models\TicketHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class AgentController extends Controller
{
    public function dashboard()
    {
        $stats = [
            'total' => Ticket::count(),
            'new' => Ticket::where('status', 'new')->count(),
            'in_progress' => Ticket::where('status', 'in_progress')->count(),
            'closed' => Ticket::where('status', 'closed')->count(),
        ];

        return view('agent.dashboard', compact('stats'));
    }

    public function tickets(Request $request)
    {
        $search = $request->input('search', '');
        $filter = $request->input('filter', '');

        $tickets = Ticket::query();

        if ($search) {
            $tickets->where(function ($q) use ($search) {
                $q->whereHas('customer', function ($query) use ($search) {
                    $query->where('name', 'like', "%{$search}%");
                });
            });
        }

        if ($filter) {
            $tickets->where('status', $filter);
        }

        $tickets = $tickets
            ->with(['latestReply', 'customer:id,name,email'])
            ->latest()
            ->paginate(15);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'html' => view('agent.partials.ticket-list', [
                    'tickets' => $tickets,
                    'search' => $search,
                    'filter' => $filter
                ])->render(),
                'pagination' => view('agent.partials.pagination', compact('tickets'))->render()
            ]);
        }

        return view('agent.tickets', compact('tickets', 'search', 'filter'));
    }

    public function show($id)
    {
        $ticket = Ticket::with(['replies' => function($query) {
            $query->orderBy('created_at', 'asc');
        }, 'replies.agent','customer'])->findOrFail($id);

        return view('agent.ticket-detail', compact('ticket'));
    }


    public function reply(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'message' => 'required|string|min:5|max:5000',
            'status' => 'nullable|in:new,in_progress,closed'
        ], [
            'message.required' => 'Please enter a reply message',
            'message.min' => 'Reply must be at least 5 characters',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $ticket = Ticket::findOrFail($id);

           
            $reply = TicketHistory::create([
                'ticket_id' => $ticket->id,
                'message' => $request->message,
                'agent_id' => auth()->id(),
                'customer_id' => $ticket->customer_id
            ]);

        
            if ($request->has('status')) {
                $ticket->updateStatus($request->status);
            } else if ($ticket->status === 'new') {
                
                $ticket->updateStatus('in_progress');
            }

            // Send email notification to customer
            try {
                Mail::to($ticket->customer->email)->send(new TicketReplyMail($ticket, $reply));
            } catch (\Exception $e) {
                Log::error('Failed to send reply email: ' . $e->getMessage());
            }

            // Refresh ticket with replies
            $ticket->load(['replies.agent']);

            return response()->json([
                'success' => true,
                'message' => 'Reply sent successfully!',
                'reply' => [
                    'message' => $reply->message,
                    'author_name' => $reply->agent->name,
                    'created_at' => $reply->created_at->format('M d, Y h:i A'),
                    'is_agent' => true,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while sending the reply. Please try again.',
                'error' => $e->getMessage()

            ], 500);
        }
    }

    public function testmail()
    {
        $ticket = Ticket::first();
        Mail::to('srsoft058@gmail.com')->send(new  TicketMail($ticket));

        return 'Test email sent to srsoft058@gmail.com';
    }
    

}
