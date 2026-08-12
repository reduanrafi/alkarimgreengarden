@extends('layouts.admin')
@section('title', 'Settings')
@section('content')
<x-admin::breadcrumb :items="[['label' => 'Settings']]" />
<div class="flex items-center justify-between mb-6">
    <div><h2 class="text-xl font-bold text-white">Site Settings</h2><p class="text-sm text-white/40 mt-0.5">Manage site-wide content and configuration</p></div>
</div>

<div x-data="{ tab: 'general' }">
    <div class="flex flex-wrap gap-2 mb-6">
        @php
            $tabs = [
                'general' => 'General',
                'contact' => 'Contact & Map',
                'social' => 'Social Links',
                'about' => 'About Page',
                'seo' => 'SEO & Newsletter',
            ];
        @endphp
        @foreach ($tabs as $key => $label)
            <button type="button" @click="tab = '{{ $key }}'"
                class="px-4 py-2 text-sm font-medium rounded-lg transition"
                :class="tab === '{{ $key }}' ? 'bg-emerald-500/15 text-emerald-400' : 'bg-gray-900/50 text-white/40 hover:text-white/70'">
                {{ $label }}
            </button>
        @endforeach
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data" class="max-w-3xl">
        @csrf

        {{-- General --}}
        <div x-show="tab === 'general'" x-cloak class="glass-card p-6 space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-white/60 mb-1">Website Name</label>
                    <input type="text" name="website_name" value="{{ old('website_name', $settings['website_name'] ?? config('app.name')) }}" class="input-glass"></div>
                <div><label class="block text-sm font-medium text-white/60 mb-1">Tagline</label>
                    <input type="text" name="website_tagline" value="{{ old('website_tagline', $settings['website_tagline'] ?? '') }}" class="input-glass" placeholder="Plants, planters and garden essentials"></div>
            </div>
            <div><label class="block text-sm font-medium text-white/60 mb-1">Footer About Text</label>
                <textarea name="footer_text" rows="3" class="input-glass">{{ old('footer_text', $settings['footer_text'] ?? '') }}</textarea></div>
            <div><label class="block text-sm font-medium text-white/60 mb-1">Copyright Text</label>
                <input type="text" name="copyright_text" value="{{ old('copyright_text', $settings['copyright_text'] ?? '') }}" class="input-glass" placeholder="&copy; 2026 Green Garden. All rights reserved."></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-white/60 mb-1">Website Logo</label>
                    @if (!empty($settings['logo']))<div class="mb-2"><img src="{{ asset('storage/' . $settings['logo']) }}" class="h-12 rounded-lg bg-white/5 p-1"></div>@endif
                    <input type="file" name="logo" accept="image/*" class="w-full text-sm text-white/50 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-emerald-500/10 file:text-emerald-400">
                </div>
                <div>
                    <label class="block text-sm font-medium text-white/60 mb-1">Favicon</label>
                    @if (!empty($settings['favicon']))<div class="mb-2"><img src="{{ asset('storage/' . $settings['favicon']) }}" class="h-10 rounded-lg bg-white/5 p-1"></div>@endif
                    <input type="file" name="favicon" accept="image/*" class="w-full text-sm text-white/50 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-emerald-500/10 file:text-emerald-400">
                </div>
            </div>
        </div>

        {{-- Contact --}}
        <div x-show="tab === 'contact'" x-cloak class="glass-card p-6 space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-white/60 mb-1">Email</label>
                    <input type="email" name="website_email" value="{{ old('website_email', $settings['website_email'] ?? '') }}" class="input-glass"></div>
                <div><label class="block text-sm font-medium text-white/60 mb-1">Phone</label>
                    <input type="text" name="website_phone" value="{{ old('website_phone', $settings['website_phone'] ?? '') }}" class="input-glass"></div>
            </div>
            <div><label class="block text-sm font-medium text-white/60 mb-1">Address</label>
                <textarea name="website_address" rows="2" class="input-glass">{{ old('website_address', $settings['website_address'] ?? '') }}</textarea></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-white/60 mb-1">WhatsApp Number</label>
                    <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number', $settings['whatsapp_number'] ?? '') }}" class="input-glass" placeholder="+1 555 123 4567"></div>
                <div><label class="block text-sm font-medium text-white/60 mb-1">Business Hours</label>
                    <input type="text" name="business_hours" value="{{ old('business_hours', $settings['business_hours'] ?? '') }}" class="input-glass" placeholder="Mon-Sat: 9:00 AM - 9:00 PM"></div>
            </div>
            <div>
                <label class="block text-sm font-medium text-white/60 mb-1">Google Map Embed</label>
                <textarea name="google_map_embed" rows="3" class="input-glass font-mono text-xs" placeholder="Paste the full &lt;iframe&gt; embed code from Google Maps">{{ old('google_map_embed', $settings['google_map_embed'] ?? '') }}</textarea>
                <p class="text-xs text-white/35 mt-1">Paste the share &rarr; embed &rarr; copy HTML snippet.</p>
            </div>
        </div>

        {{-- Social --}}
        <div x-show="tab === 'social'" x-cloak class="glass-card p-6 space-y-5">
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-white/60 mb-1">Facebook URL</label>
                    <input type="url" name="facebook_url" value="{{ old('facebook_url', $settings['facebook_url'] ?? '') }}" class="input-glass"></div>
                <div><label class="block text-sm font-medium text-white/60 mb-1">Twitter / X URL</label>
                    <input type="url" name="twitter_url" value="{{ old('twitter_url', $settings['twitter_url'] ?? '') }}" class="input-glass"></div>
                <div><label class="block text-sm font-medium text-white/60 mb-1">Instagram URL</label>
                    <input type="url" name="instagram_url" value="{{ old('instagram_url', $settings['instagram_url'] ?? '') }}" class="input-glass"></div>
                <div><label class="block text-sm font-medium text-white/60 mb-1">YouTube URL</label>
                    <input type="url" name="youtube_url" value="{{ old('youtube_url', $settings['youtube_url'] ?? '') }}" class="input-glass"></div>
                <div><label class="block text-sm font-medium text-white/60 mb-1">LinkedIn URL</label>
                    <input type="url" name="linkedin_url" value="{{ old('linkedin_url', $settings['linkedin_url'] ?? '') }}" class="input-glass"></div>
            </div>
            <p class="text-xs text-white/35">Social links appear in the footer and contact page. Leave blank to hide an icon.</p>
        </div>

        {{-- About --}}
        <div x-show="tab === 'about'" x-cloak class="glass-card p-6 space-y-5">
            <div><label class="block text-sm font-medium text-white/60 mb-1">Cover Image</label>
                @if (!empty($settings['about_cover']))<div class="mb-2"><img src="{{ asset('storage/' . $settings['about_cover']) }}" class="h-24 rounded-lg border border-gray-700/50"></div>@endif
                <input type="file" name="about_cover" accept="image/*" class="w-full text-sm text-white/50 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-emerald-500/10 file:text-emerald-400">
            </div>
            <div><label class="block text-sm font-medium text-white/60 mb-1">Our Story</label>
                <textarea name="about_story" rows="5" class="input-glass">{{ old('about_story', $settings['about_story'] ?? '') }}</textarea>
                <p class="text-xs text-white/35 mt-1">Separate paragraphs with blank lines.</p></div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div><label class="block text-sm font-medium text-white/60 mb-1">Mission</label>
                    <textarea name="about_mission" rows="3" class="input-glass">{{ old('about_mission', $settings['about_mission'] ?? '') }}</textarea></div>
                <div><label class="block text-sm font-medium text-white/60 mb-1">Vision</label>
                    <textarea name="about_vision" rows="3" class="input-glass">{{ old('about_vision', $settings['about_vision'] ?? '') }}</textarea></div>
            </div>
            <div><label class="block text-sm font-medium text-white/60 mb-1">Values</label>
                <textarea name="about_values" rows="4" class="input-glass font-mono text-xs" placeholder="🌿||Quality||We never sell plants we wouldn't keep">{{ old('about_values', $aboutValues) }}</textarea>
                <p class="text-xs text-white/35 mt-1">One per line: <code>emoji||Title||Description</code></p></div>
            <div><label class="block text-sm font-medium text-white/60 mb-1">Stats</label>
                <textarea name="about_stats" rows="3" class="input-glass font-mono text-xs" placeholder="5000+||Happy Customers">{{ old('about_stats', $aboutStats) }}</textarea>
                <p class="text-xs text-white/35 mt-1">One per line: <code>value||Label</code></p></div>
            <div><label class="block text-sm font-medium text-white/60 mb-1">Achievements</label>
                <textarea name="about_achievements" rows="4" class="input-glass font-mono text-xs" placeholder="🏆||Best Garden Shop 2025||Awarded by the Green Guild">{{ old('about_achievements', $aboutAchievements) }}</textarea>
                <p class="text-xs text-white/35 mt-1">One per line: <code>emoji||Title||Description</code></p></div>
        </div>

        {{-- SEO --}}
        <div x-show="tab === 'seo'" x-cloak class="glass-card p-6 space-y-5">
            <div>
                <label class="block text-sm font-medium text-white/60 mb-1">Default Meta Description</label>
                <textarea name="meta_description" rows="2" class="input-glass">{{ old('meta_description', $settings['meta_description'] ?? '') }}</textarea>
            </div>
            <div>
                <label class="block text-sm font-medium text-white/60 mb-1">Default Meta Keywords</label>
                <input type="text" name="meta_keywords" value="{{ old('meta_keywords', $settings['meta_keywords'] ?? '') }}" class="input-glass" placeholder="plants, planters, gardening">
            </div>
            <div><label class="block text-sm font-medium text-white/60 mb-1">Default OG / Share Image</label>
                @if (!empty($settings['og_image']))<div class="mb-2"><img src="{{ asset('storage/' . $settings['og_image']) }}" class="h-16 rounded-lg border border-gray-700/50"></div>@endif
                <input type="file" name="og_image" accept="image/*" class="w-full text-sm text-white/50 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-medium file:bg-emerald-500/10 file:text-emerald-400">
            </div>
            <div class="grid grid-cols-1 gap-4">
                <div><label class="block text-sm font-medium text-white/60 mb-1">Newsletter Heading</label>
                    <input type="text" name="newsletter_heading" value="{{ old('newsletter_heading', $settings['newsletter_heading'] ?? '') }}" class="input-glass"></div>
                <div><label class="block text-sm font-medium text-white/60 mb-1">Newsletter Subtext</label>
                    <textarea name="newsletter_subtext" rows="2" class="input-glass">{{ old('newsletter_subtext', $settings['newsletter_subtext'] ?? '') }}</textarea></div>
            </div>
        </div>

        <div class="mt-6">
            <button type="submit" class="btn-primary">Save Settings</button>
        </div>
    </form>
</div>
@endsection
