<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-primary text-on-primary rounded-air text-[16px] font-medium leading-6 shadow-cta hover:bg-primary-active focus:outline-none focus-visible:ring-2 focus-visible:ring-link/40 focus-visible:ring-offset-2 focus-visible:ring-offset-canvas transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
