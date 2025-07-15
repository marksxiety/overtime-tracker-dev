<template>
    <div class="flex flex-col">
        <div class="grid grid-cols-2 gap-4">
            <div class="col-span-1 flex flex-row border-2 justify-between rounded p-4 bg-base-200">
                <span class="text-md font-semibold">Total Overtime Hours</span>
                <span class="text-2xl font-extrabold">45</span>
            </div>
            <div class="col-span-1 flex flex-row border-2 justify-between rounded p-4 bg-base-200">
                <span class="text-md font-semibold">Pending Requests</span>
                <span class="text-2xl font-extrabold">2</span>
            </div>
        </div>
        <div class="flex px-4 py-3 justify-start">
            <button
                class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-10 px-4 bg-[#dce8f3] text-[#121416] text-sm font-bold leading-normal tracking-[0.015em]">
                <span class="truncate">File New Request</span>
            </button>
        </div>
        <h2 class="text-[22px] font-bold leading-tight tracking-[-0.015em] px-4 pb-3 pt-5">Recent
            Requests</h2>
        <div class="px-4 py-3 @container">
            <div class="flex overflow-hidden rounded-xl border border-[#dde1e3] bg-white">
                <table class="flex-1">
                    <thead>
                        <tr class="bg-white">
                            <th
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-120 px-4 py-3 text-left text-[#121416] w-[400px] text-sm font-medium leading-normal">
                                Date</th>
                            <th
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-240 px-4 py-3 text-left text-[#121416] w-[400px] text-sm font-medium leading-normal">
                                Hours</th>
                            <th
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-360 px-4 py-3 text-left text-[#121416] w-[400px] text-sm font-medium leading-normal">
                                Reason</th>
                            <th
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-480 px-4 py-3 text-left text-[#121416] w-60 text-sm font-medium leading-normal">
                                Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="border-t border-t-[#dde1e3]">
                            <td
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-120 h-[72px] px-4 py-2 w-[400px] text-[#6a7681] text-sm font-normal leading-normal">
                                2024-07-15
                            </td>
                            <td
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-240 h-[72px] px-4 py-2 w-[400px] text-[#6a7681] text-sm font-normal leading-normal">
                                8</td>
                            <td
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-360 h-[72px] px-4 py-2 w-[400px] text-[#6a7681] text-sm font-normal leading-normal">
                                Project Deadline
                            </td>
                            <td
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-480 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                                <button
                                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-8 px-4 bg-[#f1f2f4] text-[#121416] text-sm font-medium leading-normal w-full">
                                    <span class="truncate">Approved</span>
                                </button>
                            </td>
                        </tr>
                        <tr class="border-t border-t-[#dde1e3]">
                            <td
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-120 h-[72px] px-4 py-2 w-[400px] text-[#6a7681] text-sm font-normal leading-normal">
                                2024-07-10
                            </td>
                            <td
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-240 h-[72px] px-4 py-2 w-[400px] text-[#6a7681] text-sm font-normal leading-normal">
                                4</td>
                            <td
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-360 h-[72px] px-4 py-2 w-[400px] text-[#6a7681] text-sm font-normal leading-normal">
                                Urgent Task
                            </td>
                            <td
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-480 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                                <button
                                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-8 px-4 bg-[#f1f2f4] text-[#121416] text-sm font-medium leading-normal w-full">
                                    <span class="truncate">Pending</span>
                                </button>
                            </td>
                        </tr>
                        <tr class="border-t border-t-[#dde1e3]">
                            <td
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-120 h-[72px] px-4 py-2 w-[400px] text-[#6a7681] text-sm font-normal leading-normal">
                                2024-07-05
                            </td>
                            <td
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-240 h-[72px] px-4 py-2 w-[400px] text-[#6a7681] text-sm font-normal leading-normal">
                                6</td>
                            <td
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-360 h-[72px] px-4 py-2 w-[400px] text-[#6a7681] text-sm font-normal leading-normal">
                                Client Meeting
                            </td>
                            <td
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-480 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                                <button
                                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-8 px-4 bg-[#f1f2f4] text-[#121416] text-sm font-medium leading-normal w-full">
                                    <span class="truncate">Approved</span>
                                </button>
                            </td>
                        </tr>
                        <tr class="border-t border-t-[#dde1e3]">
                            <td
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-120 h-[72px] px-4 py-2 w-[400px] text-[#6a7681] text-sm font-normal leading-normal">
                                2024-06-28
                            </td>
                            <td
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-240 h-[72px] px-4 py-2 w-[400px] text-[#6a7681] text-sm font-normal leading-normal">
                                5</td>
                            <td
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-360 h-[72px] px-4 py-2 w-[400px] text-[#6a7681] text-sm font-normal leading-normal">
                                System Maintenance
                            </td>
                            <td
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-480 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                                <button
                                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-8 px-4 bg-[#f1f2f4] text-[#121416] text-sm font-medium leading-normal w-full">
                                    <span class="truncate">Rejected</span>
                                </button>
                            </td>
                        </tr>
                        <tr class="border-t border-t-[#dde1e3]">
                            <td
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-120 h-[72px] px-4 py-2 w-[400px] text-[#6a7681] text-sm font-normal leading-normal">
                                2024-06-20
                            </td>
                            <td
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-240 h-[72px] px-4 py-2 w-[400px] text-[#6a7681] text-sm font-normal leading-normal">
                                7</td>
                            <td
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-360 h-[72px] px-4 py-2 w-[400px] text-[#6a7681] text-sm font-normal leading-normal">
                                Training Session
                            </td>
                            <td
                                class="table-3107f0af-777a-4f6e-9fe9-5e763d77d3c8-column-480 h-[72px] px-4 py-2 w-60 text-sm font-normal leading-normal">
                                <button
                                    class="flex min-w-[84px] max-w-[480px] cursor-pointer items-center justify-center overflow-hidden rounded-xl h-8 px-4 bg-[#f1f2f4] text-[#121416] text-sm font-medium leading-normal w-full">
                                    <span class="truncate">Approved</span>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>