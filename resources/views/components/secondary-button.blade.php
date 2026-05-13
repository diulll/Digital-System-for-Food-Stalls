<button {{ $attributes->merge(['type' => 'button', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-canvas border border-hairline rounded-air text-[16px] font-medium text-ink hover:bg-surface-soft focus:outline-none focus-visible:ring-2 focus-visible:ring-link/30 focus-visible:ring-offset-2 focus-visible:ring-offset-canvas disabled:opacity-60 transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
