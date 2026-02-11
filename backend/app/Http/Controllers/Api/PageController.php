<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\PageResource;
use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Page::query();

        if ($request->has('published')) {
            $query->where('is_published', $request->boolean('published'));
        } else {

            $query->where('is_published', true);
        }

        $pages = $query->latest()->get();

        return PageResource::collection($pages);
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'slug' => 'required|string|max:255|unique:pages,slug',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'meta_description' => 'nullable|string|max:255',
            'is_published' => 'boolean',
        ]);

        $page = Page::create($validated);

        return new PageResource($page);
    }

    
    public function show($slug)
    {
        $page = Page::where('slug', $slug)->where('is_published', true)->firstOrFail();

        return new PageResource($page);
    }

    
    public function showById(Page $page)
    {
        return new PageResource($page);
    }

    
    public function update(Request $request, Page $page)
    {
        $validated = $request->validate([
            'slug' => 'sometimes|required|string|max:255|unique:pages,slug,' . $page->id,
            'title' => 'sometimes|required|string|max:255',
            'content' => 'sometimes|required|string',
            'meta_description' => 'nullable|string|max:255',
            'is_published' => 'boolean',
        ]);

        $page->update($validated);

        return new PageResource($page);
    }

    
    public function destroy(Page $page)
    {
        $page->delete();

        return response()->json([
            'message' => 'Page deleted successfully'
        ]);
    }

    
    public function hero()
    {
        $heroPage = Page::where('has_hero', true)
            ->where('is_published', true)
            ->first();

        if (!$heroPage) {
            return response()->json([
                'message' => 'No hero section found'
            ], 404);
        }

        return response()->json([
            'title' => $heroPage->hero_title,
            'subtitle' => $heroPage->hero_subtitle,
            'video_url' => $heroPage->hero_video_url,
            'button_text' => $heroPage->hero_button_text,
            'button_link' => $heroPage->hero_button_link,
        ]);
    }

    
    public function contact()
    {
        $contactPage = Page::where('is_contact_page', true)
            ->where('is_published', true)
            ->first();

        if (!$contactPage) {
            return response()->json([
                'message' => 'No contact information found'
            ], 404);
        }

        return response()->json([
            'phone' => $contactPage->contact_phone,
            'email' => $contactPage->contact_email,
            'address' => $contactPage->contact_address,
            'instagram' => $contactPage->contact_instagram,
            'whatsapp' => $contactPage->contact_whatsapp,
            'tiktok' => $contactPage->contact_tiktok,
            'youtube' => $contactPage->contact_youtube,
            'map_embed' => $contactPage->contact_map_embed,
            'button_text' => $contactPage->contact_button_text,
            'button_link' => $contactPage->contact_button_link,
        ]);
    }
}
