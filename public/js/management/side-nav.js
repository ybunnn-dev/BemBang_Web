let part2 = 1;
let part3 = 1;
let part4 = 1;

function hide_show_room_part() {
    const buttonsPart2 = document.querySelector('.buttons-part2');
    const roomArrow = document.getElementById('room-arrow');
    const buttonsPart3 = document.querySelector('.buttons-part3');
    const dealArrow = document.getElementById('deal-arrow');
    const buttonsPart4 = document.querySelector('.buttons-part4');
    const userArrow = document.getElementById('user-arrow');

    
    part2 = buttonsPart2.style.top === '160px' ? 1 : 0;

    if (part2 === 1) {
        buttonsPart2.style.top = "252px";
        roomArrow.style.transform = "rotate(360deg)";
        buttonsPart3.style.top = "55px";
        dealArrow.style.transform = "rotate(270deg)";
        buttonsPart4.style.top = "105px";
        userArrow.style.transform = "rotate(270deg)";
        part2 = 0;
        part3 = 1;
        part4 = 1;
    } else {
        buttonsPart2.style.top = "160px";
        roomArrow.style.transform = "rotate(270deg)";
        part2 = 1;
    }
}

function show_hide_deals(){
    const buttonsPart3 = document.querySelector('.buttons-part3');
    const dealArrow = document.getElementById('deal-arrow');

    part3 = buttonsPart3.style.top === '55px' ? 1 : 0;

    if (part3 === 1) {
        buttonsPart3.style.top = "190px";
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
        part4 = 0;
    } else {
        buttonsPart4.style.top = "105px";
        userArrow.style.transform = "rotate(270deg)";
        part4 = 1;
    }
}
