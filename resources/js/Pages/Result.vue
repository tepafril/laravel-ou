<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Result
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <!-- Table -->
                        <div class="gap-4 max-h-screen overflow-scroll">
                            <!-- Lucky Sport -->
                            <div
                                id="cs-container"
                                class="bg-gray-300 p-4 border-r border-gray-400"
                            >
                                <div
                                    id="sticky-title"
                                    class="font-bold text-white flex items-center justify-center bg-[#3e6fa7] border-[#679898] border-t"
                                >
                                    <div class="w-2/12 text-center">
                                        Kick Off
                                    </div>

                                    <div
                                        class="w-1/12 border-[#679898] border-l"
                                    ></div>
                                    <div
                                        class="w-3/12 border-[#679898] border-l"
                                    >
                                        <div
                                            class="h-[72px] flex items-center justify-center border-[#679898] border-b"
                                        >
                                            Home
                                        </div>
                                    </div>
                                    <div
                                        class="w-3/12 border-[#679898] border-l"
                                    >
                                        <div
                                            class="h-[72px] flex items-center justify-center border-[#679898] border-b"
                                        >
                                            Away
                                        </div>
                                    </div>
                                    <div
                                        class="w-1/12 border-[#679898] border-l"
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
                                                <div
                                                    class="w-[7px] h-[22px] bg-[url('/img/rp_bg.png')] m-[0px_0px_0px_18px]"
                                                    :style="`background-color:#${timeMatch.bg_color}`"
                                                ></div>
                                                <div class="ml-[8px]">
                                                    {{ timeMatch.league7m }}
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
                                                                selectedId ==
                                                                match.home_team
                                                                    .id
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
                                                                        .id
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

                                                            <td
                                                                class="w-1/12 bg-[#f0f3f6]"
                                                            >
                                                                LS
                                                            </td>
                                                            <td class="w-3/12">
                                                                {{
                                                                    match
                                                                        ?.home_team
                                                                        ?.english
                                                                }}
                                                            </td>
                                                            <td class="w-3/12">
                                                                {{
                                                                    match
                                                                        ?.away_team
                                                                        ?.english
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
                                                                selectedId ==
                                                                match.away_team
                                                                    .id
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
                                                                        .id
                                                                )
                                                            "
                                                        >
                                                            <td
                                                                class="w-1/12 bg-[#f0f3f6]"
                                                            >
                                                                7m
                                                            </td>
                                                            <td class="w-3/12">
                                                                {{
                                                                    match
                                                                        ?.game7m
                                                                        ?.home_team
                                                                        ?.name
                                                                }}
                                                            </td>
                                                            <td class="w-3/12">
                                                                {{
                                                                    match
                                                                        ?.game7m
                                                                        ?.away_team
                                                                        ?.name
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
} from "@/Types/func/index";
import { HANDICAP } from "@/Types/const/index";

import _ from "lodash";
import Fuse from "fuse.js";

import { Calendar, DatePicker } from "v-calendar";
import "v-calendar/style.css";

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
    },
    data() {
        return {
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
                end: new Date(new Date().getTime() + 86400000 * 10),
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
                            console.log("league", match.game7m.league.bg_color);
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

            // console.log("homeSearchResultIds", homeSearchResultIds)
            // console.log("awaySearchResultIds", awaySearchResultIds)
            // console.log("================================================================")
            await sleep(200);
            const indices = findMatchingIndices(
                homeSearchResultIds,
                awaySearchResultIds
            );
            if (indices.length > 0) {
                console.log(
                    homeSearchResultIds[indices[0][0]].home_team.name,
                    " <> ",
                    this.searchableMatches[i].home_team.english
                );

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
                console.log(
                    "================================================================"
                );
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
            await this.fetch7mGames(
                this.selectedDate.start,
                this.selectedDate.end
            );
        },
    },
    async mounted() {
        this.loadingSvg = Math.floor(Math.random() * 10);
        await this.getCorrectScoreMatch(
            this.selectedDate.start,
            this.selectedDate.end
        );
        await this.fetch7mGames(this.selectedDate.start, this.selectedDate.end);
        // this.autoSearch()
    },
});
</script>
