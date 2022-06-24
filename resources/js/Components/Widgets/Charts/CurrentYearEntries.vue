<template>
    <div>
        <Line
            :chart-options="{responsive: true}"
            :chart-data="chartData"
            :height="chartHeight"
        />
    </div>
</template>

<script>
import {Line} from 'vue-chartjs'
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement
} from 'chart.js'
import {isMobile} from "@/Traits/InteractsWithWindow";

ChartJS.register(
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement
)


export default {
    components: {
        Line
    },
    computed: {
        chartHeight() {
            let height = 150
            if (isMobile()) {
                height = 300
            }
            return height
        }
    },
    props: {
        labels: Array,
        data: Array,
    },
    data() {
        return {
            chartData: {
                labels: this.labels,
                datasets: [
                    {
                        label: `Entry sum for ${new Date().getFullYear()}`,
                        data: this.data,
                        borderColor: 'rgb(67, 56, 202)',
                        backgroundColor: 'rgb(67, 56, 202)',
                        fill: false
                    }
                ]
            },
        }
    },
}
</script>
