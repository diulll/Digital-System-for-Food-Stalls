@props(['disabled' => false])

<input @disabled($disabled) {{ $attributes->merge(['class' => 'bg-canvas text-ink placeholder:text-muted border-hairline focus:border-link focus:ring-link/30 rounded-[6px] shadow-none']) }}>
