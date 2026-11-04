@props(['label', 'placeholder' => null, 'model', 'type' => 'text', 'icon' => null, 'svg_icon' => null, 'suffix' => false, 'hint' => null])

<div>

    {{-- Label --}}
    <label for="text-input-component-id-{{ $model }}" class="block text-[0.8125rem] font-medium tracking-wide {{ $errors->first($model) ? 'text-red-600 dark:text-red-500' : 'text-gray-700 dark:text-white' }}">{{ htmlspecialchars_decode($label) }}</label>
    
    {{-- Form --}}
    <div class="mt-2 relative">

        {{-- Input --}}
        <input 
            type="{{ $type }}" 
            @if ($placeholder) placeholder="{{ htmlspecialchars_decode($placeholder) }}" @endif
            wire:model.defer="{{ $model }}" 
            id="text-input-component-id-{{ $model }}" 
            {{ $type === 'password' ? 'readonly' : '' }} 
            onfocus="{{ $type === 'password' ? "this.removeAttribute('readonly');" : "" }}" 
            class="disabled:cursor-not-allowed focus:!ring-1 block w-full ltr:pr-10 ltr:pl-4 rtl:pl-10 rtl:!pr-4 py-3.5 placeholder:font-normal placeholder:text-[13px] dark:placeholder-zinc-300 text-sm font-medium text-zinc-800 dark:text-white rounded-md dark:bg-transparent {{ $errors->first($model) ? 'focus:!ring-red-600 focus:!border-red-600 border-red-500' : 'focus:!ring-primary-600 focus:!border-primary-600 border-gray-300 dark:border-zinc-500' }}" 
            {{ $attributes }} />

        @if ($suffix)
            {{-- Suffix --}}
            <div class="absolute inset-y-0 ltr:right-0 rtl:left-0 ltr:pr-3 rtl:pl-3 flex items-center pointer-events-none text-xs font-bold tracking-wider">
                <span class="{{ $errors->first($model) ? 'text-red-400' : 'text-gray-400' }}">{{ $suffix }}</span>
            </div>
        @elseif ($icon)
            {{-- Icon --}}
            <div class="absolute inset-y-0 ltr:right-0 rtl:left-0 ltr:pr-4 rtl:pl-4 flex items-center pointer-events-none">
                <i class="mdi mdi-{{ $icon }} {{ $errors->first($model) ? 'text-red-400' : 'text-gray-400' }}"></i>
            </div>
        @elseif ($svg_icon)
            {{-- Icon --}}
            <div class="absolute inset-y-0 ltr:right-0 rtl:left-0 ltr:pr-4 rtl:pl-4 flex items-center pointer-events-none">
                {!! $svg_icon !!}
            </div>
        @endif

    </div>

    {{-- Hint --}}
    @if ($hint)
        <p class="mt-1 text-xs text-gray-400 dark:text-gray-200">{!! $hint !!}</p>
    @endif

    {{-- Error --}}
    @error($model)
        <p class="mt-1 text-xs text-red-600 dark:text-red-500">{{ $errors->first($model) }}</p>
    @enderror

</div>