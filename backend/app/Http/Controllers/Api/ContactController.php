<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ContactSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    /**
     * Display a listing of contact submissions.
     */
    public function index(Request $request)
    {
        $query = ContactSubmission::query();

        // Filter by status
        if ($request->has('status')) {
            $query->where('status', $request->get('status'));
        }

        // Pagination
        $perPage = $request->get('per_page', 15);
        
        // Validate and sanitize per_page parameter
        if (!is_numeric($perPage) || $perPage < 1) {
            $perPage = 15; // Default
        } elseif ($perPage > 100) {
            $perPage = 100; // Maximum limit
        }
        
        $submissions = $query->orderBy('created_at', 'desc')->paginate($perPage);

        return response()->json($submissions);
    }

    /**
     * Store a newly created contact submission.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|max:5000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $submission = ContactSubmission::create([
                'name' => $request->name,
                'email' => $request->email,
                'message' => $request->message,
                'status' => 'new',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            return response()->json([
                'message' => 'تم استلام رسالتك بنجاح! سنتواصل معك قريباً.',
                'data' => $submission
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء إرسال الرسالة. يرجى المحاولة مرة أخرى.',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified contact submission.
     */
    public function show(ContactSubmission $submission)
    {
        return response()->json([
            'data' => $submission
        ]);
    }

    /**
     * Update the specified contact submission.
     */
    public function update(Request $request, ContactSubmission $submission)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|in:new,read,replied,archived',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $submission->update($request->only(['status']));

        return response()->json([
            'message' => 'Contact submission updated successfully',
            'data' => $submission
        ]);
    }

    /**
     * Remove the specified contact submission.
     */
    public function destroy(ContactSubmission $submission)
    {
        $submission->delete();

        return response()->json([
            'message' => 'Contact submission deleted successfully'
        ]);
    }
}
