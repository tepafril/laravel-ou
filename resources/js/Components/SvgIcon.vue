<script setup>
import { computed } from 'vue';

const props = defineProps({
    name: {
        type: String,
        required: true,
        validator: (value) => ['close-thick', 'equal', 'circle-outline'].includes(value)
    },
    color: {
        type: String,
        default: 'currentColor'
    },
    size: {
        type: [Number, String],
        default: 24
    }
});

const svgPath = computed(() => {
    const paths = {
        'close-thick': 'M20 6.91L17.09 4L12 9.09L6.91 4L4 6.91L9.09 12L4 17.09L6.91 20L12 14.91L17.09 20L20 17.09L14.91 12L20 6.91Z',
        'equal': 'M19,10H5V8H19V10M19,16H5V14H19V16Z',
        'circle-outline': 'M12,20A8,8 0 0,1 4,12A8,8 0 0,1 12,4A8,8 0 0,1 20,12A8,8 0 0,1 12,20M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2Z'
    };
    return paths[props.name];
});

const svgStyle = computed(() => ({
    width: typeof props.size === 'number' ? `${props.size}px` : props.size,
    height: typeof props.size === 'number' ? `${props.size}px` : props.size,
    fill: props.color
}));
</script>

<template>
    <svg
        xmlns="http://www.w3.org/2000/svg"
        viewBox="0 0 24 24"
        :style="svgStyle"
    >
        <title>{{ name }}</title>
        <path :d="svgPath" />
    </svg>
</template> 