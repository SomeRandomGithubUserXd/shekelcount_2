<template>
    <Head title="Dashboard"/>

    <Authenticated>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>

        <div class="p-6 bg-white border-b border-gray-200">
            <div class="flex flex-col">
                <h2 class="text-lg font-semibold">Quick Actions</h2>
                <quick-actions
                    :categories="$page.props.categories"
                    class="mt-2"/>
            </div>
            <div class="flex flex-col mt-5">
                <h2 class="text-lg font-semibold">Entry Stats for Current Year</h2>
                <current-year-entries
                    :labels="charts.current_year_entries.labels"
                    :data="charts.current_year_entries.data"
                    class="mt-2"/>
            </div>
            <div class="flex flex-col mt-5">
                <h2 class="text-lg font-semibold">Categories Sum Stats</h2>
                <categories-sum-stats
                    :labels="charts.categories_sum_stats.labels"
                    :data="charts.categories_sum_stats.data"
                    :colors="charts.categories_sum_stats.colors"
                    class="mt-2"/>
            </div>
        </div>
    </Authenticated>
</template>

<script>
import Authenticated from '@/Layouts/Authenticated.vue';
import {Head} from '@inertiajs/inertia-vue3';
import CurrentYearEntries from "@/Components/Widgets/Charts/CurrentYearEntries";
import QuickActions from "@/Components/Widgets/QuickActions";
import CategoriesSumStats from "@/Components/Widgets/Charts/CategoriesSumStats";

export default {
    components: {
        CategoriesSumStats,
        QuickActions,
        Head,
        Authenticated,
        CurrentYearEntries
    },
    computed: {
        charts() {
            return this.$page.props.charts
        }
    }
}
</script>
