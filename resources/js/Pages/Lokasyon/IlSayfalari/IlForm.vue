<template>
    <FormModal 
        :title="editMode ? 'Il Düzenle' : 'Yeni Il'" 
        :width="'320px'" 
        :height="'300px'" 
        modelName="il"
        @close="handleClose" 
        @open="initializeForm" 
        @generatedKod="setKod"
        @submit="submitForm"
        :generate-kod="editMode ? false : true"
    >
        <form @submit.prevent="submitForm" @click.stop>
            <!-- Genel hata mesajı -->
            <div v-if="errors.general" class="text-red-500 text-xs mt-1 mb-2">
                {{ errors.general[0] }}
            </div>

            <div class="mb-2">
                <div class="flex items-center">
                    <label for="Kod" class="block text-xs font-medium text-gray-700 w-1/5">Kod</label>
                    <input type="text" v-model="il.Kod" id="Kod" class="w-4/5 input-xs" ref="firstInput" />
                </div>
                <div v-if="errors.Kod" class="text-red-500 text-xs mt-1 ml-[20%]">{{ errors.Kod[0] }}</div>
            </div>

            <div class="mb-2">
                <div class="flex items-center">
                    <label for="IlAdi" class="block text-xs font-medium text-gray-700 w-1/5">Il Adi</label>
                    <input type="text" v-model="il.IlAdi" id="IlAdi" class="w-4/5 input-xs" />
                </div>
                <div v-if="errors.IlAdi" class="text-red-500 text-xs mt-1 ml-[20%]">{{ errors.IlAdi[0] }}</div>
            </div>

            <div class="mb-2">
                <div class="flex items-center">
                    <label for="Durum" class="block text-xs font-medium text-gray-700 w-1/5">Durum</label>
                    <select v-model="il.Durum" id="Durum" class="w-4/5 select-xs">
                        <option value="Aktif">Aktif</option>
                        <option value="Pasif">Pasif</option>
                    </select>
                </div>
                <div v-if="errors.Durum" class="text-red-500 text-xs mt-1 ml-[20%]">{{ errors.Durum[0] }}</div>
            </div>

            <div class="mb-2">
                <div class="flex items-center">
                    <label for="Aciklama" class="block text-xs font-medium text-gray-700 w-1/5">Aciklama</label>
                    <textarea v-model="il.Aciklama" id="Aciklama" class="w-4/5"></textarea>
                </div>
                <div v-if="errors.Aciklama" class="text-red-500 text-xs mt-1 ml-[20%]">{{ errors.Aciklama[0] }}</div>
            </div>

            <div class="mt-5 sm:mt-6 flex justify-end space-x-3">
                <button type="submit" class="w-24 button-kaydet">Kaydet</button>
                <button @click.stop="handleClose" type="button" class="w-24 button-iptal">İptal</button>
            </div>
        </form>
    </FormModal>
</template>

<script setup>
import { ref, onMounted, onUnmounted, nextTick } from 'vue';
import axios from 'axios';
import FormModal from '@/Components/FormModal.vue';
import { usePage } from '@inertiajs/vue3';

const props = defineProps({
    editMode: {
        type: Boolean,
        default: false
    },
    ilData: {
        type: Object,
        default: null
    }
});

const il = ref({
    Kod: '',
    IlAdi: '',
    Durum: 'Aktif',
    Aciklama: ''
});

const firstInput = ref(null);
const errors = ref({});

const focusFirstInput = () => {
    firstInput.value.focus();
};

const setKod = (kod) => {
    il.value.Kod = kod;
};

const initializeForm = () => {
    focusFirstInput();
};

const initForm = () => {
    if (props.editMode && props.ilData) {
        console.log('Initializing form with data:', props.ilData); // Debug için
        il.value = { 
            Kod: props.ilData.Kod,
            IlAdi: props.ilData.IlAdi,
            Durum: props.ilData.Durum,
            Aciklama: props.ilData.Aciklama
        };
    } else {
        il.value = {
            Kod: '',
            IlAdi: '',
            Durum: 'Aktif',
            Aciklama: ''
        };
    }
};

const handleKeydown = (event) => {
    if (event.ctrlKey && event.key === 's') {
        event.preventDefault();
        submitForm();
    }
};

const emit = defineEmits(['close']);

const handleClose = (event) => {
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    emit('close');
};

const submitForm = async () => {
    errors.value = {}; 
    
    try {
        const url = props.editMode 
            ? `/il-yonetimi/${props.ilData.id}`
            : '/il-yonetimi';
            
        const method = props.editMode ? 'put' : 'post';

        const response = await axios[method](url, il.value, {
            headers: {
                'X-CSRF-TOKEN': usePage().props.csrf_token,
                'X-Requested-With': 'XMLHttpRequest'
            }
        });
        
        console.log('Form submitted successfully:', response.data);
        errors.value = {}; 
        handleClose();
        window.location.reload();
    } catch (error) {
        if (error.response?.status === 422) {
            errors.value = error.response.data.errors;
        } else {
            console.error('Server Error:', error.response?.data || error.message);
            errors.value = {
                general: ['Bir hata oluştu. Lütfen daha sonra tekrar deneyin.'],
                details: [error.response?.data?.message || error.message]
            };
        }
    }
};

onMounted(() => {
    console.log('IlForm mounted, editMode:', props.editMode);
    console.log('IlForm mounted, ilData:', props.ilData);
    nextTick(() => {
        initForm();
        if (!props.editMode) {
            focusFirstInput();
        }
    });
    window.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
    window.removeEventListener('keydown', handleKeydown);
});
</script>
