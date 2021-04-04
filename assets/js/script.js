document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("myModal");
    const close = document.getElementById("close");
    close.addEventListener("click", () => {
        modal.style.display = "none";
    })

});

function showLectureDetail(name, lecture_id) {
    const url = 'api/attendanceApi.php';
    const request = new Request(url, {
        method: 'POST',
        body: JSON.stringify({
            name: name,
            lecture_id: lecture_id
        }),
        headers: {
            'Content-type': 'application/json; charset=UTF-8'
        }
    });

    fetch(request)
        .then((response) => response.json())
        .then((data) => {
            const modal = document.getElementById("myModal");
            modal.style.display = "block";

            const modalTitle = document.getElementById("modal-title");
            modalTitle.innerHTML = data.name;

            const lectureTitle = document.getElementById("lecture-title");
            lectureTitle.innerHTML += data.lecture_id;

            const attendance = document.getElementById("attendance-body");
            attendance.innerHTML = " ";
            Object.keys(data.result).forEach(function (key) {
                let innerHtml = "<tr>" +
                    "<td>" +
                    key +
                    "</td>" +
                    "<td>" +
                    data.result[key] +
                    "</td>" +
                    "</tr>";
                attendance.innerHTML += innerHtml;
            });
        });
}
