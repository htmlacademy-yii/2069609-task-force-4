const lat = document.querySelector('#lat').value;
const long = document.querySelector('#long').value;

ymaps.ready(init);

function init(){
    const myMap = new ymaps.Map("map", {
        center: [lat, long],
        zoom: 10
    });


}