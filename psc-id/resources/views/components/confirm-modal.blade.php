{{--
    Reusable confirmation modal.

    Trigger from anywhere with Alpine $dispatch or window.dispatchEvent:

        $dispatch('open-confirm-modal', {
            title:        'Deactivate Staff',           // optional, default shown
            message:      'This cannot be undone.',
            formId:       'my-form-id',                 // form to submit on confirm
            confirmLabel: 'Deactivate',                 // optional, default 'Confirm'
        })
--}}
<div
    x-data="{
        open: false,
        title: 'Are you sure?',
        message: '',
        formId: '',
        confirmLabel: 'Confirm'
    }"
    @open-confirm-modal.window="
        title        = $event.detail.title        ?? 'Are you sure?';
        message      = $event.detail.message      ?? '';
        formId       = $event.detail.formId       ?? '';
        confirmLabel = $event.detail.confirmLabel ?? 'Confirm';
        open = true
    "
    x-show="open"
    x-transition.opacity
    class="fixed inset-0 z-50 flex items-center justify-center p-4"
    style="display:none"
    @keydown.escape.window="open = false"
>
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/60" @click="open = false"></div>

    {{-- Panel --}}
    <div
        class="relative bg-white rounded-xl shadow-2xl w-full max-w-sm p-6 space-y-4"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        @click.stop
    >
        {{-- Warning icon --}}
        <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mx-auto">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                      d="M12 9v2m0 4h.01M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"/>
            </svg>
        </div>

        {{-- Text --}}
        <div class="text-center space-y-1">
            <h3 class="text-base font-semibold text-gray-900" x-text="title"></h3>
            <p class="text-sm text-gray-500" x-text="message"></p>
        </div>

        {{-- Buttons --}}
        <div class="flex gap-3 pt-2">
            <button
                type="button"
                @click="open = false"
                class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 transition"
            >
                Cancel
            </button>
            <button
                type="button"
                @click="open = false; document.getElementById(formId)?.submit()"
                class="flex-1 px-4 py-2 text-sm font-medium text-white bg-red-600 hover:bg-red-700 rounded-lg transition"
                x-text="confirmLabel"
            ></button>
        </div>
    </div>
</div>
