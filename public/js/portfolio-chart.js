var doughnutChartData = {}

function setData(labels, data, colors) {
    doughnutChartData = {
        labels: labels,
        datasets: [{
            data: data,
            backgroundColor: colors,
            borderWidth: 2
        }]
    }
}

var handleChartJs = function () {
    var ctx = document.getElementById('doughnut-chart').getContext('2d');
    window.myDoughnut = new Chart(ctx, {
        type: 'doughnut',
        data: doughnutChartData,
        options: {
            legend: {
                position: 'right'
            }
        }
    });
};

var ChartJs = function () {
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