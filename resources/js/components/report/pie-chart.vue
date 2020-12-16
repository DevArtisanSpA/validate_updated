<script>
//*** Doughnut chat ***//

import { Doughnut } from "vue-chartjs";
import Chart from "chart.js";
export default {
    extends: Doughnut,
    props: ['data', 'title'],
    data: () => ({
        options: {
            legend: { display: false },
            responsive: true,
            maintainAspectRatio: false,
            tooltips: {
                enabled: true,
                callbacks: {
                    label: ((tooltipItems, data) => {
                        return data.datasets[0].label[tooltipItems.index] + ': ' + Math.round(data.datasets[0].data[tooltipItems.index]).toString() + '%'
                    })
                }
            }
        },
        chartData: {},
        complete: 0
    }),
    mounted() {
        const count = this.$props.data.length;
        const complete = this.$props.data.reduce((sum, value) => sum + value.resume.percent, 0) / count;
        const incomplete = 100 - complete;
        const labels = ["Completado", "Faltante"];
        const data = [complete, incomplete];
        const backgroundColor = ['#345086', '#dddddd'];
        this.complete = complete;
        this.chartData = {
            labels,
            datasets: [{
                label: labels,
                backgroundColor,
                data
            }]
        };
        this.addPlugin({
            id: 'my-plugin',
            beforeDraw: this.plugin
        })
        this.renderChart(this.chartData, this.options);
    },
    methods: {
        dynamicColors() {
            var r = Math.floor(Math.random() * 255);
            var g = Math.floor(Math.random() * 255);
            var b = Math.floor(Math.random() * 255);
            return '#' + this.padZero(r) + this.padZero(g) + this.padZero(b);
        },
        padZero(str, len) {
            len = len || 2;
            var zeros = new Array(len).join('0');
            return (zeros + str).slice(-len);
        },
        plugin(chart, value) {
            var width = chart.chart.width;
            var height = chart.chart.height;
            var ctx = chart.chart.ctx;

            ctx.restore();
            var fontSize = (height / 114).toFixed(2);
            ctx.font = fontSize + "em sans-serif";
            ctx.textBaseline = "middle";

            var text = Math.round(this.complete, 1).toString()+'%';
            var textX = Math.round((width - ctx.measureText(text).width) / 2);
            var textY = height / 2;

            ctx.fillText(text, textX, textY);
            ctx.save();
        }
    }
};
 //*** end Doughnut chat ***//
</script>