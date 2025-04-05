let part2 = 1;
let part3 = 1;
let part4 = 1;

function hide_show_room_part() {
    const buttonsPart2 = document.querySelector('.buttons-part2');
    const roomArrow = document.getElementById('room-arrow');

    if (part2 === 1) {
        buttonsPart2.style.top = "252px";
        roomArrow.style.transform = "rotate(360deg)";
        console.log("vakla");
        part2 = 0;
    } else {
        buttonsPart2.style.top = "160px";
        roomArrow.style.transform = "rotate(270deg)";
        console.log("kim");
        part2 = 1;
    }
}

function show_hide_deals(){
    const buttonsPart3 = document.querySelector('.buttons-part3');
    const dealArrow = document.getElementById('deal-arrow');

    if (part3 === 1) {
        buttonsPart3.style.top = "230px";
        dealArrow.style.transform = "rotate(360deg)";
        console.log("vakla");
        part3 = 0;
    } else {
        buttonsPart3.style.top = "55px";
        dealArrow.style.transform = "rotate(270deg)";
        console.log("kim");
        part3 = 1;
    }
}

function show_hide_users(){
    const buttonsPart4 = document.querySelector('.buttons-part4');
    const userArrow = document.getElementById('user-arrow');

    if (part4 === 1) {
        buttonsPart4.style.top = "185px";
        userArrow.style.transform = "rotate(360deg)";
        console.log("vakla");
        part4 = 0;
    } else {
        buttonsPart4.style.top = "105px";
        userArrow.style.transform = "rotate(270deg)";
        console.log("kim");
        part4 = 1;
    }
}
