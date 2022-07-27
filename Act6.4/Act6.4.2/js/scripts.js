window.onload = () => {
    document.querySelectorAll("#palette div").forEach(element => {
element.style.backgroundColor = element.dataset.color
element.addEventListener("click", () => {
    canvas.setColor(element.dataset.color)
})
    })

let canvas = new Dessin("#feuille")
}