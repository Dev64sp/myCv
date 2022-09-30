import "./styles/app.css";

import noUiSlider from 'nouislider';
import 'nouislider/dist/nouislider.css';

const sliderPrix = document.getElementById('slidePrix')

if (sliderPrix) {
    const min = document.getElementById('min')
    const max = document.getElementById('max')
    const minValue = Math.floor(parseInt(sliderPrix.dataset.min, 10) / 10) * 10
    const maxValue = Math.ceil(parseInt(sliderPrix.dataset.max, 10) / 10) * 10
    const range = noUiSlider.create(sliderPrix, {
        start: [min.value || minValue, max.value || maxValue],
        connect: true,
        step: 10,
        range: {
            'min': minValue,
            'max': maxValue
        }
    })
    range.on('slide', function (values, handle) {
        if (handle === 0) {
            min.value = Math.round(values[0])
        }
        if (handle === 1) {
            max.value = Math.round(values[1])
        }
    })
    range.on('end', function (values, handle) {
        if (handle===0) {
            min.dispatchEvent(new Event('change'))
        } else {
            max.dispatchEvent(new Event('change'))
        }
    })
}

