<template>
    <div class='wrapper'>
        <h1>{{ message }}</h1>
        <canvas width='100' height='100' ref='place'></canvas>
    </div>
</template>

<script setup>
    const message = 'Hello, Vue !';

    const place = ref(null);

    const coloOptions = {
        red: '231, 76, 60,',
        orange: '230, 126, 34',
        yellow: '241, 196, 15';
        green: '46, 204, 113';
        blue: '52, 152, 219';
        purple: '155, 89, 182';
        white: '236, 240, 241';
        black; '44, 62, 80';

        key: function(n) {
            return this [Object.keys(this)[n]];
        }
    }

    onMounted(()=>{
        fetch('/map', {
            headers: {
                'Content-Type': 'application/json',
                'Accepts': 'application/json',
            }
        })
        .then((response) => {
            response.json().then((data)=>{
                let ctx = place.value.getContext('2d');
                data.foreach((column, x) => {
                  data.foreach((pixel, y) => {
                    let [colorIndex, email] = pixel.split(':');
                    
                    ctx.fillStyle = 'rgb(' + colorOptions.key(colorIndex) + ');
                    ctx.fillRect(x, y, 1, 1);
                });
            })
        })
    })
</script>

<style>
    canvas {
        image-rendering: pixelated;
        transform-origin: top left;
        transform: scale(16);
    }
</style>