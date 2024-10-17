<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\BouncedEmailsExport;
use Illuminate\Support\Facades\Hash;

class MailgunWebhookController extends Controller
{
    public function downloadBouncedEmailsAsExcel(Request $request)
    {
        // Validate Mailgun signature for security
        //$this->validateSignature($request);

        // Capture the event data from the Mailgun webhook
        $events = $request->input('event-data', []);

        // Initialize the bounced emails array
        $bouncedEmails = [];

        // Loop through the events and filter bounce events
        foreach ($events as $event) {
            if (isset($event['event']) && ($event['event'] === 'bounce' || $event['event'] === 'failed')) {
                $bouncedEmails[] = [
                    'email' => $event['recipient'],
                    'reason' => $event['reason'] ?? 'Unknown',
                    'created_at' => date('Y-m-d H:i:s', $event['timestamp']) // Format timestamp
                ];
            }
        }

        // If no bounced emails, return an empty Excel file
        // if (empty($bouncedEmails)) {
        //     return response()->json(['message' => 'No bounce events found.'], 200);
        // }

        // Create the Excel file from the bounced emails array
        return Excel::download(new BouncedEmailsExport($bouncedEmails), 'bounced-emails.xlsx');
    }

    // Validate the Mailgun signature
    // protected function validateSignature(Request $request)
    // {
    //     $timestamp = $request->input('signature.timestamp');
    //     $token = $request->input('signature.token');
    //     $signature = $request->input('signature.signature');
    //     $apiKey = "562350bc02c2ed1fbfdb65bb93448661"; // Use your Mailgun API key

    //     $expectedSignature = hash_hmac('sha256', $timestamp . $token, $apiKey);

    //     // Reject if signature validation fails
    //     if ($signature !== $expectedSignature) {
    //         abort(403, 'Invalid signature.');
    //     }
    // }
}
