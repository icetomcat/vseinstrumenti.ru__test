(function ($) {
	app = this;
	var self = this;

	var taskStatus = {
		"SUCCEEDED": "bar",
		"FAILED": "bar-failed",
		"RUNNING": "bar-running",
		"KILLED": "bar-killed"
	};

	if (typeof tasks !== 'undefined' && tasks.length > 0) {
		tasks.sort(function (a, b) {
			return a.endDate - b.endDate;
		});
		var maxDate = tasks[tasks.length - 1].endDate;
		tasks.sort(function (a, b) {
			return a.startDate - b.startDate;
		});
		var minDate = tasks[0].startDate;

		var format = "%d.%m";

		var gantt = d3.gantt().taskTypes(taskNames).regions(regions).taskStatus(taskStatus).tickFormat(format).timeDomain([minDate, maxDate]).timeDomainMode("fixed");
		gantt(tasks);
	}
	
	app.schedule = {
		viewData: null
	};

	app.schedule.addNewItemFormUpdate = function () {
		var strDate = $("#addNewItem-input__start").val();
		var dateParts = strDate.split(".");

		var region;
		var region_id = $("#addNewItem-input__region").val();

		for (var i in regions)
		{
			if (regions[i].id == region_id)
			{
				region = regions[i];
				break;
			}
		}

		var regionDate = new Date(dateParts[2], (dateParts[1] - 1), parseInt(dateParts[0]) + Math.round(region.period / 2));
		var backDate = new Date(dateParts[2], (dateParts[1] - 1), parseInt(dateParts[0]) + region.period);

		$('#addNewItem-info__region-date').html(regionDate.getDate() + "." + (regionDate.getMonth() + 1) + "." + regionDate.getFullYear());
		$('#addNewItem-info__back-date').html(backDate.getDate() + "." + (backDate.getMonth() + 1) + "." + backDate.getFullYear());
	}

	app.schedule.create = function (data) {
		$.ajax({"url": root_path + "/schedule/", method: "POST", data: data})
				.done(function () {
					$('#addNewItem').modal('hide');
					app.schedule.view();
				})
				.error(function (response, status) {
					alert(response.responseJSON.body);
				});
	}

	app.schedule.view = function (data) {
		if (typeof data === "undefined")
		{
			data = app.schedule.viewData;
		}
		else
		{
			app.schedule.viewData = data;
		}
		$.ajax({"url": root_path + "/", data: data})
				.done(function (response) {
					for (var i in response.schedule) {
						response.schedule[i].startDate = new Date.createFromMysql(response.schedule[i].start);
						response.schedule[i].endDate = new Date.createFromMysql(response.schedule[i].end);
					}
					tasks = response.schedule;
					if (tasks.length > 0)
					{
						tasks.sort(function (a, b) {
							return a.endDate - b.endDate;
						});
						maxDate = tasks[tasks.length - 1].endDate;
						tasks.sort(function (a, b) {
							return a.startDate - b.startDate;
						});
						minDate = tasks[0].startDate;
						gantt.timeDomain([minDate, maxDate]);
						gantt.redraw(tasks);
					}
				});
	}

	app.schedule.addNewItemFormUpdate();

	$("#addNewItem-input__region").on("change", app.schedule.addNewItemFormUpdate);
	$("#addNewItem-input__start").on("change", app.schedule.addNewItemFormUpdate);

	app.schedule.addNewItemFormUpdate();

	$("input[data-type='date']").datepicker({
		format: "dd.mm.yyyy",
		todayBtn: true,
		clearBtn: true,
		language: "ru"
	});

	//gantt.redraw(tasks);

})(jQuery);
