@props(['n', 'titre'])

<article id="section-{{ $n }}" class="scroll-mt-28 bg-white rounded-3xl p-6 md:p-8 shadow-sm flex flex-col gap-4">
    <div class="flex items-center gap-3">
        <span class="w-10 h-10 rounded-full bg-mint flex items-center justify-center text-primary font-display font-bold shrink-0">{{ $n }}</span>
        <h2 class="font-display text-2xl text-primary-deep">{{ __($titre) }}</h2>
    </div>
    <div class="font-body text-primary-deep/80 leading-relaxed flex flex-col gap-3">
        {{ $slot }}
    </div>
</article>
