<?php

namespace App\Http\Controllers;

use App\Events\TicketOpen;
use App\Mail\TicketMail;
use App\Models\Customer;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class TicketController extends Controller
{
     public function index()
    {
        return view('tickets.create');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'customer_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'mobile' => 'required|string|max:10|min:10',
            'description' => 'required|string|min:10|max:5000',
        ], [
            'customer_name.required' => 'Please enter your name',
            'email.required' => 'Please enter your email address',
            'email.email' => 'Please enter a valid email address',
            'mobile.required' => 'Please enter your phone number',
            'description.required' => 'Please describe your problem',
            'description.min' => 'Description must be at least 10 characters',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        try {

          DB::beginTransaction();
            //add guest to customer tbl
            $customer = Customer::create([
                'name'=> $request->customer_name,
                'email' => $request->email,
                'mobile' => $request->mobile      
            ]); 
            
            // Create the ticket
            $ticket = Ticket::create([
                'description' => $request->description,
                'customer_id'=> $customer->id,

            ]);

            // Send acknowledgment email
            try {
                // Mail::to($customer->email)->send(new TicketMail($ticket));
                // use queue to send email asynchronously
                    Mail::to($customer->email)->queue(new TicketMail($ticket));
            } catch (\Exception $e) {
                
                Log::error('Failed to send ticket creation email: ' . $e->getMessage());
            }
            //send real-time notification to agents
            broadcast(new TicketOpen($ticket))->toOthers();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Ticket created successfully!',
                'reference_number' => $ticket->reference_number,
                'ticket_id' => $ticket->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating ticket: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while creating the ticket. Please try again.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

   

    public function getStatus(Request $request)
    {
        if ($request->isMethod('get')) {
            return view('tickets.check');
        }
        $validator = Validator::make($request->all(), [
            'reference_number' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Please enter a reference number'
            ], 422);
        }

        $ticket = Ticket::where('reference_number', $request->reference_number)
            ->with(['replies' => function($query) {
                $query->orderBy('created_at', 'asc');
            }, 'replies.agent'])
            ->first();

        if (!$ticket) {
            return response()->json([
                'success' => false,
                'message' => 'Ticket not found. Please check your reference number.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'ticket' => [
                'reference_number' => $ticket->reference_number,
                'customer_name' => $ticket->customer->name,
                'description' => $ticket->description,
                'status' => $ticket->status,
                'status_color' => $ticket->status_color,
                'created_at' => $ticket->created_at->format('M d, Y h:i A'),
                'replies' => $ticket->replies->map(function($reply) {
                    return [
                        'message' => $reply->message,
                        'author_name' => $reply->agent->name,
                        'created_at' => $reply->created_at->format('M d, Y h:i A'),
                    ];
                })
            ]
        ]);
    }

    public function checkStatusPage()
    {

        return view('tickets.check');

    }
}
