document.addEventListener("DOMContentLoaded", function () {
    const cardHeader = document.querySelector("#card-header");

    const elementos = cardHeader.querySelectorAll("button");

    for (let index = 0; index < elementos.length; index++) {
        const element = elementos[index];

        element.addEventListener("click", function () {
            getVideo(element.value)
        });
    }

    const siteUrl = "http://127.0.0.1:8000"

    async function getVideo(id) {
        try {
            const response = await fetch(siteUrl + '/api/video/' + id);
            
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            
            const data = await response.json();
            check_data(data);
        } catch (error) {
            /* console.error('There was a problem with the fetch operation:', error); */
        }
    }

    function check_data(data) {
        let video_div = document.querySelector("#video_container");
        video_div.innerHTML = "";

        if (data.type === "embed") {
            video_div.classList.add("ratio");
            video_div.classList.add("ratio-16x9");
            video_div.innerHTML = data.embed_code;

        } else {
            video_div.classList.remove("ratio");
            video_div.classList.remove("ratio-16x9");
            let videoElement = document.createElement("video");
            videoElement.src = siteUrl + "/storage/" + data.video_src;

            videoElement.controls = true;
            video_div.appendChild(videoElement);
            let player = new Plyr(videoElement);
        }
    }
    if (elementos.length > 0) {
        getVideo(elementos[0].value)
    }
});