<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    
    public function index()
    {
        $company = Company::first();
        
        if (!$company) {
            return response()->json([
                'message' => 'Company information not found'
            ], 404);
        }

        return response()->json([
            'name' => $company->name,
            'description' => $company->description,
            'email' => $company->email,
            'phone' => $company->phone,
            'address' => $company->address,
            'website_url' => $company->website_url,
            'social_media' => $company->social_media,
        ]);
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate(Company::getValidationRules());
        $company = Company::create($validated);
        
        return response()->json($company, 201);
    }

    
    public function show(Company $company)
    {
        return response()->json($company);
    }

    
    public function update(Request $request, Company $company)
    {
        $validated = $request->validate(Company::getValidationRules());
        $company->update($validated);
        
        return response()->json($company);
    }

    
    public function destroy(Company $company)
    {
        $company->delete();
        
        return response()->json([
            'message' => 'Company deleted successfully'
        ]);
    }
}
