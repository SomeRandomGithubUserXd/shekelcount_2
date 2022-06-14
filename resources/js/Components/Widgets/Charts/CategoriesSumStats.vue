<template>
    <div class="flex justify-center">
        <Doughnut
            :class="[widthClass]"
            :chart-options="{responsive: true}"
            :chart-data="chartData"
        />
    </div>
</template>

<script>
import {Doughnut} from 'vue-chartjs'
import {
    Chart as ChartJS,
    ArcElement,
    Title
} from 'chart.js'

ChartJS.register(
    ArcElement,
    Title
)


export default {
    components: {
        Doughnut
    },
    computed: {
        widthClass() {
            let widthClass = 'w-3/4'
            if (isMobile()) {
                widthClass = 'w-full'
            }
            return widthClass
        }
    },
    props: {
        labels: Array,
        data: Array,
        colors: Array
    },
    data() {
        return {
            chartData: {
                labels: this.labels,
                datasets: [
                    {
                        label: 'Categories sum',
                        data: this.data,
                        borderColor: this.colors,
                        backgroundColor: this.colors,
                        fill: false
                    }
                ]
            },
        }
    },
}
</script>
