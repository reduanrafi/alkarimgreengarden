@props(['buttonText' => 'Send Message'])

<form action="{{ route('contact.store') }}" method="POST" class="space-y-5">
    @csrf
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
        <div>
            <label for="contact_name" class="gg-label">Name <span class="text-red-500">*</span></label>
            <input id="contact_name" type="text" name="name" value="{{ old('name', auth()->check() ? auth()->user()->name : '') }}"
                   class="gg-input @error('name') !border-red-400 @enderror">
            @error('name') <p class="gg-error">{{ $message }}</p> @enderror
        </div>
        <div>
            <label for="contact_email" class="gg-label">Email <span class="text-red-500">*</span></label>
            <input id="contact_email" type="email" name="email" value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}"
                   class="gg-input @error('email') !border-red-400 @enderror">
            @error('email') <p class="gg-error">{{ $message }}</p> @enderror
        </div>
    </div>
    <div>
        <label for="contact_subject" class="gg-label">Subject</label>
        <input id="contact_subject" type="text" name="subject" value="{{ old('subject') }}"
               placeholder="How can we help?"
               class="gg-input @error('subject') !border-red-400 @enderror">
        @error('subject') <p class="gg-error">{{ $message }}</p> @enderror
    </div>
    <div>
        <label for="contact_message" class="gg-label">Message <span class="text-red-500">*</span></label>
        <textarea id="contact_message" name="message" rows="5"
                  class="gg-input resize-y @error('message') !border-red-400 @enderror">{{ old('message') }}</textarea>
        @error('message') <p class="gg-error">{{ $message }}</p> @enderror
    </div>
    <button type="submit" class="gg-btn !px-7 !py-3.5">{{ $buttonText }}</button>
</form>
