colorarr = ["red", "blue", "green", "blue", "red", "blue","red", "blue", "green", "blue", "red", "blue"]

function f(arr , label) {
    console.log(label);
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            //last 7 days
            labels: getlabelData(label),
            datasets: [{
                label: '# of Votes',
                //last 7 days values
                data: getData(arr),
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)',
                    'rgba(255, 206, 86, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });

    var bar = document.getElementById('myBarChart').getContext('2d');
    var myBarChart = new Chart(bar, {
        type: 'bar',
        data: {
            labels:  getlabelData(label),
            datasets: [
                {
                    label: '# of Votes',
                    data: getData(arr),
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(153, 102, 255, 0.2)',
                        'rgba(255, 159, 64, 0.2)',
                        'rgba(255, 206, 86, 0.2)'],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)',
                        'rgba(153, 102, 255, 1)',
                        'rgba(255, 159, 64, 1)',
                        'rgba(255, 206, 86, 1)'],
                    borderWidth: 1,
                }
            ]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}

function L(label , values , ans , days , data) {
    console.log(label);
    console.log(values);
    console.log(ans);
    console.log(days);
    var ctx = document.getElementById('myLineChart').getContext('2d');
    var myLineChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: label,
            datasets: getDataset(data , values ) ,
        }
    });
}

function getDataset(label , values ) {
    var data = splitUp(values , label.length);

    var arr = [];
    var myColors = [
        'rgba(255, 99, 132, 1)',
        'rgba(54, 162, 235, 1)',
        'rgba(255, 206, 86, 1)',
        'rgba(75, 192, 192, 1)',
        'rgba(153, 102, 255, 1)',
        'rgba(255, 159, 64, 1)',
        'rgba(255, 206, 86, 1)']; // Define your colors
    var v = 0;
    for(var i=0; i < label.length; i++) {
        arr.push( {
            label: label[i].Choice,
            fill: false,
            backgroundColor: myColors[i],
            borderColor: myColors[i],
            data: data[i]
        });
    }
    return arr;
}

function splitUp(arr, n) {
    var rest = arr.length % n,
        restUsed = rest,
        partLength = Math.floor(arr.length / n),
        result = [];

    for(var i = 0; i < arr.length; i += partLength) {
        var end = partLength + i,
            add = false;

        if(rest !== 0 && restUsed) {
            end++;
            restUsed--;
            add = true;
        }

        result.push(arr.slice(i, end));

        if(add) {
            i++;
        }
    }
    console.log(result);
    return result;
}


function getlabelData(value) {
    var data = []

    for(var i=0; i < value.length; i++) {
        data.push(value[i].Choice)
    }

    return data
}

function getData(value) {
    var data = []

    for(var i=0; i < value.length; i++) {
        data.push(value[i])
    }

    return data
}

function getColor(value , colorarr) {
    var data = []

    for(var i=0; i < value.length; i++) {
        data.push(colorarr[i])
    }

    return data
}