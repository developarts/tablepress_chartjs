

window.chartColors = {
	red: {
        line: 'rgb(255, 99, 132)',
        bg: 'rgb(255, 99, 132, .5)',
    },
    blue: {
        line: 'rgb(54, 162, 235)',
        bg: 'rgb(54, 162, 235, .5)',
    },
    orange: {
        line: 'rgb(255, 159, 64)',
        bg: 'rgb(255, 159, 64, .5)',
    },
    yellow: {
        line: 'rgb(255, 205, 86)',
        bg: 'rgb(255, 205, 86, .5)',
    },
    green: {
        line: 'rgb(75, 192, 192)',
        bg: 'rgb(75, 192, 192, .5)',
    },
    purple: {
        line: 'rgb(153, 102, 255)',
        bg: 'rgb(153, 102, 255, .5)',
    },
    grey: {
        line: 'rgb(201, 203, 207)',
        bg: 'rgb(201, 203, 207, .5)',
    },
    black: {
        line: 'rgb(0, 0, 0)',
        bg: 'rgb(0, 0, 0, .5)',
    },
};

tpc_axis_number_format = function(value, index, values) {
   if (isNaN(value)) {
       return value;
   } else {
       return Intl.NumberFormat("es-MX").format((value));
   }
};
