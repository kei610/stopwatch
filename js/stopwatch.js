
'use strict';

const timerLabel = document.getElementById('timerLabel');
const resetBtn = document.getElementById('resetBtn');
const startBtn = document.getElementById('startBtn');
const stopBtn = document.getElementById('stopBtn');

let status = 0;
let time = 0;

function start(){
    status = 1;
    startBtn.disabled = true;

    timer();
}
function stop(){
    status = 0;
    startBtn.disabled = false;
}
function reset(){
    status = 0;
    time = 0;

    timerLabel.innerHTML = '00:00:00'
    startBtn.disabled = false;
}

function timer(){
    if(status == 1){
        setTimeout(function(){
            time++;

            // 分・秒・ミリ秒を計算
            let min = Math.floor(time/100/60);
            let sec = Math.floor(time/100);
            let mSec = time % 100;

            // 分が１桁の場合は、先頭に０をつける
            if (min < 10) min = "0" + min;

            // 秒が６０秒以上の場合 例）89秒→29秒にする
            if (sec >= 60) sec = sec % 60;

            // 秒が１桁の場合は、先頭に０をつける
            if (sec < 10) sec = "0" + sec;

    // ミリ秒が１桁の場合は、先頭に０をつける
            if (mSec < 10) mSec = "0" + mSec;

            // タイマーラベルを更新
            timerLabel.innerHTML = min + ":" + sec + ":" + mSec;

            // 再びtimer()を呼び出す
            timer();
        }, 10);
    }
}

document.onkeydown = function(event) { 
    if (event) {
        if (event.keyCode == 32 || event.which == 32) {
            if(status == 1) {
                stop();
            } else if (status == 0) {
                start();
            }
        }
    }
};