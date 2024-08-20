<template>
    <button :class="buttonClass" @click="handleClick">
        <font-awesome-icon :icon="icon" :class="fontSizeClass + ' text-gray-500'" />
    </button>
</template>

<script>
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

export default {
    name: 'IconButton',
    components: {
        FontAwesomeIcon,
    },
    props: {
        icon: {
            type: [String, Array],
            required: true,
        },
        size: {
            type: String,
            default: 'md',
            validator(value) {
                return ['sm', 'md', 'lg', 'xl'].includes(value);
            },
        },
    },
    computed: {
        buttonClass() {
            let sizeClass;
            switch (this.size) {
                case 'sm':
                    sizeClass = 'w-[28px] h-[28px]';
                    break;
                case 'md':
                    sizeClass = 'w-[32px] h-[32px]';
                    break;
                case 'lg':
                    sizeClass = 'w-[36px] h-[36px]';
                    break;
                case 'xl':
                    sizeClass = 'w-[60px] h-[60px]';
                    break;
                default:
                    sizeClass = 'w-[32px] h-[32px]';
            }
            return `${sizeClass} rounded-xl flex items-center justify-center bg-gray-200 hover:bg-gray-300`;
        },
        fontSizeClass() {
            let sizeClass;
            switch (this.size) {
                case 'sm':
                    sizeClass = 'text-[12px]';
                    break;
                case 'md':
                    sizeClass = 'text-[16px]';
                    break;
                case 'lg':
                    sizeClass = 'text-[28px]';
                    break;
                case 'xl':
                    sizeClass = 'text-[48px]';
                    break;
                default:
                    sizeClass = 'text-[16px]';
            }
            return sizeClass;
        },
    },
    methods: {
        handleClick() {
            this.$emit('iconClick');
        }
    }
};
</script>

<style scoped>
</style>
