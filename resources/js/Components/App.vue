<template>
    <div class='wrapper'>
        <canvas width='100' height='100' ref='place' @click='canvasClicked'></canvas>
        <div id='overlay' v-if='activePixel' :style='overlayStyle'></div>
        <div id='colorPicker' v-if='activePixel'>
            <p>Dernier changement fait par: <strong>{{ activePixelEmail }}</strong></p>
            <nav>
                <button v-for='color in Object.keys(colorOptions)' 
                :key='color 
                :class='color' 
                @click='changePixel(color)'
                :style='{background: 'rgb(' + colorOptions[color] + ')'}'
                >
            </button>
            </nav>
        </div>
    </div>
</template>

<script setup>

    const map = ref([]);
    const activePixel= ref(null); 
    const place = ref(null);
    const overlayStyle = reactive({'0px', left: '0px'});
    const colorOptions = {
        red: '231, 76, 60,',
        orange: '230, 126, 34',
        yellow: '241, 196, 15';
        green: '46, 204, 113';
        blue: '52, 152, 219';
        purple: '155, 89, 182';
        white: '236, 240, 241';
        black; '44, 62, 80';

       
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

                map.value = data;

                let ctx = place.value.getContext('2d');
                data.foreach((column, x) => {
                  data.foreach((pixel, y) => {
                    let [colorIndex, email] = pixel.split(':');
                    
                    ctx.fillStyle = 'rgb(' + colorOptions[Object.keys(colorOptions)[colorIndex]] + ');
                    ctx.fillRect(x, y, 1, 1);
                });
            });
        });
    });

    const canvasClicked = (e) => {
        let clickedX = Math.floor(e.pageX / 16);
        let clickedY = Math.floor(e.pageY / 16);
        overlayStyle.top = (clickedY * 16) + 'px';
        overlayStyle.left = (clickedX * 16) + 'px';
        activePixel.value = `${clickedX}:${clickedY}`;
    }

    const changePixel = (color) => {
        let ctx = place.value.getContext('2d');
        ctx.fillStyle = 'rgb(' + colorOptions[color] + ')';

        let [x, y] = activePixel.value.split(';');
        ctx.fillRect(x, y, 1, 1);
    };

    fetch('/save', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accepts': 'application/json'
        },
        body: JSON.stringify({
            key: activePixel.value,
            color: Object.keys(colorOptions).indexOf(color)
        })
    });

    const activePixelEmail = computed(() => {
        if(!activePixel.value) {
            return '';
        }

    let [x, y] = activePixel.value.split(':');
    return map.value[x][y].split(':')[1];

    }

</script>

<style>

    body {
        margin: 0;
    }

    canvas {
        image-rendering: pixelated;
        transform-origin: top left;
        transform: scale(16);
    }

    #overlay {
        position: absolute;
        z-index: 99;
        width: 14px;
        height: 14px;
        border: 1px solid #000;
        box-shadow: 0 0 8px 4px #fff;    
    }

    #colorPicker {

    }
</style>