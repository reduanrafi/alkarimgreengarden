@extends('layouts.admin')
@section('title', 'FAQs')
@section('content')
<x-admin::breadcrumb :items="[['label' => 'FAQs']]" />
<div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
    <div><h2 class="text-xl font-bold text-white">FAQs</h2><p class="text-sm text-white/40 mt-0.5">Manage frequently asked questions</p></div>
    <a href="{{ route('admin.faqs.create') }}" class="btn-primary"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>Add FAQ</a>
</div>
<x-admin::admin-table :headers="['Question', 'Category', 'Order', 'Status']">
    @forelse($faqs as $faq)
        <tr>
            <td class="max-w-md">
                <span class="font-semibold text-white/90 line-clamp-2">{{ $faq->question }}</span>
                <span class="block text-xs text-white/35 mt-0.5">{{ Str::limit(strip_tags($faq->answer), 80) }}</span>
            </td>
            <td>
                @if ($faq->category)
                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-medium bg-blue-500/10 text-blue-400">{{ $faq->category }}</span>
                @else
                    <span class="text-white/30">—</span>
                @endif
            </td>
            <td class="text-white/50">{{ $faq->display_order }}</td>
            <td>
                <form action="{{ route('admin.faqs.toggle', $faq) }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center gap-2">
                        <span class="relative inline-flex h-5 w-9 items-center rounded-full transition {{ $faq->status ? 'bg-emerald-500/80' : 'bg-gray-600' }}">
                            <span class="inline-block h-3.5 w-3.5 transform rounded-full bg-white transition {{ $faq->status ? 'translate-x-5' : 'translate-x-1' }}"></span>
                        </span>
                        <span class="text-xs {{ $faq->status ? 'text-emerald-400' : 'text-gray-400' }}">{{ $faq->status ? 'Published' : 'Draft' }}</span>
                    </button>
                </form>
            </td>
            <td class="text-right">
                <div class="flex items-center justify-end gap-2">
                    <a href="{{ route('admin.faqs.edit', $faq) }}" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-emerald-500/10 text-emerald-400 hover:bg-emerald-500/20 transition">Edit</a>
                    <button onclick="openModal('deleteFaq-{{ $faq->id }}')" class="px-3 py-1.5 text-xs font-medium rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500/20 transition">Delete</button>
                    <x-admin::modal :id="'deleteFaq-' . $faq->id" title="Delete FAQ?" action="{{ route('admin.faqs.destroy', $faq) }}">Delete this FAQ permanently?</x-admin::modal>
                </div>
            </td>
        </tr>
    @empty
        <tr><td colspan="5" class="px-4 py-10 text-center text-white/30">No FAQs found.</td></tr>
    @endforelse
</x-admin::admin-table>
<x-admin::pagination :paginator="$faqs" />
@endsection
