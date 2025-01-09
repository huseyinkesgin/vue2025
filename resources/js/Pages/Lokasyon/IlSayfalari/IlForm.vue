<template>
    <FormModal :title="editMode ? 'Il Düzenle' : 'Yeni Il'" :width="'320px'" :height="'300px'" modelName="il"
        @close="handleClose" @open="initializeForm" @generatedKod="setKod" @submit="handleSubmit"
        :generate-kod="editMode ? false : true">
        <form @submit.prevent="handleSubmit" @click.stop>
            <div class="mb-2">
                <div class="flex items-center">
                    <label for="Kod" class="block text-xs font-medium text-gray-700 w-1/5">Kod</label>
                    <input type="text" v-model="form.Kod" id="Kod" class="w-4/5 input-xs" ref="firstInput" />
                </div>
                <div v-if="form.errors.Kod" class="text-red-500 text-xs mt-1 ml-[20%]">{{ form.errors.Kod }}</div>
            </div>

            <div class="mb-2">
                <div class="flex items-center">
                    <label for="IlAdi" class="block text-xs font-medium text-gray-700 w-1/5">Il Adi</label>
                    <input type="text" v-model="form.IlAdi" id="IlAdi" class="w-4/5 input-xs" />
                </div>
                <div v-if="form.errors.IlAdi" class="text-red-500 text-xs mt-1 ml-[20%]">{{ form.errors.IlAdi }}</div>
            </div>

            <div class="mb-2">
                <div class="flex items-center">
                    <label for="Durum" class="block text-xs font-medium text-gray-700 w-1/5">Durum</label>
                    <select v-model="form.Durum" id="Durum" class="w-4/5 select-xs">
                        <option value="Aktif">Aktif</option>
                        <option value="Pasif">Pasif</option>
                    </select>
                </div>
                <div v-if="form.errors.Durum" class="text-red-500 text-xs mt-1 ml-[20%]">{{ form.errors.Durum }}</div>
            </div>

            <div class="mb-2">
                <div class="flex items-center">
                    <label for="Aciklama" class="block text-xs font-medium text-gray-700 w-1/5">Aciklama</label>
                    <textarea v-model="form.Aciklama" id="Aciklama" class="w-4/5"></textarea>
                </div>
                <div v-if="form.errors.Aciklama" class="text-red-500 text-xs mt-1 ml-[20%]">{{ form.errors.Aciklama }}
                </div>
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
import FormModal from '@/Components/FormModal.vue';
import { router, useForm } from '@inertiajs/vue3';

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

const form = useForm({
    Kod: '',
    IlAdi: '',
    Durum: 'Aktif',
    Aciklama: ''
});

const firstInput = ref(null);

const focusFirstInput = () => {
    firstInput.value.focus();
};

const setKod = (kod) => {
    form.Kod = kod;
};

const initializeForm = () => {
    focusFirstInput();
};

const initForm = () => {
    if (props.editMode && props.ilData) {
        form.Kod = props.ilData.Kod;
        form.IlAdi = props.ilData.IlAdi;
        form.Durum = props.ilData.Durum;
        form.Aciklama = props.ilData.Aciklama;
    } else {
        form.reset();
        form.Durum = 'Aktif';
    }
};

const handleKeydown = (event) => {
    if (event.ctrlKey && event.key === 's') {
        event.preventDefault();
        handleSubmit();
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

const handleSubmit = () => {
    if (props.editMode) {
        form.put(route('il.update', props.ilData.id), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                emit('close');
            }
        });
    } else {
        form.post(route('il.store'), {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                emit('close');
            }
        });
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
