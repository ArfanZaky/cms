
    <div>
    <div class="1.125em font-bold my-8 leading-[24.51px]">{{ \\App\Helper\Helper::_wording('result', $data['lang']) }}</div>
    <div class="grid grid-cols-12 gap-4 w-full mb-8">
        <div class="col-span-12 md:col-span-5">
            <button class="bg-secondary text-start w-full text-white rounded-br-[30px] p-4">{{ \\App\Helper\Helper::_wording('interest_nett', $data['lang']) }} {{ $data['interest_nett'] }}</button>
        </div>
        <div class="col-span-12 md:col-span-7">
            <button class=" rounded-br-[30px] text-start w-full text-white bg-[#A6A6A6] p-4">{{ \\App\Helper\Helper::_wording('total_tax', $data['lang']) }} {{$data['last_tax']}}</button>
        </div>
        @if(!empty($data['last_deposito']))
            <div class="col-span-12 md:col-span-5">
                <button class=" rounded-br-[30px] text-start w-full text-white bg-[#A6A6A6] p-4">{{ \\App\Helper\Helper::_wording('total_deposito', $data['lang']) }} {{$data['last_deposito']}}</button>
            </div>
        @endif
    </div>

    <div class="mt-6 mb-6">
        <div class="flex flex-col gap-2 w-full">
            <div class="table-auto w-full overflow-auto" style="margin-bottom: 25px;">
                <table class="table-auto border-collapse bg-white w-full rounded-tl-[15px]">
                    <thead class="bg-[#142336] text-white rounded-tl-[15px]">
                        <tr class="rounded-tl-[15px]">
                            <th class="p-4 text-start rounded-tl-[15px] left-0 bg-[#142336]">
                                {{ \\App\Helper\Helper::_wording('month', $data['lang']) }}
                            </th>
                            <th class="p-4 text-start">
                                {{ \\App\Helper\Helper::_wording('deposito', $data['lang']) }}
                            </th>
                            <th class="p-4 text-start">
                                {{ \\App\Helper\Helper::_wording('InterestAmount', $data['lang']) }}
                            </th>
                            <th class="p-4 text-start">
                                {{ \\App\Helper\Helper::_wording('taxAfter', $data['lang']) }}
                            </th>
                            @if(!empty($data['last_deposito']))
                                <th class="p-4 text-start">
                                    {{ \\App\Helper\Helper::_wording('depositoAfter', $data['lang']) }}
                                </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($result as $i => $item)
                            <tr class="border-b border-[#E7E3E3]" {{ $i % 2 == 0 ? 'style=background-color:#F2F2F2' : 'style=background-color:#FFFFFF' }}>
                                <td class="p-4 left-0">
                                    {{ $item['no'] }}
                                </td>
                                <td class="p-4">{{ $item['deposito'] }}</td>
                                <td class="p-4">{{ $item['InterestAmount'] }}</td>
                                <td class="p-4">{{ $item['taxAfter'] }}</td>
                                @if(!empty($data['last_deposito']))
                                    <td class="p-4">{{ $item['depositoAfter'] }}</td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
