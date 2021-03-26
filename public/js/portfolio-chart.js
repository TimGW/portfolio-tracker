var doughnutChartData = {}

const colorArray = [
    '#075E54', '#128C7E', '#25D366', '#DCF8C6',
    '#003B73', '#0074B7', '#60A3D9', '#BFD7ED',
    '#721121', '#A5402D', '#F15156', '#FFC07F',
];

function setData(labels, data) {
    doughnutChartData = {
        labels: labels,
        datasets: [{
            data: data,
            backgroundColor: colorArray,
            borderWidth: 2
        }]
    }
}


const handleChartJs = function () {
    var ctx = document.getElementById('doughnut-chart').getContext('2d');
    window.myDoughnut = new Chart(ctx, {
        type: 'doughnut',
        data: doughnutChartData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            legend: {
                position: 'right',
                labels: {
                    usePointStyle: true,
                    boxWidth: 8,
                    fontSize: 14
                }
            },
            tooltips: {
                callbacks: {
                    label: function (tooltipItem, data) {
                        let label = data.labels[tooltipItem.index];
                        let value = data.datasets[tooltipItem.datasetIndex].data[tooltipItem.index];
                        let roundedValue = Math.round(value * 100) / 100
                        return ' ' + label + ': ' + roundedValue + '%';
                    }
                }
            }
        }
    });
};

const ChartJs = function () {
    "use strict";
    return {
        //main function
        init: function () {
            handleChartJs();
        }
    };
}();

$(function () {
    ChartJs.init();
});
