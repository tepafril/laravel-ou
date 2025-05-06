<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center">
                <h2
                    class="font-semibold text-xl text-gray-800 leading-tight mr-10"
                >
                    Unmatched Teams
                </h2>

                <Loading v-if="confirmMatchLoading" />
                <div
                    v-else-if="compare?.game != null && compare?.game7m != null"
                >
                    <span class="bg-yellow-500 text-white px-2 rounded-md">
                        {{ compare?.game?.league?.english_short }}
                    </span>
                    <span class="bg-yellow-500 text-white px-2 rounded-md">
                        {{ compare?.game7m?.league?.name }}
                    </span>
                    <span class="px-4"></span>
                    <span class="bg-red-500 text-white px-2 rounded-md">
                        {{ compare?.game?.home_team?.english }}
                    </span>
                    <span class="bg-red-500 text-white px-2 rounded-md">
                        {{ compare?.game7m?.home_team?.name }}
                    </span>
                    <span class="px-2">VS</span>
                    <span class="bg-blue-500 text-white px-2 rounded-md">
                        {{ compare?.game?.away_team?.english }}
                    </span>
                    <span class="bg-blue-500 text-white px-2 rounded-md">
                        {{ compare?.game7m?.away_team?.name }}
                    </span>
                    <div class="mt-4">
                        <div class="flex justify-center w-full">
                            <span
                                class="cursor-pointer border border-slate-500 text-slate-500 py-2 px-4 rounded-md text-lg hover:bg-slate-500 hover:text-white"
                                @click="compare = { game: null, game7m: null }"
                            >
                                Cancel
                            </span>
                            <span
                                class="cursor-pointer border border-green-500 text-green-500 ml-2 py-2 px-4 rounded-md text-lg hover:bg-green-500 hover:text-white"
                                @click="confirmMatched"
                            >
                                Confirm Matched
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="relative inline-block text-left">
                        <!-- Dropdown button -->
                        <button
                            @click="openDropdown = !openDropdown"
                            class="inline-flex justify-between items-center w-full cursor-pointer px-4 py-2 text-lg font-medium text-gray-700 rounded-md shadow-sm hover:bg-gray-50"
                        >
                            <label tabindex="0" class="btn m-1 cursor-pointer"
                                >From:
                                <span class="text-red-600 mr-4">{{
                                    formatDate(selectedDate.start)
                                }}</span>
                                To:
                                <span class="text-red-600">{{
                                    formatDate(selectedDate.end)
                                }}</span>
                            </label>
                        </button>

                        <!-- Dropdown menu with datepicker -->
                        <div
                            v-if="openDropdown"
                            class="absolute z-10 mt-2 bg-white border border-gray-300 rounded-md shadow-lg max-w-72"
                        >
                            <DatePicker
                                borderless
                                v-model.range="selectedDate"
                                mode="date"
                            />
                            <div class="p-2 flex justify-end">
                                <button
                                    type="button"
                                    class="px-3 py-2 text-sm font-medium text-center text-blue-800 rounded-lg focus:outline-none"
                                    @click="openDropdown = false"
                                >
                                    Close
                                </button>
                                <button
                                    type="button"
                                    class="px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                                    @click="
                                        () => {
                                            openDropdown = false;
                                            filterData();
                                        }
                                    "
                                >
                                    Search
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="text-gray-900">
                        <div
                            v-if="isLoading"
                            class="flex items-center justify-center py-10"
                        >
                            <Loading />
                        </div>
                        <!-- Table -->
                        <div v-else class="grid grid-cols-2">
                            <!-- Lucky Sport -->
                            <div
                                id="cs-container"
                                class="max-h-screen overflow-scroll p-4"
                            >
                                <div
                                    id="sticky-title"
                                    class="font-bold text-white flex items-center justify-center bg-[#3e6fa7] border-[#679898] border-t"
                                >
                                    <div class="w-2/12 text-center">
                                        Kick Off
                                    </div>
                                    <div
                                        class="w-7/12 border-[#679898] border-l"
                                    >
                                        <div
                                            class="h-[36px] flex items-center justify-center border-[#679898] border-b"
                                        >
                                            Home
                                        </div>
                                        <div
                                            class="h-[36px] flex items-center justify-center"
                                        >
                                            Away
                                        </div>
                                    </div>
                                    <div
                                        class="w-1/12 border-[#679898] border-l"
                                    >
                                        <div class="text-center">1:0</div>
                                        <div class="text-center">2:0</div>
                                        <div class="text-center">3:0</div>
                                    </div>
                                    <div
                                        class="w-1/12 border-[#679898] border-l"
                                    >
                                        <div class="text-center">2:1</div>
                                        <div class="text-center">3:1</div>
                                        <div class="text-center">4:0</div>
                                    </div>
                                    <div
                                        class="w-1/12 border-[#679898] border-l"
                                    >
                                        <div class="text-center">3:2</div>
                                        <div class="text-center">4:1</div>
                                        <div class="text-center">4:2</div>
                                    </div>
                                </div>

                                <template
                                    v-for="(batch, i) in matches"
                                    :key="i"
                                >
                                    <div class="relative">
                                        <div
                                            class="cs-box-date text-black font-bold text-center h-[24px]"
                                        >
                                            {{ formatDate(batch.gd) }}
                                        </div>
                                        <template
                                            v-for="(
                                                timeMatch, j
                                            ) in batch.timeMatches"
                                            :key="j"
                                        >
                                            <div
                                                class="cs-box-league text-black font-bold h-[29px] px-2 flex flex-row items-center"
                                            >
                                                <div
                                                    class="w-[7px] h-[22px] bg-[url('/img/rp_bg.png')] m-[0px_0px_0px_18px]"
                                                    :style="`background-color:#`"
                                                ></div>
                                                <div class="ml-[8px]">
                                                    {{ timeMatch.league }} ({{
                                                        timeMatch.league_short
                                                    }})
                                                </div>
                                            </div>
                                            <div
                                                v-for="match in timeMatch.matches"
                                                :key="match.id"
                                            >
                                                <table
                                                    class="w-full border-collapse cs-team-table"
                                                    cellpadding="0"
                                                    cellspacing="0"
                                                >
                                                    <tbody>
                                                        <tr
                                                            id="r403976"
                                                            class="bg-[#f0f3f6] text-black text-center cursor-pointer hover:bg-purple-500 hover:bg-opacity-30"
                                                            :class="[
                                                                compare?.game
                                                                    ?.id ==
                                                                match?.id
                                                                    ? 'bg-purple-500 bg-opacity-30'
                                                                    : '',
                                                            ]"
                                                            @click="
                                                                searchThisItem(
                                                                    match
                                                                        .home_team
                                                                        .english,
                                                                    match
                                                                        .home_team
                                                                        .id,
                                                                    match
                                                                )
                                                            "
                                                        >
                                                            <td
                                                                class="w-2/12 bg-[#f0f3f6]"
                                                                rowspan="2"
                                                            >
                                                                {{
                                                                    extractTime(
                                                                        match.gt
                                                                    )
                                                                }}
                                                            </td>
                                                            <td class="w-7/12">
                                                                {{
                                                                    match
                                                                        .home_team
                                                                        .english
                                                                }}
                                                            </td>
                                                            <td
                                                                class="w-1/12 bg-[#ccd7f5]"
                                                            >
                                                                <span>{{
                                                                    match.home_o1 *
                                                                    1
                                                                }}</span>
                                                            </td>
                                                            <td
                                                                class="w-1/12 bg-[#ccd7f5]"
                                                            >
                                                                <span>{{
                                                                    match.home_o2 *
                                                                    1
                                                                }}</span>
                                                            </td>
                                                            <td
                                                                class="w-1/12 bg-[#ccd7f5]"
                                                            >
                                                                <span>{{
                                                                    match.home_o3 *
                                                                    1
                                                                }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr
                                                            class="bg-[#f0f3f6] text-black text-center cursor-pointer hover:bg-purple-500 hover:bg-opacity-30"
                                                            :class="[
                                                                compare?.game
                                                                    ?.id ==
                                                                match?.id
                                                                    ? 'bg-purple-500 bg-opacity-30'
                                                                    : '',
                                                            ]"
                                                            @click="
                                                                searchThisItem(
                                                                    match
                                                                        .away_team
                                                                        .english,
                                                                    match
                                                                        .away_team
                                                                        .id,
                                                                    match
                                                                )
                                                            "
                                                        >
                                                            <td class="w-7/12">
                                                                {{
                                                                    match
                                                                        .away_team
                                                                        .english
                                                                }}
                                                            </td>
                                                            <td
                                                                class="m7_score1 bg-[#ccd7f5]"
                                                            >
                                                                <span>{{
                                                                    match.away_o1 *
                                                                    1
                                                                }}</span>
                                                            </td>
                                                            <td
                                                                class="m7_score2 bg-[#ccd7f5]"
                                                            >
                                                                <span>{{
                                                                    match.away_o2 *
                                                                    1
                                                                }}</span>
                                                            </td>
                                                            <td
                                                                class="m7_score3 bg-[#ccd7f5]"
                                                            >
                                                                <span>{{
                                                                    match.away_o3 *
                                                                    1
                                                                }}</span>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                            </div>

                            <!-- 7m Sport -->
                            <div
                                id="7m-container"
                                class="pt-4 max-h-screen overflow-scroll"
                            >
                                <div
                                    id="sticky-title"
                                    class="font-bold text-white flex items-center justify-center bg-[#3e6fa7] border-t"
                                >
                                    <table
                                        cellpadding="2"
                                        cellspacing="0"
                                        border="1"
                                        bordercolor="#0066CC"
                                        class="list_data w-full border-collapse border-spacing-0 bg-[#ddd]"
                                        id="tbodds"
                                    >
                                        <tbody class="border-2 border-[#06C]">
                                            <tr class="tb_head">
                                                <td
                                                    colspan="2"
                                                    rowspan="2"
                                                    class="th_lea w-2/12 text-center border border-r-[#06C] bg-[#1975D1]"
                                                >
                                                    Matches
                                                </td>
                                                <td
                                                    rowspan="2"
                                                    class="th_team w-2/12 text-center border border-r-[#06C] bg-[#1975D1]"
                                                >
                                                    Against
                                                </td>
                                                <td
                                                    colspan="3"
                                                    class="th_ft w-4/12 text-center border border-[#06C] bg-[#1975D1]"
                                                >
                                                    Full
                                                </td>
                                                <td
                                                    colspan="3"
                                                    class="th_ht w-4/12 text-center border border-[#06C] bg-[#1975D1]"
                                                >
                                                    Half
                                                </td>
                                            </tr>
                                            <tr class="tb_head">
                                                <td
                                                    class="th_o1 text-center border border-r-[#06C] bg-[#1975D1]"
                                                >
                                                    1X2
                                                </td>
                                                <td
                                                    class="th_o2 text-center border border-r-[#06C] bg-[#1975D1]"
                                                >
                                                    Handicap
                                                </td>
                                                <td
                                                    class="th_o3 text-center border border-r-[#06C] bg-[#1975D1]"
                                                >
                                                    Over Under
                                                </td>
                                                <td
                                                    class="th_o4 text-center border border-r-[#06C] bg-[#1975D1]"
                                                >
                                                    1X2
                                                </td>
                                                <td
                                                    class="th_o5 text-center border border-r-[#06C] bg-[#1975D1]"
                                                >
                                                    Handicap
                                                </td>
                                                <td
                                                    class="th_o6 text-center border border-r-[#06C] bg-[#1975D1]"
                                                >
                                                    Over Under
                                                </td>
                                            </tr>
                                        </tbody>
                                        <template
                                            v-for="batch in filteredMatches7m"
                                            :key="batch.gd"
                                        >
                                            <tbody
                                                class="border-2 border-[#06C] bg-[#eee] text-black text-center"
                                            >
                                                <tr class="">
                                                    <td
                                                        colspan="9"
                                                        class="box-date-7m"
                                                        id="td_date"
                                                    >
                                                        {{
                                                            formatDate(batch.gd)
                                                        }}
                                                    </td>
                                                </tr>
                                            </tbody>
                                            <tbody
                                                class="team-table-7m border-2 border-[#06C] bg-white text-black text-sm font-normal"
                                                v-for="match in batch.matches"
                                                :key="match.id"
                                            >
                                                <tr>
                                                    <td
                                                        rowspan="3"
                                                        colspan="2"
                                                        class="text-center text-white font-bold"
                                                        :style="`background-color: #${
                                                            match?.f1 ??
                                                            'ff9900'
                                                        }`"
                                                    >
                                                        <div class="text-white">
                                                            {{
                                                                match?.league
                                                                    ?.name
                                                            }}
                                                        </div>
                                                        <div class="text-white">
                                                            {{
                                                                extractTime7m(
                                                                    match.gt
                                                                )
                                                            }}
                                                        </div>
                                                    </td>
                                                    <td
                                                        rowspan="2"
                                                        class="text-right hover:bg-purple-300 cursor-pointer"
                                                        :class="[
                                                            compare?.game7m
                                                                ?.id ==
                                                            match?.id
                                                                ? 'bg-purple-200'
                                                                : '',
                                                        ]"
                                                        @click="
                                                            compare.game7m =
                                                                match
                                                        "
                                                    >
                                                        <div>
                                                            {{
                                                                match?.home_team
                                                                    ?.name
                                                            }}
                                                        </div>
                                                        <div>
                                                            {{
                                                                match?.away_team
                                                                    ?.name
                                                            }}
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="text-[#1515d0]"
                                                            >{{
                                                                match?.hda1_0 *
                                                                1
                                                            }}</span
                                                        >
                                                    </td>
                                                    <td>
                                                        <div class="flex">
                                                            <div
                                                                class="w-1/2 font-extrabold"
                                                                :class="[
                                                                    homeHidden(
                                                                        match?.ah1_4
                                                                    ),
                                                                ]"
                                                            >
                                                                {{
                                                                    HANDICAP[
                                                                        match?.ah1_2 *
                                                                            1
                                                                    ] ??
                                                                    match?.ah1_2 *
                                                                        1
                                                                }}
                                                            </div>
                                                            <div
                                                                class="w-1/2 text-right"
                                                            >
                                                                <span
                                                                    class="text-[#1515d0]"
                                                                    >{{
                                                                        match?.ah1_0 *
                                                                        1
                                                                    }}</span
                                                                >
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="flex">
                                                            <div
                                                                class="w-1/2 font-extrabold"
                                                            >
                                                                o{{
                                                                    match?.ou1_2 *
                                                                    1
                                                                }}
                                                            </div>
                                                            <div
                                                                class="w-1/2 text-right"
                                                            >
                                                                <span
                                                                    class="text-[#1515d0]"
                                                                    >{{
                                                                        match?.ou1_0 *
                                                                        1
                                                                    }}</span
                                                                >
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="text-[#1515d0]"
                                                            >{{
                                                                match?.hda2_0 *
                                                                1
                                                            }}</span
                                                        >
                                                    </td>
                                                    <td>
                                                        <div class="flex">
                                                            <div
                                                                class="w-1/2 font-extrabold"
                                                                :class="[
                                                                    homeHidden(
                                                                        match?.ah2_4
                                                                    ),
                                                                ]"
                                                            >
                                                                {{
                                                                    HANDICAP[
                                                                        match?.ah2_2 *
                                                                            1
                                                                    ] ??
                                                                    match?.ah2_2 *
                                                                        1
                                                                }}
                                                            </div>
                                                            <div
                                                                class="w-1/2 text-right"
                                                            >
                                                                <span
                                                                    class="text-[#1515d0]"
                                                                    >{{
                                                                        match?.ah2_0 *
                                                                        1
                                                                    }}</span
                                                                >
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="flex">
                                                            <div
                                                                class="w-1/2 font-extrabold"
                                                            >
                                                                o{{
                                                                    match?.ou2_2 *
                                                                    1
                                                                }}
                                                            </div>
                                                            <div
                                                                class="w-1/2 text-right"
                                                            >
                                                                <span
                                                                    class="text-[#1515d0]"
                                                                    >{{
                                                                        match?.ou2_0 *
                                                                        1
                                                                    }}</span
                                                                >
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-center">
                                                        <span
                                                            class="text-[#1515d0]"
                                                            >{{
                                                                match?.hda1_1 *
                                                                1
                                                            }}</span
                                                        >
                                                    </td>
                                                    <td>
                                                        <div class="flex">
                                                            <div
                                                                class="w-1/2 font-extrabold"
                                                                :class="[
                                                                    awayHidden(
                                                                        match?.ah1_4
                                                                    ),
                                                                ]"
                                                            >
                                                                {{
                                                                    HANDICAP[
                                                                        match?.ah1_2 *
                                                                            1
                                                                    ] ??
                                                                    match?.ah1_2 *
                                                                        1
                                                                }}
                                                            </div>
                                                            <div
                                                                class="w-1/2 text-right"
                                                            >
                                                                <span
                                                                    class="text-[#1515d0]"
                                                                    >{{
                                                                        match?.ah1_1 *
                                                                        1
                                                                    }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="flex">
                                                            <div
                                                                class="w-1/2 font-extrabold"
                                                            ></div>
                                                            <div
                                                                class="w-1/2 text-right"
                                                            >
                                                                <span
                                                                    class="text-[#1515d0]"
                                                                    >{{
                                                                        match?.ou1_1 *
                                                                        1
                                                                    }}</span
                                                                >
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="text-[#1515d0]"
                                                            >{{
                                                                match?.hda2_1 *
                                                                1
                                                            }}</span
                                                        >
                                                    </td>
                                                    <td>
                                                        <div class="flex">
                                                            <div
                                                                class="w-1/2 font-extrabold"
                                                                :class="[
                                                                    awayHidden(
                                                                        match?.ah2_4
                                                                    ),
                                                                ]"
                                                            >
                                                                {{
                                                                    HANDICAP[
                                                                        match?.ah2_2 *
                                                                            1
                                                                    ] ??
                                                                    match?.ah2_2 *
                                                                        1
                                                                }}
                                                            </div>
                                                            <div
                                                                class="w-1/2 text-right"
                                                            >
                                                                <span
                                                                    class="text-[#1515d0]"
                                                                >
                                                                    {{
                                                                        match?.ah2_1 *
                                                                        1
                                                                    }}
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <div class="flex">
                                                            <div
                                                                class="w-1/2 font-extrabold"
                                                            ></div>
                                                            <div
                                                                class="w-1/2 text-right"
                                                            >
                                                                <span
                                                                    class="text-[#1515d0]"
                                                                    >{{
                                                                        match?.ou2_1 *
                                                                        1
                                                                    }}</span
                                                                >
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">
                                                        Draw
                                                    </td>
                                                    <td class="text-center">
                                                        <span
                                                            class="text-[#1515d0]"
                                                            >{{
                                                                match?.hda1_2 *
                                                                1
                                                            }}</span
                                                        >
                                                    </td>
                                                    <td colspan="2"></td>
                                                    <td class="text-center">
                                                        <span
                                                            class="text-[#1515d0]"
                                                            >{{
                                                                match?.hda2_2 *
                                                                1
                                                            }}</span
                                                        >
                                                    </td>
                                                    <td></td>
                                                    <td class="text-right">
                                                        <span
                                                            class="text-[#1515d0]"
                                                            >Analyse</span
                                                        >
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </template>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { defineComponent } from "vue";
import { confirmMatch, getUnmatched } from "@/Services/correct_score";
import {
    extractTime,
    extractTime7m,
    formatDate,
    findMatchingIndices,
} from "@/Types/func/index";
import { HANDICAP } from "@/Types/const/index";

import _ from "lodash";
import Fuse from "fuse.js";

import { Calendar, DatePicker } from "v-calendar";
import "v-calendar/style.css";
import Loading from "@/Components/Loading.vue";

const fuseOptions = {
    threshold: 0.3,
    keys: ["away_team.name", "home_team.name"],
};
export function sleep(ms) {
    return new Promise((resolve) => setTimeout(resolve, ms));
}

export default defineComponent({
    components: {
        Calendar,
        DatePicker,
        Loading,
    },
    data() {
        return {
            HANDICAP,
            formatDate,
            extractTime,
            extractTime7m,
            openDropdown: false,
            matches: [],
            matches7m: [],
            searchableMatches7m: [],
            searchableMatches: [],
            filteredMatches7m: [],
            isLoading: true,
            keyword: "",
            selectedId: "",
            loadingSvg: 0,
            showModal: false,
            calendarDropdown: false,
            autoSearchIndex: 0,

            selectedDate: {
                start: new Date(new Date().getTime() - 86400000 * 4),
                end: new Date(new Date().getTime() + 86400000),
            },

            dialog: {},

            compare: {
                game: null,
                game7m: null,
            },

            confirmMatchLoading: false,
        };
    },
    watch: {
        keyword() {
            const fuse = new Fuse(this.searchableMatches7m, fuseOptions);
            const searchResult = fuse.search(this.keyword);
            const searchResultIds = searchResult.map((x) => x.item.id);
            this.filteredMatches7m = this.matches7m
                .map((item) => ({
                    gd: item.gd,
                    matches: item.matches.filter((match) =>
                        searchResultIds.some((x) => x == match.id)
                    ),
                }))
                .filter((x) => x.matches.length > 0);
            if (this.keyword == "") {
                this.filteredMatches7m = _.cloneDeep(this.matches7m);
            }
        },
    },
    computed: {
        //
    },
    methods: {
        async confirmMatched() {
            this.confirmMatchLoading = true;
            try {
                console.log("this.compare", this.compare);
                await confirmMatch(
                    this.compare.game.id,
                    this.compare.game7m.id
                );
                this.compare = { game: null, game7m: null };
                this.filterData();
            } catch (e) {
                console.error(e);
            } finally {
                this.confirmMatchLoading = false;
            }
        },
        homeHidden(val) {
            if (val == 0) return "invisible";
        },

        awayHidden(val) {
            if (val == 1) return "invisible";
        },

        searchThisItem(keyword, id, game) {
            this.keyword = keyword;
            this.selectedId = id;
            this.compare.game = game;
            this.compare.game7m = null;
        },

        async getUnmatched(start_date = undefined, end_date = undefined) {
            try {
                this.isLoading = true;
                let filteredLeagueMatches = [];
                const res = await getUnmatched(start_date, end_date);

                // Lucky Sport
                const matches = res.games;
                this.searchableMatches = _.cloneDeep(matches);
                let lastItem = "";
                let i = 0;
                matches.forEach((match) => {
                    if (lastItem == match.gd) {
                        filteredLeagueMatches[i - 1].timeMatches.push(match);
                    } else {
                        lastItem = match.gd;
                        filteredLeagueMatches[i] = {
                            gd: match.gd,
                            timeMatches: [match],
                        };
                        i++;
                    }
                });
                this.matches = filteredLeagueMatches;

                let filteredDateMatches = [];
                let index = 0;
                filteredLeagueMatches.forEach((leagueMatch) => {
                    let temp = [];
                    i = 0;
                    lastItem = "";
                    leagueMatch.timeMatches.forEach((match) => {
                        if (lastItem == match.league.english) {
                            temp[i - 1].matches.push(match);
                        } else {
                            lastItem = match.league.english;

                            temp[i] = {
                                league: match.league.english,
                                league_short: match.league.english_short,
                                matches: [match],
                            };

                            i++;
                        }
                    });
                    if (index == 0) {
                        filteredDateMatches = [
                            {
                                gd: leagueMatch.gd,
                                timeMatches: temp,
                            },
                        ];
                    } else {
                        filteredDateMatches[index] = {
                            gd: leagueMatch.gd,
                            timeMatches: temp,
                        };
                    }
                    index++;
                });
                this.matches = filteredDateMatches;
                // End  Lucky Sport

                // 7m
                let filteredDate7mMatches = [];
                const matches7m = res.game7ms;
                this.searchableMatches7m = _.cloneDeep(matches7m);
                let last7mItem = "";
                let j = 0;
                matches7m.forEach((match) => {
                    if (last7mItem == match.gd) {
                        filteredDate7mMatches[j - 1].matches.push(match);
                    } else {
                        last7mItem = match.gd;
                        filteredDate7mMatches[j] = {
                            gd: match.gd,
                            matches: [match],
                        };
                        j++;
                    }
                });
                this.matches7m = filteredDate7mMatches;
                this.filteredMatches7m = _.cloneDeep(this.matches7m);
                // End 7m
            } catch (e) {
                //
            } finally {
                this.isLoading = false;
            }
        },

        async autoSearch(i = 0) {
            const homeSearch =
                this.searchableMatches[this.autoSearchIndex].home_team.english;
            const homeFuse = new Fuse(this.searchableMatches7m, fuseOptions);
            const homeSearchResult = homeFuse.search(homeSearch);
            const homeSearchResultIds = homeSearchResult.map((x) => ({
                id: x.item.id,
                league: x.item.league,
                away_team: x.item.away_team,
                home_team: x.item.home_team,
                gd: x.item.gd,
            }));

            const awaySearch =
                this.searchableMatches[this.autoSearchIndex].away_team.english;
            const awayFuse = new Fuse(this.searchableMatches7m, fuseOptions);
            const awaySearchResult = awayFuse.search(awaySearch);
            const awaySearchResultIds = awaySearchResult.map((x) => ({
                id: x.item.id,
                league: x.item.league,
                away_team: x.item.away_team,
                home_team: x.item.home_team,
                gd: x.item.gd,
            }));

            await sleep(200);
            const indices = findMatchingIndices(
                homeSearchResultIds,
                awaySearchResultIds
            );
            if (indices.length > 0) {
                this.dialog = {
                    league: this.searchableMatches[this.autoSearchIndex].league,
                    homeTeam:
                        this.searchableMatches[this.autoSearchIndex].home_team,
                    awayTeam:
                        this.searchableMatches[this.autoSearchIndex].away_team,

                    league7m: homeSearchResultIds[indices[0][0]].league,
                    homeTeam7m: homeSearchResultIds[indices[0][0]].home_team,
                    awayTeam7m: homeSearchResultIds[indices[0][0]].away_team,
                    dateMatch: this.formatDate(
                        homeSearchResultIds[indices[0][0]].gd
                    ),
                };
            }
            this.showModal = !this.showModal;
            this.autoSearchIndex++;

            await sleep(2000);
            // this.ignoreMatch()
        },

        async ignoreMatch() {
            this.showModal = false;
            await sleep(300);
            // this.autoSearch()
        },

        async filterData() {
            await this.getUnmatched(
                this.selectedDate.start,
                this.selectedDate.end
            );
        },
    },
    async mounted() {
        this.loadingSvg = Math.floor(Math.random() * 10);
        await this.getUnmatched(this.selectedDate.start, this.selectedDate.end);
    },
});
</script>
