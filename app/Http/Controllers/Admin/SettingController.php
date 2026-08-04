<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    protected $imageUpload;

    public function __construct(ImageUploadService $imageUpload)
    {
        $this->imageUpload = $imageUpload;
    }

    public function index()
    {
        $settings = settings();

        return view('admin.settings.index', [
            'settings' => $settings,
            'aboutValues' => $this->listToPipes($this->decodeList($settings['about_values'] ?? null)),
            'aboutStats' => $this->listToPipes($this->decodeList($settings['about_stats'] ?? null)),
            'aboutAchievements' => $this->listToPipes($this->decodeList($settings['about_achievements'] ?? null)),
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            // General
            'website_name' => ['nullable', 'string', 'max:255'],
            'website_tagline' => ['nullable', 'string', 'max:500'],
            'footer_text' => ['nullable', 'string', 'max:2000'],
            'copyright_text' => ['nullable', 'string', 'max:500'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'favicon' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,ico', 'max:1024'],

            // Contact
            'website_email' => ['nullable', 'email', 'max:255'],
            'website_phone' => ['nullable', 'string', 'max:255'],
            'website_address' => ['nullable', 'string', 'max:1000'],
            'whatsapp_number' => ['nullable', 'string', 'max:255'],
            'business_hours' => ['nullable', 'string', 'max:500'],
            'google_map_embed' => ['nullable', 'string', 'max:5000'],

            // Social
            'facebook_url' => ['nullable', 'url', 'max:500'],
            'twitter_url' => ['nullable', 'url', 'max:500'],
            'instagram_url' => ['nullable', 'url', 'max:500'],
            'youtube_url' => ['nullable', 'url', 'max:500'],
            'linkedin_url' => ['nullable', 'url', 'max:500'],

            // About
            'about_cover' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'about_story' => ['nullable', 'string', 'max:10000'],
            'about_mission' => ['nullable', 'string', 'max:2000'],
            'about_vision' => ['nullable', 'string', 'max:2000'],
            'about_values' => ['nullable', 'string'],
            'about_stats' => ['nullable', 'string'],
            'about_achievements' => ['nullable', 'string'],

            // SEO / Newsletter
            'meta_description' => ['nullable', 'string', 'max:2000'],
            'meta_keywords' => ['nullable', 'string', 'max:2000'],
            'og_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'newsletter_heading' => ['nullable', 'string', 'max:255'],
            'newsletter_subtext' => ['nullable', 'string', 'max:1000'],
        ]);

        $images = ['logo', 'favicon', 'about_cover', 'og_image'];
        $lists = ['about_values', 'about_stats', 'about_achievements'];

        foreach ($validated as $key => $value) {
            if (in_array($key, $images, true)) {
                continue;
            }

            if (in_array($key, $lists, true)) {
                Setting::set($key, json_encode($this->pipesToList($value), JSON_UNESCAPED_UNICODE));
                continue;
            }

            Setting::set($key, $value);
        }

        foreach ($images as $key) {
            if ($request->hasFile($key)) {
                $path = $this->imageUpload->upload($request->file($key), Setting::get($key), 'settings');
                Setting::set($key, $path);
            }
        }

        Cache::forget('app_settings');

        return redirect()->route('admin.settings.index')
            ->with('success', 'Settings updated successfully.');
    }

    private function decodeList(?string $json): array
    {
        $decoded = json_decode((string) $json, true);

        return is_array($decoded) ? $decoded : [];
    }

    private function listToPipes(array $list): string
    {
        return collect($list)
            ->map(fn ($item) => implode('||', [
                $item['emoji'] ?? '',
                $item['title'] ?? '',
                $item['text'] ?? '',
            ]))
            ->implode("\n");
    }

    private function pipesToList(?string $text): array
    {
        return collect(preg_split('/\r\n|\r|\n/', trim((string) $text)))
            ->filter(fn ($line) => trim((string) $line) !== '')
            ->map(function ($line) {
                [$emoji, $title, $body] = array_pad(explode('||', (string) $line, 3), 3, '');

                return [
                    'emoji' => trim($emoji),
                    'title' => trim($title),
                    'text' => trim($body),
                ];
            })
            ->filter(fn ($item) => $item['title'] !== '' || $item['text'] !== '')
            ->values()
            ->all();
    }

    public function profile()
    {
        return view('admin.profile.index');
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.auth()->id()],
        ]);

        if ($request->filled('password')) {
            $request->validate([
                'password' => ['required', 'string', 'min:8', 'confirmed'],
            ]);
            $validated['password'] = bcrypt($request->password);
        }

        auth()->user()->update($validated);

        return back()->with('success', 'Profile updated successfully.');
    }
}
