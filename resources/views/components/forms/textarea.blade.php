@props(['label', 'placeholder', 'model', 'icon' => null, 'svg_icon' => null, 'rows' => 8, 'hint' => null])

<div>

    {{-- Label --}}
    <label for="textarea-input-component-id-{{ $model }}" class="block text-[0.8125rem] font-medium {{ $errors->first($model) ? 'text-red-600 dark:text-red-500' : 'text-gray-700 dark:text-white' }}">{{ htmlspecialchars_decode($label) }}</label>
    
    {{-- Form --}}
    <div class="mt-2 relative">

        {{-- Textarea --}}
        <textarea
            placeholder="{{ htmlspecialchars_decode($placeholder) }}" 
            wire:model.defer="{{ $model }}" 
            rows="{{ $rows }}" 
            id="textarea-input-component-id-{{ $model }}" 
            class="disabled:cursor-not-allowed resize-none focus:!ring-1 block w-full ltr:pr-10 ltr:pl-4 rtl:pl-10 rtl:!pr-4 py-3.5 placeholder:font-normal placeholder:text-[13px] dark:placeholder-zinc-300 text-sm font-medium text-zinc-800 dark:text-white rounded-md dark:bg-transparent {{ $errors->first($model) ? 'focus:!ring-red-600 focus:!border-red-600 border-red-500' : 'focus:!ring-primary-600 focus:!border-primary-600 border-gray-300 dark:border-zinc-500' }} scrollbar-thin scrollbar-thumb-gray-300 scrollbar-track-gray-100 dark:scrollbar-thumb-zinc-900 dark:scrollbar-track-zinc-600" {{ $attributes }}>
            </textarea>

        {{-- Icon --}}
        @if ($icon)
            <div class="absolute inset-y-0 ltr:right-0 ltr:pr-4 rtl:left-0 rtl:pl-4 flex items-center pointer-events-none">
                <i class="mdi mdi-{{ $icon }} {{ $errors->first($model) ? 'text-red-400' : 'text-gray-400' }}"></i>
            </div>
        @elseif ($svg_icon)
            <div class="absolute inset-y-0 ltr:right-0 rtl:left-0 ltr:pr-4 rtl:pl-4 flex items-center pointer-events-none">
                {!! $svg_icon !!}
            </div>
        @endif

    </div>

    {{-- Hint --}}
    @if ($hint)
        <p class="mt-1 text-xs text-gray-400 dark:text-gray-200">{{ $hint }}</p>
    @endif

    {{-- Error --}}
    @error($model)
        <p class="mt-1 text-xs text-red-600 dark:text-red-500">{{ $errors->first($model) }}</p>
    @enderror

</div>