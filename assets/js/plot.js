document.addEventListener("DOMContentLoaded", () => {
    const url = 'api/graphApi.php';

    const request = new Request(url, {
        method: 'POST',
        body: '',
        headers: {
            'Content-type': 'application/json; charset=UTF-8'
        }
    });

    fetch(request)
        .then((response) => response.json())
        .then((data) => {

            let lectures = [];
            let lectureAttendances = [];

            Object.keys(data.result).forEach(function (key) {
                lectures.push(key);
                lectureAttendances.push(data.result[key]);
            });
            let plotData = [
                {
                    x: lectures,
                    y: lectureAttendances,
                    type: 'bar'
                }
            ];

            Plotly.newPlot('plot', plotData);
        });

});