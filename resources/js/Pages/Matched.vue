<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Matched Teams
            </h2>
        </template>

        <div class="pt-6">
            <div class="mx-auto sm:px-6 flex items-start lg:px-8">
                <div
                    class="w-[320px] mr-4 bg-white border border-gray-300 rounded-md shadow-lg max-w-72"
                >
                    <DatePicker
                        borderless
                        v-model.range="selectedDate"
                        mode="date"
                    />
                    <div class="p-2 flex justify-end">
                        <button
                            type="button"
                            class="px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                            @click="
                                () => {
                                    filterData();
                                }
                            "
                        >
                            Search
                        </button>
                    </div>
                </div>
                <div
                    class="w-full bg-white min-h-[400px] max-h-[calc(100vh_-_180px)] overflow-scroll shadow-sm sm:rounded-lg"
                >
                    <div class="text-gray-900 w-full">
                        <div
                            v-if="isLoading"
                            class="flex items-center justify-center py-10"
                        >
                            <Loading />
                        </div>
                        <!-- Table -->
                        <div v-else class="gap-4">
                            <!-- Lucky Sport -->
                            <div
                                id="cs-container"
                                class="bg-gray-300 p-4 border-r border-gray-400"
                            >
                                <div
                                    id="sticky-title"
                                    class="font-bold text-white flex items-center justify-center bg-[#3e6fa7] border-[#679898] border-t"
                                >
                                    <div class="w-1/12 text-center">
                                        Time/Score
                                    </div>

                                    <div
                                        class="w-1/12 border-[#679898] border-l"
                                    >
                                        <div
                                            class="h-[72px] flex items-center justify-center"
                                        >
                                            S2
                                        </div>
                                    </div>

                                    <div
                                        class="w-3/12 border-[#679898] border-l"
                                    >
                                        <div
                                            class="h-[72px] flex items-center justify-center border-[#679898] border-b"
                                        >
                                            Lucksport
                                        </div>
                                    </div>
                                    <div
                                        class="w-3/12 border-[#679898] border-l"
                                    >
                                        <div
                                            class="h-[72px] flex items-center justify-center border-[#679898] border-b"
                                        >
                                            7msport
                                        </div>
                                    </div>
                                    <div
                                        class="w-2/12 border-[#679898] border-l"
                                    >
                                        <div class="text-center">&nbsp;</div>
                                        <div class="text-center">
                                            Total Goal
                                        </div>
                                        <div class="text-center">&nbsp;</div>
                                    </div>
                                    <div
                                        class="w-1/12 border-[#679898] border-l"
                                    >
                                        <div class="text-center">&nbsp;</div>
                                        <div class="text-center">Over</div>
                                        <div class="text-center">&nbsp;</div>
                                    </div>
                                    <div
                                        class="w-1/12 border-[#679898] border-l"
                                    >
                                        <div class="text-center">&nbsp;</div>
                                        <div class="text-center">Under</div>
                                        <div class="text-center">&nbsp;</div>
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
                                                <div class="w-2/12"></div>
                                                <div
                                                    class="w-3/12 flex h-[22px]"
                                                >
                                                    <div
                                                        class="w-[7px] h-[22px] bg-[url('/img/rp_bg.png')] m-[0px_0px_0px_0px]"
                                                        :style="`background-color:#${timeMatch.bg_color}`"
                                                    ></div>

                                                    <div class="ml-2">
                                                        <span class="mr-8">
                                                            {{
                                                                timeMatch.league_short
                                                            }}</span
                                                        >
                                                    </div>
                                                </div>
                                                <div
                                                    class="w-3/12 flex h-[22px]"
                                                >
                                                    <div
                                                        class="w-[7px] h-[22px] bg-[url('/img/rp_bg.png')] m-[0px_0px_0px_0px]"
                                                        :style="`background-color:#${timeMatch.bg_color}`"
                                                    ></div>
                                                    <div class="ml-2">
                                                        <span>
                                                            {{
                                                                timeMatch.league7m
                                                            }}</span
                                                        >
                                                    </div>
                                                </div>
                                                <div class="w-4/12"></div>
                                            </div>
                                            <div
                                                v-for="(
                                                    match, im
                                                ) in timeMatch.matches"
                                                :key="match.id"
                                            >
                                                <table
                                                    class="w-full border-collapse cs-team-table"
                                                    cellpadding="0"
                                                    cellspacing="0"
                                                >
                                                    <tbody>
                                                        <tr
                                                            :class="
                                                                im % 2 == 0
                                                                    ? 'bg-[#f0f3f6]'
                                                                    : 'bg-[#fff]'
                                                            "
                                                            class="text-black text-center cursor-pointer hover:bg-purple-500 hover:bg-opacity-30"
                                                        >
                                                            <td
                                                                :class="
                                                                    im % 2 == 0
                                                                        ? 'bg-[#f0f3f6]'
                                                                        : 'bg-[#fff]'
                                                                "
                                                                class="w-1/12"
                                                                rowspan="2"
                                                            >
                                                                {{
                                                                    match
                                                                        ?.game7m
                                                                        ?.status ==
                                                                    4
                                                                        ? `${
                                                                              match
                                                                                  ?.game7m
                                                                                  ?.ft_home_score ??
                                                                              `?`
                                                                          }-${
                                                                              match
                                                                                  ?.game7m
                                                                                  ?.ft_away_score ??
                                                                              `?`
                                                                          }`
                                                                        : extractTime(
                                                                              match.gt
                                                                          )
                                                                }}
                                                            </td>

                                                            <td class="w-1/12">
                                                                {{
                                                                    Number(
                                                                        match
                                                                            ?.game7m
                                                                            ?.f20
                                                                    ) <= 0
                                                                        ? `${
                                                                              getHandicapByValue(
                                                                                  match
                                                                                      ?.game7m
                                                                                      ?.f20
                                                                              ) ??
                                                                              ""
                                                                          }`
                                                                        : ""
                                                                }}
                                                            </td>

                                                            <td class="w-3/12">
                                                                <div
                                                                    class="flex items-center"
                                                                >
                                                                    <img
                                                                        v-if="
                                                                            match?.is_wn ==
                                                                                'win' ||
                                                                            match?.is_wn ==
                                                                                'win_half'
                                                                        "
                                                                        class="h-[32px] border border-gray-400"
                                                                        src="/img/winner.gif"
                                                                    />
                                                                    <img
                                                                        v-else-if="
                                                                            match?.is_wn ==
                                                                                'loss' ||
                                                                            match?.is_wn ==
                                                                                'loss_half'
                                                                        "
                                                                        class="h-[32px] border border-gray-400"
                                                                        src="/img/loser.gif"
                                                                    />
                                                                    <img
                                                                        v-else-if="
                                                                            match?.is_wn ==
                                                                            'draw'
                                                                        "
                                                                        class="h-[32px] border border-gray-400"
                                                                        src="/img/draw.gif"
                                                                    />
                                                                    <span
                                                                        class="ml-4"
                                                                    >
                                                                        {{
                                                                            match
                                                                                ?.home_team
                                                                                ?.english
                                                                        }}
                                                                    </span>
                                                                </div>
                                                            </td>

                                                            <td class="w-3/12">
                                                                <div
                                                                    class="flex items-center"
                                                                >
                                                                    <img
                                                                        v-if="
                                                                            match?.is_wn ==
                                                                                'win' ||
                                                                            match?.is_wn ==
                                                                                'win_half'
                                                                        "
                                                                        class="h-[32px] border border-gray-400"
                                                                        src="/img/winner.gif"
                                                                    />
                                                                    <img
                                                                        v-else-if="
                                                                            match?.is_wn ==
                                                                                'loss' ||
                                                                            match?.is_wn ==
                                                                                'loss_half'
                                                                        "
                                                                        class="h-[32px] border border-gray-400"
                                                                        src="/img/loser.gif"
                                                                    />
                                                                    <img
                                                                        v-else-if="
                                                                            match?.is_wn ==
                                                                            'draw'
                                                                        "
                                                                        class="h-[32px] border border-gray-400"
                                                                        src="/img/draw.gif"
                                                                    />
                                                                    <span
                                                                        class="ml-4"
                                                                    >
                                                                        {{
                                                                            match
                                                                                ?.game7m
                                                                                ?.home_team
                                                                                ?.name
                                                                        }}
                                                                    </span>
                                                                </div>
                                                            </td>
                                                            <td
                                                                class="w-2/12 bg-[#ccd7f5]"
                                                                rowspan="2"
                                                            >
                                                                <div
                                                                    class="flex items-center justify-center"
                                                                >
                                                                    <img
                                                                        v-if="
                                                                            match?.is_ov ==
                                                                            'over'
                                                                        "
                                                                        class="h-[32px] border border-gray-400"
                                                                        src="/img/over.gif"
                                                                    />
                                                                    <img
                                                                        v-else-if="
                                                                            match?.is_ov ==
                                                                            'under'
                                                                        "
                                                                        class="h-[32px] border border-gray-400"
                                                                        src="/img/under.gif"
                                                                    />
                                                                    <img
                                                                        v-else-if="
                                                                            match?.is_ov ==
                                                                            'draw'
                                                                        "
                                                                        class="h-[32px] border border-gray-400"
                                                                        src="/img/draw.gif"
                                                                    />
                                                                    <span
                                                                        class="ml-4"
                                                                    >
                                                                        {{
                                                                            getHandicap(
                                                                                match.li
                                                                            )
                                                                        }}
                                                                    </span>
                                                                </div>
                                                            </td>
                                                            <td
                                                                class="w-1/12 bg-[#ccd7f5]"
                                                                rowspan="2"
                                                            >
                                                                <span>{{
                                                                    match.oo * 1
                                                                }}</span>
                                                            </td>
                                                            <td
                                                                class="w-1/12 bg-[#ccd7f5]"
                                                                rowspan="2"
                                                            >
                                                                <span>{{
                                                                    match.uo * 1
                                                                }}</span>
                                                            </td>
                                                        </tr>
                                                        <tr
                                                            :class="
                                                                im % 2 == 0
                                                                    ? 'bg-[#f0f3f6]'
                                                                    : 'bg-[#fff]'
                                                            "
                                                            class="text-black text-center cursor-pointer hover:bg-purple-500 hover:bg-opacity-30"
                                                        >
                                                            <td class="w-1/12">
                                                                {{
                                                                    Number(
                                                                        match
                                                                            ?.game7m
                                                                            ?.f20
                                                                    ) > 0
                                                                        ? `${
                                                                              getHandicapByValue(
                                                                                  match
                                                                                      ?.game7m
                                                                                      ?.f20
                                                                              ) ??
                                                                              ""
                                                                          }`
                                                                        : ""
                                                                }}
                                                            </td>
                                                            <td class="w-3/12">
                                                                <div
                                                                    class="flex items-center"
                                                                >
                                                                    <img
                                                                        v-if="
                                                                            match?.is_wn ==
                                                                                'win' ||
                                                                            match?.is_wn ==
                                                                                'win_half'
                                                                        "
                                                                        class="h-[32px] border border-gray-400"
                                                                        src="/img/loser.gif"
                                                                    />
                                                                    <img
                                                                        v-else-if="
                                                                            match?.is_wn ==
                                                                                'loss' ||
                                                                            match?.is_wn ==
                                                                                'loss_half'
                                                                        "
                                                                        class="h-[32px] border border-gray-400"
                                                                        src="/img/winner.gif"
                                                                    />
                                                                    <img
                                                                        v-else-if="
                                                                            match?.is_wn ==
                                                                            'draw'
                                                                        "
                                                                        class="h-[32px] border border-gray-400"
                                                                        src="/img/draw.gif"
                                                                    />
                                                                    <span
                                                                        class="ml-4"
                                                                    >
                                                                        {{
                                                                            match
                                                                                ?.away_team
                                                                                ?.english
                                                                        }}
                                                                    </span>
                                                                </div>
                                                            </td>

                                                            <td class="w-3/12">
                                                                <div
                                                                    class="flex items-center"
                                                                >
                                                                    <img
                                                                        v-if="
                                                                            match?.is_wn ==
                                                                                'win' ||
                                                                            match?.is_wn ==
                                                                                'win_half'
                                                                        "
                                                                        class="h-[32px] border border-gray-400"
                                                                        src="/img/loser.gif"
                                                                    />
                                                                    <img
                                                                        v-else-if="
                                                                            match?.is_wn ==
                                                                                'loss' ||
                                                                            match?.is_wn ==
                                                                                'loss_half'
                                                                        "
                                                                        class="h-[32px] border border-gray-400"
                                                                        src="/img/winner.gif"
                                                                    />
                                                                    <img
                                                                        v-else-if="
                                                                            match?.is_wn ==
                                                                            'draw'
                                                                        "
                                                                        class="h-[32px] border border-gray-400"
                                                                        src="/img/draw.gif"
                                                                    />
                                                                    <span
                                                                        class="ml-4"
                                                                    >
                                                                        {{
                                                                            match
                                                                                ?.game7m
                                                                                ?.away_team
                                                                                ?.name
                                                                        }}
                                                                    </span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </template>
                                    </div>
                                </template>
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
import { fetch7mGames, getCorrectScoreMatch } from "@/Services/correct_score";
import {
    extractTime,
    extractTime7m,
    formatDate,
    findMatchingIndices,
    getHandicap,
} from "@/Types/func/index";
import { HANDICAP } from "@/Types/const/index";

import _ from "lodash";
import Fuse from "fuse.js";

import { Calendar, DatePicker } from "v-calendar";
import "v-calendar/style.css";
import Loading from "@/Components/Loading.vue";
import { getHandicapByValue } from "@/Types/func/func";

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
            getHandicapByValue,
            openDropdown: false,
            getHandicap,
            HANDICAP,
            formatDate,
            extractTime,
            extractTime7m,
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
                start: new Date(),
                end: new Date(),
            },

            dialog: {},
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
        homeHidden(val) {
            if (val == 0) return "invisible";
        },

        awayHidden(val) {
            if (val == 1) return "invisible";
        },

        searchThisItem(keyword, id) {
            this.keyword = keyword;
            this.selectedId = id;
        },

        async getCorrectScoreMatch(
            start_date = undefined,
            end_date = undefined
        ) {
            try {
                this.isLoading = true;
                let filteredLeagueMatches = [];
                const matches = await getCorrectScoreMatch(
                    start_date,
                    end_date
                );
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
                                bg_color: match.game7m.league.bg_color,
                                league7m: match.game7m.league.name,
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

                this.isLoading = false;
            } catch (e) {
                //
            }
        },

        async fetch7mGames(start_date = undefined, end_date = undefined) {
            try {
                let filteredDateMatches = [];
                const matches = await fetch7mGames(start_date, end_date);
                this.searchableMatches7m = _.cloneDeep(matches);
                let lastItem = "";
                let i = 0;
                matches.forEach((match) => {
                    if (lastItem == match.gd) {
                        filteredDateMatches[i - 1].matches.push(match);
                    } else {
                        lastItem = match.gd;
                        filteredDateMatches[i] = {
                            gd: match.gd,
                            matches: [match],
                        };
                        i++;
                    }
                });
                this.matches7m = filteredDateMatches;
                this.filteredMatches7m = _.cloneDeep(this.matches7m);
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
            this.isLoading = true;
            await this.getCorrectScoreMatch(
                this.selectedDate.start,
                this.selectedDate.end
            );
            this.isLoading = false;
            // await this.fetch7mGames(
            //     this.selectedDate.start,
            //     this.selectedDate.end
            // );
        },
    },
    async mounted() {
        this.loadingSvg = Math.floor(Math.random() * 10);
        await this.getCorrectScoreMatch(
            this.selectedDate.start,
            this.selectedDate.end
        );
        // await this.fetch7mGames(this.selectedDate.start, this.selectedDate.end);
        // this.autoSearch()
    },
});
</script>
