<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="py-6">
            <div class="max-w-7xl mx-auto sm:px-3 lg:px-4">
                <div
                    class="bg-white overflow-hidden shadow-sm sm:rounded-lg sm:pb-3 lg:pb-4"
                >
                    <div class="p-0 text-gray-900">
                        <div
                            v-if="isLoading"
                            class="flex items-center justify-center py-10"
                        >
                            <Loading />
                        </div>
                        <template v-else>
                            <div
                                class="grid grid-cols-1 gap-4 px-4 mt-8 sm:grid-cols-3 sm:px-8"
                            >
                                <!-- Total Games -->
                                <div
                                    class="flex items-center bg-white border rounded-sm overflow-hidden shadow"
                                >
                                    <div class="p-4 bg-red-400">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-12 w-12 text-white"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"
                                            ></path>
                                        </svg>
                                    </div>
                                    <div class="px-4 text-gray-700">
                                        <h3 class="text-sm tracking-wider">
                                            Total Games
                                        </h3>
                                        <p class="text-3xl">
                                            {{ formatNumber(dashboard?.count?.total) }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Matched Games -->
                                <div
                                    class="flex items-center bg-white border rounded-sm overflow-hidden shadow"
                                >
                                    <div class="p-4 bg-green-400">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            height="48px"
                                            viewBox="0 -960 960 960"
                                            width="48px"
                                            fill="#e8eaed"
                                        >
                                            <path
                                                d="M358-186q-81-32-133.95-100.5Q171.09-355 160-442h60q9 62 46 111.5t92 78.5v66ZM495-80q-18.75 0-31.87-13.13Q450-106.25 450-125v-258q0-18.75 13.13-31.88Q476.25-428 495-428h99q11.4 0 21.66 5.19Q625.92-417.63 632-408l27 42h176q18.75 0 31.88 13.12Q880-339.75 880-321v196q0 18.75-13.12 31.87Q853.75-80 835-80H495ZM125-532q-18.75 0-31.87-13.13Q80-558.25 80-577v-258q0-18.75 13.13-31.88Q106.25-880 125-880h99q11.4 0 21.66 5.19Q255.92-869.63 262-860l27 42h176q18.75 0 31.88 13.12Q510-791.75 510-773v196q0 18.75-13.12 31.87Q483.75-532 465-532H125Zm615 52q0-72-38-132.5T600-706v-66q91 36.5 145.5 115.91T800-480h-60ZM510-140h310v-166H627l-41-62h-76v228ZM140-592h310v-166H257l-41-62h-76v228Zm370 452v-228 228ZM140-592v-228 228Z"
                                            />
                                        </svg>
                                    </div>
                                    <div class="px-4 text-gray-700">
                                        <h3 class="text-sm tracking-wider">
                                            Matched Games
                                        </h3>
                                        <p class="text-3xl">
                                            {{
                                                formatNumber(dashboard?.count?.matched)
                                            }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Unmatched Games -->
                                <div
                                    class="flex items-center bg-white border rounded-sm overflow-hidden shadow"
                                >
                                    <div class="p-4 bg-blue-400">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            height="48px"
                                            viewBox="0 -960 960 960"
                                            width="48px"
                                            fill="#e8eaed"
                                        >
                                            <path
                                                d="M180-120q-24 0-42-18t-18-42v-600q0-24 18-42t42-18h210q24 0 42 18t18 42v600q0 24-18 42t-42 18H180Zm390 0q-24 0-42-18t-18-42v-600q0-24 18-42t42-18h210q24 0 42 18t18 42v600q0 24-18 42t-42 18H570Zm210-660H570v600h210v-600Z"
                                            />
                                        </svg>
                                    </div>
                                    <div class="px-4 text-gray-700">
                                        <h3 class="text-sm tracking-wider">
                                            Unmatched Games
                                        </h3>
                                        <p class="text-3xl">
                                            {{
                                                formatNumber(
                                                    dashboard?.count?.unmatched
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="grid grid-cols-1 gap-4 px-4 mt-8 sm:grid-cols-3 sm:px-8"
                            >
                                <!-- Total Games -->
                                <div
                                    class="flex items-center bg-white border rounded-sm overflow-hidden shadow"
                                >
                                    <div class="p-4 bg-yellow-400">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            class="h-12 w-12 text-white"
                                            fill="none"
                                            viewBox="0 0 24 24"
                                            stroke="currentColor"
                                        >
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                stroke-width="2"
                                                d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"
                                            ></path>
                                        </svg>
                                    </div>
                                    <div class="px-4 text-gray-700">
                                        <h3 class="text-sm tracking-wider">
                                            Today's Total Games
                                        </h3>
                                        <p class="text-3xl">
                                            {{
                                                formatNumber(
                                                    dashboard?.count?.todayTotal
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Matched Games -->
                                <div
                                    class="flex items-center bg-white border rounded-sm overflow-hidden shadow"
                                >
                                    <div class="p-4 bg-yellow-400">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            height="48px"
                                            viewBox="0 -960 960 960"
                                            width="48px"
                                            fill="#e8eaed"
                                        >
                                            <path
                                                d="M358-186q-81-32-133.95-100.5Q171.09-355 160-442h60q9 62 46 111.5t92 78.5v66ZM495-80q-18.75 0-31.87-13.13Q450-106.25 450-125v-258q0-18.75 13.13-31.88Q476.25-428 495-428h99q11.4 0 21.66 5.19Q625.92-417.63 632-408l27 42h176q18.75 0 31.88 13.12Q880-339.75 880-321v196q0 18.75-13.12 31.87Q853.75-80 835-80H495ZM125-532q-18.75 0-31.87-13.13Q80-558.25 80-577v-258q0-18.75 13.13-31.88Q106.25-880 125-880h99q11.4 0 21.66 5.19Q255.92-869.63 262-860l27 42h176q18.75 0 31.88 13.12Q510-791.75 510-773v196q0 18.75-13.12 31.87Q483.75-532 465-532H125Zm615 52q0-72-38-132.5T600-706v-66q91 36.5 145.5 115.91T800-480h-60ZM510-140h310v-166H627l-41-62h-76v228ZM140-592h310v-166H257l-41-62h-76v228Zm370 452v-228 228ZM140-592v-228 228Z"
                                            />
                                        </svg>
                                    </div>
                                    <div class="px-4 text-gray-700">
                                        <h3 class="text-sm tracking-wider">
                                            Today's Matched Games
                                        </h3>
                                        <p class="text-3xl">
                                            {{
                                                formatNumber(
                                                    dashboard?.count?.todayMatched
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Unmatched Games -->
                                <div
                                    class="flex items-center bg-white border rounded-sm overflow-hidden shadow"
                                >
                                    <div class="p-4 bg-yellow-400">
                                        <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            height="48px"
                                            viewBox="0 -960 960 960"
                                            width="48px"
                                            fill="#e8eaed"
                                        >
                                            <path
                                                d="M180-120q-24 0-42-18t-18-42v-600q0-24 18-42t42-18h210q24 0 42 18t18 42v600q0 24-18 42t-42 18H180Zm390 0q-24 0-42-18t-18-42v-600q0-24 18-42t42-18h210q24 0 42 18t18 42v600q0 24-18 42t-42 18H570Zm210-660H570v600h210v-600Z"
                                            />
                                        </svg>
                                    </div>
                                    <div class="px-4 text-gray-700">
                                        <h3 class="text-sm tracking-wider">
                                            Today's Unmatched Games
                                        </h3>
                                        <p class="text-3xl">
                                            {{
                                                formatNumber(
                                                    dashboard?.count?.todayUnmatched
                                                )
                                            }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="grid grid-cols-2 gap-4 px-4 mt-8 sm:px-8"
                            >
                                <div>
                                    <Line
                                        :data="chartData"
                                        :options="chartOptions"
                                    />
                                </div>
                                <div>
                                    <Line
                                        :data="chartData"
                                        :options="chartOptions"
                                    />
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import { Line } from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale,
} from "chart.js";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    LineElement,
    PointElement,
    CategoryScale,
    LinearScale
);

import { defineComponent } from "vue";
import Loading from "@/Components/Loading.vue";
import { getDashboard } from "@/Services/correct_score";
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";

import { Head } from "@inertiajs/vue3";

export default defineComponent({
    data() {
        return {
            isLoading: false,
            dashboard: {
                total: 0,
            },

            chartData: {
                labels: [
                    "2025-04-29",
                    "2025-04-30",
                    "2025-05-01",
                    "2025-05-02",
                    "2025-05-03",
                    "2025-05-04",
                    "2025-05-05",
                    "2025-05-06",
                    "2025-05-07",
                ],
                datasets: [
                    {
                        label: "Under",
                        data: [30, 12, 51, 30, 12, 51, 30, 12, 51],
                        borderColor: "#f07274",
                        tension: 0.4,
                        fill: false,
                    },
                    {
                        label: "Over",
                        data: [20, 50, 30, 20, 50, 30, 20, 50, 30],
                        borderColor: "#6ea1f9",
                        tension: 0.4,
                        fill: false,
                    },
                ],
            },
            chartOptions: {
                responsive: true,
                plugins: {
                    legend: { position: "top" },
                    title: {
                        display: true,
                        text: "Under/Over Line Chart",
                    },
                },
            },
        };
    },
    methods: {
        formatNumber(num) {
            return new Intl.NumberFormat("en-US").format(num);
        },

        async getDashboard() {
            try {
                this.isLoading = true;
                this.dashboard = await getDashboard();
            } catch (e) {
            } finally {
                this.isLoading = false;
            }
        },
    },
    mounted() {
        this.getDashboard();
    },
    components: {
        Line,
        Loading,
        Head,
        AuthenticatedLayout,
    },
});
</script>
