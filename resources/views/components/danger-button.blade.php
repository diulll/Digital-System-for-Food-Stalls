<button {{ $attributes->merge(['type' => 'submit', 'class' => 'inline-flex items-center justify-center px-6 py-3 bg-signature-coral text-on-primary rounded-air text-[16px] font-medium leading-6 hover:bg-danger-border focus:outline-none focus-visible:ring-2 focus-visible:ring-signature-coral/40 focus-visible:ring-offset-2 focus-visible:ring-offset-canvas transition ease-in-out duration-150']) }}>
    {{ $slot }}
</button>
