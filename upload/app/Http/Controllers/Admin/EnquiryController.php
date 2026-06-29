<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CourseEnquiry;

class EnquiryController extends Controller
{
    public function index(Request $request)
    {
        $query = CourseEnquiry::with(['course', 'user'])->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
        
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $enquiries = $query->paginate(20);
        return response()->json($enquiries);
    }

    public function updateStatus(Request $request, $id)
    {
        $enquiry = CourseEnquiry::findOrFail($id);

        $request->validate([
            'status' => 'required|in:new,contacted,closed'
        ]);

        $enquiry->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Enquiry status updated successfully',
            'enquiry' => $enquiry
        ]);
    }

    public function destroy($id)
    {
        $enquiry = CourseEnquiry::findOrFail($id);
        $enquiry->delete();

        return response()->json(['message' => 'Enquiry deleted successfully']);
    }
}
