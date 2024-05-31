<div class="grid-cols-1 md:grid-cols-1 grid w-full  text-white mt-8">
    <div class="bg-[#233757] p-8 2xl:p-16 rounded-br-[140px] flex flex-col gap-16">
        <div class="flex gap-4 justify-between">
            <h4 class="font-bold leading-[21.94px] sm:leading-[29.26px] uppercase">{{ $content?->translations()->where('language_id', $value['language_id'])?->first()?->name }}</h4>
        </div>
        <div class="flex flex-col gap-8">
            @foreach($items as $item)
                <div class="flex  gap-4">
                    <a href="{{ $item['url'] }}">
                        <div>{{ $item['name'] }}</div>
                        @if(!empty($item['sub_name']))
                            <div class="p-1 h-fit text-[11.56px] blink w-fit leading-[15.74px] bg-primary rounded-br-[11px]">{{$item['sub_name']}}
                            </div>
                        @endif
                    </a>
                </div>
        </div>
    </div>
</div>
