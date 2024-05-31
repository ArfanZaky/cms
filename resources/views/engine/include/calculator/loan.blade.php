<div>
    <div class="1.125em font-bold my-8 leading-[24.51px]">{{ \\App\Helper\Helper::_wording('result', $data->lang) }}</div>
    <div class="grid grid-cols-12 gap-4 w-full mb-8">
        <div class="col-span-12 md:col-span-5">
            <button class="bg-secondary text-start w-full text-white rounded-br-[30px] p-4">{{ \\App\Helper\Helper::_wording('eligible_amount', $data->lang) }} {{collect($result)->first()['jumlahAngsuran']}}</button>
        </div>
        <div class="col-span-12 md:col-span-7">
            <button class=" rounded-br-[30px] text-start w-full text-white bg-[#A6A6A6] p-4">{{ \\App\Helper\Helper::_wording('monthly_loan_emi', $data->lang) }} {{collect($result)->first()['pokok']}}</button>
        </div>
        <div class="col-span-12 md:col-span-5">
            <button class=" rounded-br-[30px] text-start w-full text-white bg-[#A6A6A6] p-4">{{ \\App\Helper\Helper::_wording('monthly_interest', $data->lang) }} {{collect($result)->first()['bunga']}}</button>
        </div>
    </div>

    <div class="mt-6 mb-6">
        <div class="flex flex-col gap-2 w-full">
            <div class="table-auto w-full overflow-auto" style="margin-bottom: 25px;">
                <table class="table-auto border-collapse bg-white w-full rounded-tl-[15px]">
                    <thead class="bg-[#142336] text-white rounded-tl-[15px]">
                        <tr class="rounded-tl-[15px]">
                            <th class="p-4 text-start rounded-tl-[15px] sticky left-0 bg-[#142336]">
                                {{ \\App\Helper\Helper::_wording('month', $data->lang) }}
                            </th>
                            <th class="p-4 text-start">
                                {{ \\App\Helper\Helper::_wording('amount', $data->lang) }}
                            </th>
                            <th class="p-4 text-start">
                                {{ \\App\Helper\Helper::_wording('interest', $data->lang) }}
                            </th>
                            <th class="p-4 text-start">
                                {{ \\App\Helper\Helper::_wording('monthly_loan', $data->lang) }}
                            </th>
                            <th class="p-4 text-start">
                                {{ \\App\Helper\Helper::_wording('remaining_loan', $data->lang) }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($result as $i => $item)
                            <tr class="border-b border-[#E7E3E3]" {{ $i % 2 == 0 ? 'style=background-color:#F2F2F2' : 'style=background-color:#FFFFFF' }}>
                                <td class="p-4 left-0">
                                    {{ $i + 1 }}
                                </td>
                                <td class="p-4">{{ $item['pokok'] }}</td>
                                <td class="p-4">{{ $item['bunga'] }}</td>
                                <td class="p-4">{{ $item['jumlahAngsuran'] }}</td>
                                <td class="p-4">{{ $item['sisaPinjaman'] }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
