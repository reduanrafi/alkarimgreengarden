@extends('layouts.app')

@section('title', 'Contact Us - ' . config('app.name'))
@section('meta_description', 'Get in touch with ' . config('app.name') . '. Questions about orders, plants or delivery — we would love to hear from you.')

@php
    $phone = setting('website_phone', '+1 (555) 123-4567');
    $email = setting('website_email', 'hello@greengarden.test');
    $address = setting('website_address', '123 Garden Lane, Portland, OR');
    $whatsapp = setting('whatsapp_number');
    $hours = setting('business_hours', 'Mon-Sat: 9:00 AM - 9:00 PM');
    $map = setting('google_map_embed');
    $whatsappUrl = $whatsapp ? 'https://wa.me/' . preg_replace('/\D+/', '', $whatsapp) : null;
@endphp

@section('content')
<section class="gg-page-hero">
    <div class="gg-container">
        <div class="gg-crumb">
            <a href="{{ route('home') }}">Home</a>
            <span aria-hidden="true">/</span>
            <span>Contact</span>
        </div>
        <span class="eyebrow mt-4 block">We're Here to Help</span>
        <h1>Get in Touch</h1>
        <p>Have a question, feedback, or just want to say hello? We would love to hear from you.</p>
    </div>
</section>

<section class="py-14">
    <div class="gg-container">
        @if (session('success'))
            <div class="gg-alert gg-alert-success max-w-3xl mx-auto mb-8">{{ session('success') }}</div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 items-start">
            <div class="lg:col-span-3">
                <div class="gg-panel">
                    <div class="gg-panel-head">
                        <h2 class="gg-title">Send Us a Message</h2>
                        <p class="gg-sub">Fill in the form and we will reply as soon as we can.</p>
                    </div>
                    <x-contact-form />
                </div>
            </div>

            <div class="lg:col-span-2 space-y-5">
                <div class="gg-contact-card">
                    <div class="gg-contact-ico">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                    </div>
                    <h3>Call Us</h3>
                    <p>{{ $phone }}</p>
                </div>

                <div class="gg-contact-card">
                    <div class="gg-contact-ico">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    </div>
                    <h3>Email Us</h3>
                    <p>{{ $email }}</p>
                </div>

                <div class="gg-contact-card">
                    <div class="gg-contact-ico">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <h3>Visit Us</h3>
                    <p>{{ $address }}</p>
                </div>

                <div class="gg-contact-card">
                    <div class="gg-contact-ico">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3>Opening Hours</h3>
                    <p>{{ $hours }}</p>
                </div>

                @if ($whatsappUrl)
                    <a href="{{ $whatsappUrl }}" target="_blank" rel="noopener" class="gg-btn w-full !bg-[#25D366] hover:!bg-[#1eb955]">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        Chat on WhatsApp
                    </a>
                @endif
            </div>
        </div>

        @if ($map)
            <div class="gg-map-frame mt-10">
                {!! $map !!}
            </div>
        @endif
    </div>
</section>
@endsection
