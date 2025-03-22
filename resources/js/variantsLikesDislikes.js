const token = document.querySelector('meta[name="csrf-token"]').content;
const baseUrl = document.querySelector('meta[name="base-url"]').content;

const likeBtn = document.querySelector('#like-button');
console.log(likeBtn);
console.log(likeBtn.dataset.variant);

likeBtn.addEventListener("click", likeVariant);

function likeVariant() {
    console.log("try fetch");
    try {
        fetch(baseUrl + "/api/variants/" + likeBtn.dataset.variant + "/like", {
            headers: {
                'X-Request-With': 'XMLHttpRequest',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token,
            },
            method: "POST",
            body: JSON.stringify({
                songVariant_id: likeBtn.dataset.variant
            }),
        }).then(response => {
            return response.json()
        }).then((data) => {
            console.log(data);
        });
    } catch (error) {
        console.log(error)
    }
}
