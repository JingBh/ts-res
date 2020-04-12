require("./bootstrap");

function setImage(now, last) {
    if (last || last === 0) images[last].hide();
    images[now].show();
}

const images = [];
let image = 0;

for (let i = 1; i <= 17; i ++) {
    let url = require(`../images/equestria/${i}.png`);
    let ele = $('<div class="bg"></div>');
    ele.css("background-image", `url(${url})`);
    $("body").append(ele);
    images.push(ele);
}

document.body.onwheel = function(event) {
    let delta = event.deltaY;
    let last = image;
    let now = image;
    let direction = delta > 0 ? "+" : "-";
    if (direction === "+") now ++;
    if (direction === "-") now --;
    if (now > 16) now = 16;
    if (now < 0) now = 0;
    setImage(now, last);
    image = now;
};

setImage(image);
