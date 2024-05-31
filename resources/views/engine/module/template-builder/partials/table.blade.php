<div class="flex flex-col mt-8">
    <div>
        <h1 class="font-bold leading-[68.26px] text-primary uppercase">{{ $content?->translations()->where('language_id', $value['language_id'])?->first()?->name }}</h1>
        <div class="bg-primary w-[118px] h-[6px]"></div>
    </div>
    <div class="overflow-hidden block">
        <div class="block w-full relative bg-transparent overflow-hidden pb-0 px-0">
            <div role="tabpanel"
                class="w-full h-max text-gray-700 p-4 antialiased font-sans text-base font-light leading-relaxed pb-0 px-0"
                data-value="16" style="opacity: 1; position: relative; z-index: 2;">
                <div>
                    <div>
                        <p class="leading-[24px]">{{ $content?->translations()->where('language_id', $value['language_id'])?->first()?->overview }}
                        </p>
                        <div class="mt-6">
                            <div class="flex flex-col gap-2 w-full">
                                <div class="table-auto w-full overflow-auto">
                                    @php
                                        $thead = $content?->translations()->where('language_id', $value['language_id'])?->first()?->sub_name;
                                        $thead = explode(',', $thead);

                                    @endphp
                                    <table class="table-auto border-collapse bg-white w-full rounded-tl-[15px]">
                                        <thead class="bg-[#142336] text-white rounded-tl-[15px]">
                                            <tr class="rounded-tl-[15px]">
                                                @foreach($thead as $item)
                                                    <th class="p-4 text-start">
                                                        {{ $item }}
                                                    </th>
                                                @endforeach

                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($items as $item)
                                                <tr class="border-b border-[#E7E3E3]" {{ $loop->even ? 'style=background-color:#F2F2F2' : 'style=background-color:##FFFFFF' }}>
                                                    <td class="p-4 ">USD</td>
                                                    <td class="p-4">1</td>
                                                    <td class="p-4">13,005</td>
                                                    <td class="p-4">13,605</td>
                                                    <td class="p-4">12,955</td>
                                                    <td class="p-4">13,680</td>
                                                </tr>
                                            @endforeach
                                            
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8  text-16 leading-[36px] font-bold text-[#333]">Note:</div>
                    <div class="ps-6 ">
                        {{ $content?->translations()->where('language_id', $value['language_id'])?->first()?->description }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
