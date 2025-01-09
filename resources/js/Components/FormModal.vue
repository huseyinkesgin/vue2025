<template>
    <div class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click.self="$emit('close')"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
            <div
                :style="{ width: width, height: height, top: position.top, left: position.left }"
                class="fixed bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle relative"
                @mousedown.stop.prevent="startDrag"
            >
                <div class="bg-white px-4 pt-1 pb-4 sm:p-6 sm:pb-4">
                    <div class="flex justify-between items-start w-full pt-1 draggable">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">{{ title }}</h3>
                        <button @click="$emit('close')" type="button" class="absolute top-1 right-1 text-gray-400 hover:text-gray-600 focus:outline-none">
                            <span class="sr-only">Close</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                    <div class="mt-1 w-full">
                        <slot></slot>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { defineProps, onMounted, onUnmounted, defineEmits, ref } from 'vue';
import axios from 'axios';

const emit = defineEmits(['close', 'generatedKod', 'submit']);

const props = defineProps({
    title: {
        type: String,
        required: true
    },
    width: {
        type: String,
        default: 'auto'
    },
    height: {
        type: String,
        default: 'auto'
    },
    modelName: {
        type: String,
        required: true
    },
    generateKod: {
        type: Boolean,
        required: true
    }
});

const position = ref({
    top: '50%',
    left: '50%'
});

const formatModelName = (name) => {
    return name.toUpperCase();
};

const generateKod = async () => {
    try {
        const formattedModelName = formatModelName(props.modelName);
        const response = await axios.get(`/${props.modelName}-yonetimi/latest-kod`);
        const latestKod = response.data;
        
        if (!latestKod || typeof latestKod !== 'string') {
            emit('generatedKod', `${formattedModelName}-00001`);
            return;
        }
        
        const parts = latestKod.split('-');
        if (parts.length !== 2) {
            emit('generatedKod', `${formattedModelName}-00001`);
            return;
        }

        const currentNumber = parseInt(parts[1]);
        const nextNumber = isNaN(currentNumber) ? 1 : currentNumber + 1;
        const formattedNumber = nextNumber.toString().padStart(5, '0');
        const kod = `${formattedModelName}-${formattedNumber}`;
        
        emit('generatedKod', kod);
    } catch (error) {
        console.error('Error generating Kod:', error);
        emit('generatedKod', `${formatModelName(props.modelName)}-00001`);
    }
};

const handleKeydown = (event) => {
    if (event.ctrlKey && (event.key === 's' || event.key === 'S')) {
        event.preventDefault();
        emit('submit');
    }
};

const startDrag = (event) => {
    const modal = event.currentTarget.closest('.fixed');
    const shiftX = event.clientX - modal.getBoundingClientRect().left;
    const shiftY = event.clientY - modal.getBoundingClientRect().top;

    const moveAt = (pageX, pageY) => {
        const newLeft = pageX - shiftX;
        const newTop = pageY - shiftY;

        // Ensure the modal stays within the viewport
        const viewportWidth = window.innerWidth;
        const viewportHeight = window.innerHeight;
        const modalWidth = modal.offsetWidth;
        const modalHeight = modal.offsetHeight;

        position.value.left = `${Math.max(0, Math.min(newLeft, viewportWidth - modalWidth))}px`;
        position.value.top = `${Math.max(0, Math.min(newTop, viewportHeight - modalHeight))}px`;
    };

    const onMouseMove = (event) => {
        moveAt(event.clientX, event.clientY);
    };

    const onMouseUp = () => {
        document.removeEventListener('mousemove', onMouseMove);
        document.removeEventListener('mouseup', onMouseUp);
        savePosition();
    };

    document.addEventListener('mousemove', onMouseMove);
    document.addEventListener('mouseup', onMouseUp);
};

const savePosition = async () => {
    try {
        await axios.post('/save-modal-position', position.value);
    } catch (error) {
        console.error('Error saving position:', error);
    }
};

const loadPosition = async () => {
    try {
        const response = await axios.get('/load-modal-position');
        position.value = response.data;
    } catch (error) {
        console.error('Error loading position:', error);
    }
};

onMounted(() => {
    if (props.generateKod === true) {
        generateKod();
    }
    loadPosition();
    window.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown);
});
</script>

<style>
.draggable {
    cursor: move;
}
</style>
