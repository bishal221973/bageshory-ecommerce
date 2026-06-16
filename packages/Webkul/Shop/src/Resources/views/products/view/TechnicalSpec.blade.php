@php
$pdfs = is_string($product->pdf)
? json_decode($product->pdf, true)
: ($product->pdf ?? []);
@endphp

@if (!empty($pdfs))
<div class="container max-1180:px-5 mb-4 flex flex-wrap justify-center gap-3">
    @foreach ($pdfs as $pdf)
    <a
        href="{{ Storage::url($pdf) }}"
        target="_blank"
        class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 text-white rounded-lg">
        <div>
            <div class="flex justify-center w-full">
                <img src="/pdf.png" alt="" style="height: 70px;" class="block mx-auto">
            </div>
            <small class="block text-gray-500">{{$pdf}}</small>
        </div>
    </a>
    @endforeach
</div>
@endif