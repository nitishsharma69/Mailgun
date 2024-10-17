<?php

namespace App\Http\Controllers;
use App\Models\Contact;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\UsersImport;
use App\Mail\UserNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class ContactController extends Controller
{
    /**
     * Handle file uploads and import contacts
     */
    public function uploadContact(Request $request)
    {
        // Validate the uploaded file
        $request->validate([
            'file' => 'required|mimes:xlsx,xls,csv|max:2048'  // Optional: set a max file size (e.g., 2MB)
        ]);

        // Record the time before the import
        $timestampBeforeImport = now();

        // Import contacts from the uploaded Excel file
        Excel::import(new UsersImport, $request->file('file'));

        // Fetch the newly imported contacts based on creation timestamp
        $newContacts = Contact::where('created_at', '>=', $timestampBeforeImport)->get();

        // Log the new contacts
        Log::info('New contacts imported: ', $newContacts->toArray());

        // Queue emails to the newly imported contacts
        foreach ($newContacts as $contact) {
            // Ensure the contact has a valid email before sending
            if (filter_var($contact->email, FILTER_VALIDATE_EMAIL)) {
                Log::info('Sending email to: ' . $contact->email);
                Mail::to($contact->email)->send(new UserNotification($contact));
            } else {
                Log::warning('Invalid email for contact: ' . $contact->id);
            }
        }

        return response()->json(['message' => 'Contacts imported and emails queued successfully!'], 200);
        return Route::view('URI', 'viewName');
    }
}
