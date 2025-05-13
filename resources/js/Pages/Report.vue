<script setup>
import AuthenticatedLayout from "@/Layouts/AuthenticatedLayout.vue";
import { Head } from "@inertiajs/vue3";
import { ref, onMounted } from "vue";
import axios from "axios";
import SvgIcon from "@/Components/SvgIcon.vue";

const handicaps = ref([]);
const isLoading = ref(true);

const fetchHandicapStats = async () => {
    try {
        isLoading.value = true;
        const response = await axios.get("/api/report/igame-count-by-f20a");
        handicaps.value = response.data;
    } catch (error) {
        console.error("Error fetching handicap stats:", error);
    } finally {
        isLoading.value = false;
    }
};

onMounted(() => {
    fetchHandicapStats();
});
</script>

<template>
    <Head title="Handicap Report" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Handicap Report
            </h2>
        </template>

        <div class="py-12">
            <div class="w-full mx-auto sm:px-6 lg:px-8 flex items-start">
                <div class="w-[360px] space-y-2 bg-white p-1">
                    <div class="text-xl text-center">S2 Handicap</div>
                    <div
                        v-for="i in [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]"
                        class="space-x-2"
                    >
                        <button
                            class="relative inline-flex items-center bg-transparent hover:bg-gray-500 text-gray-700 font-semibold hover:text-white py-2 border border-gray-500 hover:border-transparent rounded w-16 text-lg justify-center"
                            :class="
                                currentS2 == 0 ? 'bg-gray-500 text-white' : ''
                            "
                            @click="viewport(0 + i)"
                        >
                            {{ i == 0 ? "=" : i }}
                        </button>
                        <button
                            class="relative inline-flex items-center bg-transparent hover:bg-gray-500 text-gray-700 font-semibold hover:text-white py-2 border border-gray-500 hover:border-transparent rounded w-16 text-lg justify-center"
                            :class="
                                currentS2 == 0.25
                                    ? 'bg-gray-500 text-white'
                                    : ''
                            "
                            @click="viewport(0.25 + i)"
                        >
                            {{ i }}+½
                        </button>
                        <button
                            class="relative inline-flex items-center bg-transparent hover:bg-gray-500 text-gray-700 font-semibold hover:text-white py-2 border border-gray-500 hover:border-transparent rounded w-16 text-lg justify-center"
                            :class="
                                currentS2 == 0.5 ? 'bg-gray-500 text-white' : ''
                            "
                            @click="viewport(0.5 + i)"
                        >
                            {{ i == 0 ? "" : i }}½
                        </button>
                        <button
                            class="relative inline-flex items-center bg-transparent hover:bg-gray-500 text-gray-700 font-semibold hover:text-white py-2 border border-gray-500 hover:border-transparent rounded w-16 text-lg justify-center"
                            :class="
                                currentS2 == 0.75
                                    ? 'bg-gray-500 text-white'
                                    : ''
                            "
                            @click="viewport(0.75 + i)"
                        >
                            {{ i == 0 ? "" : i }}½+{{ i + 1 }}
                            <!-- <div
                                class="absolute inline-flex items-center justify-center px-1 h-6 text-xs text-white bg-red-500 border-2 border-white rounded-md -top-2 -end-2"
                            >12</div> -->
                        </button>
                    </div>
                </div>
                <div
                    class="w-full bg-white overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <div class="p-6 text-gray-900">
                        <!-- SVG Icons -->
                        <div class="flex gap-1 mb-4">
                            <SvgIcon name="close-thick" color="#EF4444" />
                            <SvgIcon name="equal" color="#3B82F6" size="24" />
                            <SvgIcon name="circle-outline" color="#10B981" />
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

<style scoped>
.grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
}
</style>
