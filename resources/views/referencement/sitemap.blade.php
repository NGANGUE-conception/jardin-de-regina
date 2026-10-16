{!! '<'.'?xml version="1.0" encoding="UTF-8"?'.'>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9" xmlns:xhtml="http://www.w3.org/1999/xhtml">
@foreach ($pages as $page)
    <url>
        <loc>{{ $page['adresse'] }}</loc>
@foreach ($page['alternatives'] as $langue => $adresse)
        <xhtml:link rel="alternate" hreflang="{{ $langue }}" href="{{ $adresse }}"/>
@endforeach
        <xhtml:link rel="alternate" hreflang="x-default" href="{{ $page['alternatives']['fr'] }}"/>
@if ($page['modifiee_le'])
        <lastmod>{{ $page['modifiee_le'] }}</lastmod>
@endif
        <changefreq>{{ $page['frequence'] }}</changefreq>
        <priority>{{ $page['priorite'] }}</priority>
    </url>
@endforeach
</urlset>
