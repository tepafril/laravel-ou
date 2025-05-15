<template>
    <Head title="Handicap Report" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Handicap Report {{ selectedTab }}
            </h2>
        </template>

        <div class="py-12">
            <ul class="flex flex-wrap -mb-px">
                <li class="me-2" @click="selectedTab = 's2'">
                    <a
                        class="cursor-pointer"
                        :class="
                            selectedTab == 's2'
                                ? 'inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active '
                                : 'inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 '
                        "
                        >S2 Handicap</a
                    >
                </li>
                <li class="me-2" @click="selectedTab = 'li'">
                    <a
                        class="cursor-pointer"
                        :class="
                            selectedTab == 'li'
                                ? 'inline-block p-4 text-blue-600 border-b-2 border-blue-600 rounded-t-lg active '
                                : 'inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300 '
                        "
                        aria-current="page"
                        >Over/Under Handicap</a
                    >
                </li>
            </ul>
            <div class="w-full mx-auto sm:px-6 lg:px-8 flex items-start">
                <div class="w-[360px]">
                    <div
                        v-if="selectedTab == 's2'"
                        class="w-[360px] space-y-2 bg-white p-1"
                    >
                        <div
                            v-for="i in [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]"
                            class="space-x-2"
                        >
                            <button
                                class="relative inline-flex items-center bg-transparent font-semibold py-2 border border-gray-500 rounded w-[80px] text-lg justify-center"
                                :class="[
                                    selectedS2 == 0 + i
                                        ? '!bg-gray-500 !text-white border-transparent cursor-pointer'
                                        : '',
                                    ,
                                    currentS2 == 0
                                        ? 'bg-gray-500 text-white'
                                        : '',
                                    getHandicapCount(0 + i) == 0
                                        ? 'opacity-30 cursor-not-allowed'
                                        : 'hover:bg-gray-500 text-gray-700 hover:text-white hover:border-transparent cursor-pointer',
                                ]"
                                @click="viewS2(0 + i)"
                            >
                                {{ i == 0 ? "=" : i }}
                                <div
                                    v-if="getHandicapCount(0 + i) > 0"
                                    class="absolute inline-flex items-center justify-center px-1 h-6 text-xs text-white bg-red-500 border-2 border-white rounded-md -top-2 -end-2"
                                >
                                    {{ getHandicapCount(0 + i) }}
                                </div>
                            </button>
                            <button
                                class="relative inline-flex items-center bg-transparent font-semibold py-2 border border-gray-500 rounded w-[80px] text-lg justify-center"
                                :class="[
                                    selectedS2 == 0.25 + i
                                        ? '!bg-gray-500 !text-white border-transparent cursor-pointer'
                                        : '',
                                    ,
                                    currentS2 == 0.25
                                        ? 'bg-gray-500 text-white'
                                        : '',
                                    getHandicapCount(0.25 + i) == 0
                                        ? 'opacity-30 cursor-not-allowed'
                                        : 'hover:bg-gray-500 text-gray-700 hover:text-white hover:border-transparent cursor-pointer',
                                ]"
                                @click="viewS2(0.25 + i)"
                            >
                                {{ i }}+½
                                <div
                                    v-if="getHandicapCount(0.25 + i) > 0"
                                    class="absolute inline-flex items-center justify-center px-1 h-6 text-xs text-white bg-red-500 border-2 border-white rounded-md -top-2 -end-2"
                                >
                                    {{ getHandicapCount(0.25 + i) }}
                                </div>
                            </button>
                            <button
                                class="relative inline-flex items-center bg-transparent font-semibold py-2 border border-gray-500 rounded w-[80px] text-lg justify-center"
                                :class="[
                                    selectedS2 == 0.5 + i
                                        ? '!bg-gray-500 !text-white border-transparent cursor-pointer'
                                        : '',
                                    ,
                                    currentS2 == 0.5
                                        ? 'bg-gray-500 text-white'
                                        : '',
                                    getHandicapCount(0.5 + i) == 0
                                        ? 'opacity-30 cursor-not-allowed'
                                        : 'hover:bg-gray-500 text-gray-700 hover:text-white hover:border-transparent cursor-pointer',
                                ]"
                                @click="viewS2(0.5 + i)"
                            >
                                {{ i == 0 ? "" : i }}½
                                <div
                                    v-if="getHandicapCount(0.5 + i) > 0"
                                    class="absolute inline-flex items-center justify-center px-1 h-6 text-xs text-white bg-red-500 border-2 border-white rounded-md -top-2 -end-2"
                                >
                                    {{ getHandicapCount(0.5 + i) }}
                                </div>
                            </button>
                            <button
                                class="relative inline-flex items-center bg-transparent font-semibold py-2 border border-gray-500 rounded w-[80px] text-lg justify-center"
                                :class="[
                                    selectedS2 == 0.75 + i
                                        ? '!bg-gray-500 !text-white border-transparent cursor-pointer'
                                        : '',
                                    ,
                                    currentS2 == 0.75
                                        ? 'bg-gray-500 text-white'
                                        : '',
                                    getHandicapCount(0.75 + i) == 0
                                        ? 'opacity-30 cursor-not-allowed'
                                        : 'hover:bg-gray-500 text-gray-700 hover:text-white hover:border-transparent cursor-pointer',
                                ]"
                                @click="viewS2(0.75 + i)"
                            >
                                {{ i == 0 ? "" : i }}½+{{ i + 1 }}
                                <div
                                    v-if="getHandicapCount(0.75 + i) > 0"
                                    class="absolute inline-flex items-center justify-center px-1 h-6 text-xs text-white bg-red-500 border-2 border-white rounded-md -top-2 -end-2"
                                >
                                    {{ getHandicapCount(0.75 + i) }}
                                </div>
                            </button>
                        </div>
                    </div>
                    <div
                        v-else-if="selectedTab == 'li'"
                        class="w-[360px] space-y-2 bg-white p-1"
                    >
                        <div
                            v-for="i in [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]"
                            class="space-x-2"
                        >
                            <button
                                class="relative inline-flex items-center bg-transparent font-semibold py-2 border border-gray-500 rounded w-[80px] text-lg justify-center"
                                :class="[
                                    selectedLi == 0 + i
                                        ? '!bg-gray-500 !text-white border-transparent cursor-pointer'
                                        : '',
                                    ,
                                    currentS2 == 0
                                        ? 'bg-gray-500 text-white'
                                        : '',
                                    getLiHandicapCount(0 + i) == 0
                                        ? 'opacity-30 cursor-not-allowed'
                                        : 'hover:bg-gray-500 text-gray-700 hover:text-white hover:border-transparent cursor-pointer',
                                ]"
                                @click="viewLi(0 + i)"
                            >
                                {{ i == 0 ? "=" : i }}
                                <div
                                    v-if="getLiHandicapCount(0 + i) > 0"
                                    class="absolute inline-flex items-center justify-center px-1 h-6 text-xs text-white bg-red-500 border-2 border-white rounded-md -top-2 -end-2"
                                >
                                    {{ getLiHandicapCount(0 + i) }}
                                </div>
                            </button>
                            <button
                                class="relative inline-flex items-center bg-transparent font-semibold py-2 border border-gray-500 rounded w-[80px] text-lg justify-center"
                                :class="[
                                    selectedLi == 0.25 + i
                                        ? '!bg-gray-500 !text-white border-transparent cursor-pointer'
                                        : '',
                                    ,
                                    currentS2 == 0.25
                                        ? 'bg-gray-500 text-white'
                                        : '',
                                    getLiHandicapCount(0.25 + i) == 0
                                        ? 'opacity-30 cursor-not-allowed'
                                        : 'hover:bg-gray-500 text-gray-700 hover:text-white hover:border-transparent cursor-pointer',
                                ]"
                                @click="viewLi(0.25 + i)"
                            >
                                {{ i }}+½
                                <div
                                    v-if="getLiHandicapCount(0.25 + i) > 0"
                                    class="absolute inline-flex items-center justify-center px-1 h-6 text-xs text-white bg-red-500 border-2 border-white rounded-md -top-2 -end-2"
                                >
                                    {{ getLiHandicapCount(0.25 + i) }}
                                </div>
                            </button>
                            <button
                                class="relative inline-flex items-center bg-transparent font-semibold py-2 border border-gray-500 rounded w-[80px] text-lg justify-center"
                                :class="[
                                    selectedLi == 0.5 + i
                                        ? '!bg-gray-500 !text-white border-transparent cursor-pointer'
                                        : '',
                                    ,
                                    currentS2 == 0.5
                                        ? 'bg-gray-500 text-white'
                                        : '',
                                    getLiHandicapCount(0.5 + i) == 0
                                        ? 'opacity-30 cursor-not-allowed'
                                        : 'hover:bg-gray-500 text-gray-700 hover:text-white hover:border-transparent cursor-pointer',
                                ]"
                                @click="viewLi(0.5 + i)"
                            >
                                {{ i == 0 ? "" : i }}½
                                <div
                                    v-if="getLiHandicapCount(0.5 + i) > 0"
                                    class="absolute inline-flex items-center justify-center px-1 h-6 text-xs text-white bg-red-500 border-2 border-white rounded-md -top-2 -end-2"
                                >
                                    {{ getLiHandicapCount(0.5 + i) }}
                                </div>
                            </button>
                            <button
                                class="relative inline-flex items-center bg-transparent font-semibold py-2 border border-gray-500 rounded w-[80px] text-lg justify-center"
                                :class="[
                                    selectedLi == 0.75 + i
                                        ? '!bg-gray-500 !text-white border-transparent cursor-pointer'
                                        : '',
                                    ,
                                    currentS2 == 0.75
                                        ? 'bg-gray-500 text-white'
                                        : '',
                                    getLiHandicapCount(0.75 + i) == 0
                                        ? 'opacity-30 cursor-not-allowed'
                                        : 'hover:bg-gray-500 text-gray-700 hover:text-white hover:border-transparent cursor-pointer',
                                ]"
                                @click="viewLi(0.75 + i)"
                            >
                                {{ i == 0 ? "" : i }}½+{{ i + 1 }}
                                <div
                                    v-if="getLiHandicapCount(0.75 + i) > 0"
                                    class="absolute inline-flex items-center justify-center px-1 h-6 text-xs text-white bg-red-500 border-2 border-white rounded-md -top-2 -end-2"
                                >
                                    {{ getLiHandicapCount(0.75 + i) }}
                                </div>
                            </button>
                        </div>
                    </div>
                </div>
                <div
                    class="w-full bg-white overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div
                        v-if="isLoading"
                        class="py-6 flex items-center justify-center w-full"
                    >
                        <Loading />
                    </div>
                    <div v-else class="p-2 text-gray-900 border rounded-lg">
                        <!-- SVG Icons -->
                        <div class="flex gap-1 mb-4">
                            <div class="w-full space-y-4">
                                <div
                                    v-for="record in recordObj?.data ?? []"
                                    class="border rounded-lg w-full p-2"
                                >
                                    <div class="w-full">
                                        <div class="flex items-center">
                                            <div class="w-[120px]">
                                                {{ record.oo }} -
                                                {{ record.uo }}:
                                                <span
                                                    class="text-blue-600 text-lg font-bold"
                                                    >H</span
                                                >
                                            </div>

                                            <template
                                                v-for="homeRec in record.home"
                                            >
                                                <SvgIcon
                                                    :name="
                                                        getSign(homeRec, 'home')
                                                    "
                                                    :color="
                                                        getColor(
                                                            homeRec,
                                                            'home'
                                                        )
                                                    "
                                                />
                                            </template>
                                        </div>
                                        <div class="flex items-center">
                                            <div class="w-[120px]">
                                                {{ record.oo }} -
                                                {{ record.uo }}:
                                                <span
                                                    class="text-red-600 text-lg font-bold"
                                                    >A</span
                                                >
                                            </div>

                                            <SvgIcon
                                                v-for="away in record.away"
                                                name="close-thick"
                                                color="#EF4444"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- <SvgIcon name="close-thick" color="#EF4444" />
                            <SvgIcon name="equal" color="#3B82F6" size="24" />
                            <SvgIcon name="circle-outline" color="#10B981" /> -->
                        </div>

                        <div
                            v-if="isLoading"
                            class="flex justify-center items-center"
                        >
                            <div
                                class="animate-spin rounded-full h-8 w-8 border-b-2 border-gray-900"
                            ></div>
                        </div>

                        <div v-else class="grid grid-cols-4 gap-4">
                            <div
                                v-for="handicap in handicaps"
                                :key="handicap.f20a"
                                class="p-4 border rounded-lg shadow-sm hover:shadow-md transition-shadow"
                            >
                                <div class="text-lg font-semibold">
                                    {{ handicap.f20a }}
                                </div>
                                <div class="text-2xl font-bold text-blue-600">
                                    {{ handicap.count }}
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
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import SvgIcon from "@/Components/SvgIcon.vue";

import { defineComponent } from "vue";
import {
    fetch7mGames,
    getCorrectScoreMatch,
    fetchReportCountS2,
    fetchReportCountLi,
    Record,
} from "@/Services/correct_score";
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
        AuthenticatedLayout,
        Head,
        SvgIcon,
    },
    data() {
        return {
            isLoading: false,
            s20Count: [],
            liCount: [],
            records: [],
            selectedTab: "s2", // 's2' | 'li'
            selectedS2: null,
            selectedLi: null,
            recordObj: null,
        };
    },
    watch: {},
    methods: {
        async fetchReportCountS2() {
            try {
                this.isLoading = true;
                this.s20Count = await fetchReportCountS2();
            } catch (e) {
                //
            } finally {
                this.isLoading = false;
            }
        },
        async fetchReportCountLi() {
            try {
                this.isLoading = true;
                this.liCount = await fetchReportCountLi(this.selectedS2);
            } catch (e) {
                //
            } finally {
                this.isLoading = false;
            }
        },
        async fetchReportRecords() {
            try {
                this.isLoading = true;
                this.recordObj = new Record();
                await this.recordObj.fetchReportRecords(
                    this.selectedS2,
                    this.selectedLi
                );
                console.log(this.recordObj.data);
            } catch (e) {
                //
            } finally {
                this.isLoading = false;
            }
        },
        getHandicapCount(val) {
            const count = this.s20Count.find(
                (x) => Number(x.f20a) === Number(val)
            );
            return count ? count.count : 0;
        },
        getLiHandicapCount(val) {
            const count = this.liCount.find(
                (x) => Number(x.li) === Number(val)
            );
            return count ? count.count : 0;
        },
        getSign(record, homeOrAway) {
            if (record?.is_wn == "win" || record?.is_wn == "win_half") {
                return "close-thick";
            } else if (
                record?.is_wn == "loss" ||
                record?.is_wn == "loss_half"
            ) {
                return "circle-outline";
            } else {
                return "equal";
            }
        },
        getColor(record, homeOrAway) {
            if (record?.is_ov == "over") {
                return "#EF4444";
            } else if (record?.is_wn == "under") {
                return "#3B82F6";
            } else {
                return "#000000";
            }
        },
        async viewS2(value) {
            if (this.getHandicapCount(value) > 0) {
                this.selectedS2 = value;
                this.selectedLi = null;
                this.recordObj = null;
                this.selectedTab = "li";
                await this.fetchReportCountLi();
            }
        },
        async viewLi(value) {
            if (this.getLiHandicapCount(value) > 0) {
                this.selectedLi = value;
                await this.fetchReportRecords();
            }
        },
    },
    async mounted() {
        await this.fetchReportCountS2();
    },
});
</script>

<style scoped>
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
}
</style>
