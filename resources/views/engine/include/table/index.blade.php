<div class="flex flex-col gap-2 w-full">
    <div class="table-auto w-full overflow-auto">
        <table
            class="table-auto border-collapse bg-white w-full rounded-tl-[15px]"
        >
            <thead class="bg-[#142336] text-white rounded-tl-[15px]">
                <tr class="rounded-tl-[15px]">
                    <th class="p-4 text-start rounded-tl-[15px] sticky left-0 bg-[#142336]">
                        {{ $currency }}
                    </th>
                    <th class="p-4 text-start">{{ $buy }}</th>
                    <th class="p-4 text-start rounded-tr-[15px]">{{ $sell }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                    @php
                        ($item->image != 'default.jpg') ? $image = env('APP_URL').$item->image : $image = asset('storage/images/1/countries/'.strtolower($item->currency).'.png');
                    @endphp
                    <tr class="border-b border-[#E7E3E3]" {{ $loop->even ? 'style=background-color:#F2F2F2' : 'style=background-color:#FFFFFF' }}>
                        <td class="p-4 sticky left-0" {{ $loop->even ? 'style=background-color:#F2F2F2' : 'style=background-color:#FFFFFF' }}>
                            <div class="flex items-center gap-2">
                                <span  style="
                                width: 40px;
                                height: 40px;
                                background-image: url({{ $image }});
                                background-repeat: no-repeat;
                                display: block;"></span>
                                <p>
                                    {{ $item->currency }}

                                </p>
                            </div>
                          
                        </td>
                        <td class="p-4">{{ number_format($item->bn_buy, 2, ',', '.') }}</td>
                        <td class="p-4">{{  number_format($item->bn_sell, 2, ',', '.') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
