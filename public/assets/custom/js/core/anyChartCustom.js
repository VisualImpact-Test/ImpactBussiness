var anyChartCustom = {

	pieCharts: [],
	columnCharts: [],
	barCharts: [],

	dataPieCharts: [],
	dataColumnCharts: [],
	dataBarCharts: [],

	setPieCharts: function (graficosPieAC = this.dataPieCharts) {
		$.each(graficosPieAC, function (indice, value) {
			anyChartCustom.pieCharts[indice] = anychart.pie(value.data);
			anyChartCustom.pieCharts[indice].animation(true);
			anyChartCustom.pieCharts[indice].title("<strong>" + value.title + "</strong>");
			anyChartCustom.pieCharts[indice].title().fontSize(12);
			anyChartCustom.pieCharts[indice].title().fontColor('#000');
			anyChartCustom.pieCharts[indice].title().useHtml(true);

			anyChartCustom.pieCharts[indice].legend(true);
			anyChartCustom.pieCharts[indice].legend().title(false);
			anyChartCustom.pieCharts[indice].legend().fontColor('#000');
			anyChartCustom.pieCharts[indice].legend().fontSize(10);
			anyChartCustom.pieCharts[indice].legend().title().padding([0, 0, 10, 0]);
			anyChartCustom.pieCharts[indice].legend().position('bottom');
			anyChartCustom.pieCharts[indice].legend().itemsLayout('horizontal');
			anyChartCustom.pieCharts[indice].legend().align('center');

			anyChartCustom.pieCharts[indice].labels()
				.fontSize(10)
				.fontColor('#000');
			anyChartCustom.pieCharts[indice].labels().position("outside");
			anyChartCustom.pieCharts[indice].connectorStroke({ color: "#595959", thickness: 1, dash: "1 1" });

			anyChartCustom.pieCharts[indice].tooltip()
				.useHtml(true)
				.width(300)
				.title({ fontColor: '#FFFFFF' })
				.format(function () {
					return eval(value.tooltipHtml);
				});
			anyChartCustom.pieCharts[indice].tooltip().background()
				.enabled(true)
				.fill('#FFFFFF')
				.stroke('#ccc')
				.corners(3)
				.cornerType('round');

			anyChartCustom.pieCharts[indice].container("containerPie" + indice);
			anyChartCustom.pieCharts[indice].draw();
		});
	},

	setColumnCharts: function (graficosColumnAC = this.dataColumnCharts) {
		$.each(graficosColumnAC, function (indice, value) {
			anyChartCustom.columnCharts[indice] = anychart.column();
			anyChartCustom.columnCharts[indice].animation(true);

			anyChartCustom.columnCharts[indice].title("<strong>" + value.title + "</strong>");
			anyChartCustom.columnCharts[indice].title().fontSize(12);
			anyChartCustom.columnCharts[indice].title().fontColor('#000');
			anyChartCustom.columnCharts[indice].title().useHtml(true);

			anyChartCustom.columnCharts[indice].column(value.data);

			anyChartCustom.columnCharts[indice].labels()
				.enabled(true)
				.fontSize(10)
				.fontColor('#000');

			anyChartCustom.columnCharts[indice].tooltip()
				.useHtml(true)
				.width(250)
				.title({ fontColor: '#7c868e' })
				.format(function () {
					return eval(value.tooltipHtml);
				});

			// anyChartCustom.columnCharts[indice].xAxis().labels().rotation(-60);
			anyChartCustom.columnCharts[indice].xAxis()
				.labels()
				.width(45)
				.height(50)
				.fontSize(10)
				.fontColor('#000').textOverflow("...");

			anyChartCustom.columnCharts[indice].tooltip().background()
				.enabled(true)
				.fill('#fff')
				.stroke('#ccc')
				.corners(3)
				.cornerType('round');
			anyChartCustom.columnCharts[indice].container("containerColumn" + indice);
			anyChartCustom.columnCharts[indice].legend(false);
			anyChartCustom.columnCharts[indice].draw();
		});
	},

	setBarCharts: function (graficosBarAC = this.dataBarCharts) {

		$.each(graficosBarAC, function (indice, value) {
			anyChartCustom.barCharts[indice] = anychart.bar();
			anyChartCustom.barCharts[indice].animation(true);
			anyChartCustom.barCharts[indice].padding([10, 40, 5, 20]);

			anyChartCustom.barCharts[indice].title("<strong>" + value.title + "</strong>");
			anyChartCustom.barCharts[indice].title().fontSize(12);
			anyChartCustom.barCharts[indice].title().fontColor('#000');
			anyChartCustom.barCharts[indice].title().useHtml(true);

			anyChartCustom.barCharts[indice].bar(value.data);

			anyChartCustom.barCharts[indice]
				.tooltip()
				.useHtml(true)
				.position('right')
				.anchor('left-center')
				.offsetX(5)
				.offsetY(0)
				.titleFormat('{%X}')
				.format(function () {
					return eval(value.tooltipHtml);
				});

			// set yAxis labels formatter
			anyChartCustom.barCharts[indice].yAxis().labels().format('{%Value}{groupsSeparator: }');
			// set titles for axises
			// anyChartCustom.barCharts[indice].xAxis().title('Products by Revenue');
			anyChartCustom.barCharts[indice].yAxis().title('Porcentaje');
			anyChartCustom.barCharts[indice].interactivity().hoverMode('by-x');
			anyChartCustom.barCharts[indice].tooltip().positionMode('point');
			// set scale minimum
			anyChartCustom.barCharts[indice].yScale().minimum(0);

			anyChartCustom.barCharts[indice].container('containerBar' + indice);
			anyChartCustom.barCharts[indice].draw();
		});
	}
}