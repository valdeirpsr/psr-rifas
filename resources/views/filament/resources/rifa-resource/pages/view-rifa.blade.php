<x-filament-panels::page>
    <div x-load-js="['https://cdn.tailwindcss.com/3.3.5']"></div>

    <div class="grid grid-cols-1 gap-8 lg:grid-cols-2 -mt-8">
        <div>
            <div class="flex flex-col">
                <div class="relative flex h-[430px] w-full flex-col rounded-[10px] border-[1px] border-gray-200 bg-white bg-clip-border shadow-md shadow-[#F3F3F3] dark:border-0 dark:bg-gray-900 dark:text-white dark:shadow-none">
                    <div class="flex h-fit w-full items-center justify-between rounded-t-2xl bg-white px-4 pb-[20px] pt-4 shadow-2xl shadow-gray-100 dark:bg-gray-900 dark:shadow-none">
                        <h4 class="text-lg font-bold text-navy-700 dark:text-white">
                            Ranking dos Compradores
                        </h4>
                    </div>

                    <div class="w-full overflow-x-scroll px-4 md:overflow-x-hidden">
                        <table role="table" class="w-full min-w-[500px] overflow-x-scroll">
                            <thead>
                                <tr role="row">
                                    <th colspan="1" role="columnheader" title="Toggle SortBy" style="cursor: pointer">
                                        <div class="flex items-center justify-between pb-2 pt-4 text-start uppercase tracking-wide text-gray-600 sm:text-xs lg:text-xs">
                                            Posição
                                        </div>
                                    </th>

                                    <th colspan="1" role="columnheader" title="Toggle SortBy" style="cursor: pointer">
                                        <div class="flex items-center justify-between pb-2 pt-4 text-start uppercase tracking-wide text-gray-600 sm:text-xs lg:text-xs">
                                            Comprador
                                        </div>
                                    </th>

                                    <th colspan="1" role="columnheader" title="Toggle SortBy" style="cursor: pointer">
                                        <div class="flex items-center justify-between pb-2 pt-4 text-start uppercase tracking-wide text-gray-600 sm:text-xs lg:text-xs">
                                            Quantidade
                                        </div>
                                    </th>
                                </tr>
                            </thead>

                            <tbody role="rowgroup" class="px-4">
                                @foreach ($this->rankingBuyers() as $position => $buyer)
                                <tr role="row">
                                    <td class="py-3 text-sm" role="cell">
                                        <div class="flex items-center gap-2">
                                            <p class="text-sm font-medium text-navy-700 dark:text-white">{{ $position + 1 }}</p>
                                        </div>
                                    </td>
                                    <td class="py-3 text-sm" role="cell">
                                        <p class="text-md font-medium text-gray-600 dark:text-white">
                                            {{ $buyer['customer_fullname'] }}
                                        </p>
                                    </td>
                                    <td class="py-3 text-sm" role="cell">
                                        <p class="text-sm font-medium text-navy-700 dark:text-white">
                                            {{ $buyer['total'] }}
                                        </p>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <livewire:app.filament.resources.rifa-resource.widgets.stats-overview :rifa="$this->getRecord()" />
        </div>
    </div>
</x-filament-panels::page>
