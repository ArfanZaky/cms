
<div class="grid grid-cols-1 xl:grid-cols-1 gap-8">
    @foreach($builder as $value)
        @if($value['type'] == 'CUSTOM')
            @php
                $count = count($value['description']);
                if ($count > 1) {
                    $class = 'xl:grid-cols-2';
                }else{
                    $class = 'xl:grid-cols-1';
                }
            @endphp
            <div class="grid grid-cols-1 gap-4 {{$class}}">
                @foreach($value['description'] as $description)
                   
                        {!! description($description) !!}
                @endforeach
             </div>
        @elseif($value['type'] == 'TEMPLATE')
            @php
                // add / 
                $category = \App\Models\WebArticleCategories::where('id', $value['model_id'])->first();

                $items = $category->relation()->with('article.translations')
                ->whereHas('article', function ($q) use ($value) {
                    $q->whereHas('translations', function ($q) use ($value) {
                        $q->where('language_id', $value['language_id']);
                    });
                })
                ->get();

                $items = collect($items)->map(function ($item) use ($value) {
                    $item = $item->article->getResponeses($item->article, $value['language_id']);
                    $item = collect($item)->toArray();
                    return $item;
                })

             @endphp
          
                
            @include('engine.module.template-builder.partials.'.$value['template']['code'])

            
        @endif
    @endforeach
</div>