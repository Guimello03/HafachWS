<div class= "px-6 pt-4">
    
    <nav class="flex items-center pt-1 mb-4 text-gray-600" aria-label="Breadcrumb hover:text-blue-200">
        <ol class="inline-flex items-center space-x-1 ">
            @foreach ($items as $item)
                {!! \App\Helpers\BreadcrumbHelper::renderItem($item, $loop->last) !!}
            @endforeach
        </ol>
    </nav>
    <div class="my-4 border-b border-gray-300 "></div>

</div>