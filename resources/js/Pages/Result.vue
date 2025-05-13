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
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                            >
                                <title>close-thick</title>
                                <path
                                    d="M20 6.91L17.09 4L12 9.09L6.91 4L4 6.91L9.09 12L4 17.09L6.91 20L12 14.91L17.09 20L20 17.09L14.91 12L20 6.91Z"
                                />
                            </svg>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                            >
                                <title>equal</title>
                                <path d="M19,10H5V8H19V10M19,16H5V14H19V16Z" />
                            </svg>
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                viewBox="0 0 24 24"
                            >
                                <title>circle-outline</title>
                                <path
                                    d="M12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z"
                                />
                            </svg>
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
