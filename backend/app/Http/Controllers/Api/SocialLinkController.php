<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SocialLinkResource;
use App\Models\SocialLink;
use Illuminate\Http\JsonResponse;

class SocialLinkController extends Controller
{
    public function index(): JsonResponse
    {
        $socialLinks = SocialLink::active()
            ->ordered()
            ->get();

        return response()->json([
            'success' => true,
            'data' => SocialLinkResource::collection($socialLinks),
        ]);
    }
}
