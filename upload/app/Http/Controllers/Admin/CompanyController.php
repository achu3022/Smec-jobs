<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Company;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::with('user')->withTrashed()->orderBy('created_at', 'desc');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('industry', 'like', "%{$search}%")
                  ->orWhere('location', 'like', "%{$search}%");
            });
        }

        $companies = $query->paginate(20);
        return response()->json($companies);
    }

    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'industry' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
        ]);

        $company->update($request->all());

        return response()->json([
            'message' => 'Company updated successfully',
            'company' => $company
        ]);
    }

    public function toggleStatus($id)
    {
        // Toggle suspension using soft deletes
        $company = Company::withTrashed()->findOrFail($id);
        
        if ($company->trashed()) {
            $company->restore();
        } else {
            $company->delete();
            // Also suspend the user account if needed? Just company for now.
        }

        return response()->json(['message' => 'Company status updated']);
    }

    public function destroy($id)
    {
        $company = Company::withTrashed()->findOrFail($id);
        $company->forceDelete();

        return response()->json(['message' => 'Company permanently deleted']);
    }
}
