<div class="flex flex-col gap-8">
    <div>
        <h1>{{ $content?->translations()->where('language_id', $value['language_id'])?->first()?->name }}</h1>
        <div class="w-[219px] h-[6px] bg-primary"></div>
    </div>
    <div class="grid grid-cols-1 2xl:grid-cols-2 gap-x-8 gap-y-8">
        @foreach($items as $item)
            <div class="w-full flex flex-col md:grid grid-cols-12 gap-4 md:gap-8">
                <div class="col-span-7">
                    <div class="relative w-full h-[294px] md:h-full md:min-h-[221.33px] 4xl:h-[310px]"><img alt="Remitance"
                            loading="lazy" decoding="async" data-nimg="fill"
                            class="rounded-br-[110px] h-full w-full md:rounded-br-[82.81px] 4xl:rounded-br-[110px] object-cover"
                            style="position:absolute;height:100%;width:100%;left:0;top:0;right:0;bottom:0;color:transparent"
                            sizes="100vw"
                            src="{{ $item['image']['default'] }}"
                            >
                    </div>
                </div>
                <div class="col-span-5">
                    <div class="flex flex-col justify-between h-full">
                        <div class="w-full flex flex-col justify-end ">
                            <h3 class="text-primary leading-[39.01px] font-bold uppercase">{{$item['name'] }}</h3>
                            <div>
                                {!! $item['description'] !!}
                            </div>
                        </div>
                        <div class="mt-2">
                            <a href="{{ $item['url'] }}">
                                <button class="p-2 group flex items-center gap-2 border-2 max-w-[209px] min-w-[209px]  rounded-lg border-[#4274BA] bg-white hover:bg-[#051e33] hover:border-[#051e33] transition ease-in duration-500"><span
                                        class="group-hover:translate-x-[140px]  transition ease-in duration-500 "></span>
                                    <span
                                    class="transition ease-in duration-500 text-18 text-primary   group-hover:translate-x-[-10px] font-bold  group-hover:text-white">
                                        {{ \\App\Helper\Helper::_wording('learn_more', $value['language_id']) }}
                                    </span>
                                </button>
                            </a>
                           
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>
