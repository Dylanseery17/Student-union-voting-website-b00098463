var dates = []
function last7Days(d) {
    d = +(d || new Date()), i=7;
    while (i--) {
        dates.push(formatDate(new Date(d-=8.64e7)));
    }
    return dates;
}


// Return date string in mm/dd/y format
function formatDate(d) {
    //add 0 to start of months if less then 10th month
    function z(n){
        return (n<10?'0':'')+ +n;
    }
    return  d.getFullYear() + '/' + z(d.getMonth() + 1) + '/' +  z(d.getDate());
}

function f(arr) {
    //Gets last 7 days
    last7Days();
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'bar',
        data: {
            //last 7 days
            labels: [dates[0], dates[1], dates[2], dates[3], dates[4], dates[5], dates[6]],
            datasets: [{
                label: '# of Users Created',
                //last 7 days values
                data: [arr[0], arr[1], arr[2], arr[3], arr[4], arr[5], arr[6]],
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
}