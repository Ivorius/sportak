jQuery(function($) {
$('#graph-1').highcharts({
			chart: {
				type: 'line'
			},
			title: {
				text: "Skok daleký"
			},
			subtitle: {
				text: 'Josef Novák'
			},
			xAxis: {
				categories: ["15.04.2014", "22.06.2014", "29.09.2014", "01.03.2015", "15.03.2015"]
			},
			yAxis: {
				title: {
					text: "Skok daleký" + ' (' + "m" + ')'
				}
			},
			tooltip: {
				valueSuffix: "m"
			},
			plotOptions: {
				line: {
					dataLabels: {
						enabled: true
					},
					enableMouseTracking: true
				}
			},
			series: [{
					name: 'hodnota',
					data: [3.4, 3.6, 3.1, 3.8, 4.2]
				}]
		});

		$('#graph-2').highcharts({
			chart: {
				type: 'line'
			},
			title: {
				text: "Váš sport"
			},
			xAxis: {
				categories: ["04.03.2014", "06.03.2014", "17.04.2014", "18.08.2014", "21.08.2014", "18.10.2014"]

			},
			yAxis: {
				title: {
					text: "Název sportu" + ' (' + "cm" + ')'
				}
			},
			tooltip: {
				valueSuffix: "cm"
			},
			plotOptions: {
				line: {
					dataLabels: {
						//enabled: true
					},
					enableMouseTracking: true
				}
			},
			series: [{
					name: "Ivo Lakomý",
					data: [48.3, 63.7, 83.9, 33.3, 98.6, 69.4]
				}, {
					name: "Petr Pavel",
					data: [97.4, 93.3, 33.3, 75.7, 24.8, 63.7]
				}, {
					name: "Ondra Famouz",
					data: [11.9, 98.4, 87.6, 13.1, 72.8, 19.4]
				}, {
					name: "František Straka",
					data: [41.8, 87.7, 49.5, 14.6, 39.2, 34.7]
				}, {
					name: "Jiří Potrhlý",
					data: [72.5, 99.9, 23.4, 19.5, 41.5, 43.2]
				}]
		});
});