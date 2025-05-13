<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Report
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

import { Calendar, DatePicker } from "v-calendar";
import "v-calendar/style.css";

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

    computed: {
        //
    },
    methods: {
        async fetch7mGames(start_date = undefined, end_date = undefined) {
            try {
            } catch (e) {
                //
            } finally {
                this.isLoading = false;
            }
        },
    },
    async mounted() {
        // this.autoSearch()
    },
});
</script>
