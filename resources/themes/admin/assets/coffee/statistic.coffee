Statistic = {}

Statistic.pieChartOptions =
  segmentShowStroke: true,
  segmentStrokeColor: "#fff",
  segmentStrokeWidth: 1,
  percentageInnerCutout: 50,
  animationSteps: 20,
  animationEasing: "easeOutBounce",
  animateRotate: true,
  animateScale: false,
  responsive: true,
  maintainAspectRatio: false,
  legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<segments.length; i++){%><li><span style=\"background-color:<%=segments[i].fillColor%>\"></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>",
  tooltipTemplate: "<%=value %> <%=label%>"

Statistic.getDayColor = (index) ->
  console.log(index)
  
  switch (index)
    when 0
      color = '#dd4b39'
    when 1
      color = '#00c0ef'
    else
      color = '#00a65a'

  return color

$(document).on 'ready', () ->
  Statistic.$count_chart = $('#days_statistic_chart_count')
  Statistic.$sum_chart = $('#days_statistic_chart_sum')

  if Statistic.$count_chart.length && Statistic.$sum_chart.length
    data1 = []
    data2 = []

    i = 0

    $.each Statistic.data, (index, item) ->
      data1.push
        label: item.title
        value: item.count
        color: Statistic.getDayColor(i)
        highlight: Statistic.getDayColor(i)

      data2.push
        label: window.currency + ' ' + item.title
        value: item.sum
        color: Statistic.getDayColor(i)
        highlight: Statistic.getDayColor(i)

      i++

    chartCanvas = Statistic.$count_chart.get(0).getContext('2d')
    chart_count = new Chart(chartCanvas)
    chart_count.Pie(data1, Statistic.pieChartOptions)

    chartCanvas = Statistic.$sum_chart.get(0).getContext('2d')
    chart_sum = new Chart(chartCanvas)
    chart_sum.Pie(data2, Statistic.pieChartOptions)