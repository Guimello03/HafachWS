<div>
    <nav class="flex mb-4 text-gray-500" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            @foreach ($items as $item)
                {!! \App\Helpers\BreadcrumbHelper::renderItem($item, $loop->last) !!}
            @endforeach
        </ol>
    </nav>
</div>