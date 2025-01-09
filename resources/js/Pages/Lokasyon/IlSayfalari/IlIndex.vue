<template>
    <AppLayout title="İl Yönetimi">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                İl Yönetimi
            </h2>
        </template>

        <div class="py-1">
            <div class="mx-auto h-[calc(100vh-12rem)]">
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-sm flex justify-between items-center p-4">
                    <h1 class="text-sm font-bold mb-4">Il Listesi</h1>
                    <button @click.prevent="openModal" class="button-ekle mb-2">Yeni Il Ekle</button>
                </div>
                <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg h-full">
                    <IlTable :iller="iller" :filters="filters" @edit="editIl" />
                </div>
            </div>
        </div>

        <Teleport to="body">
            <IlForm v-if="showModal" :edit-mode="!!selectedIl" :il-data="selectedIl" @close="closeModal" />
        </Teleport>
    </AppLayout>
</template>

<script setup>
import { ref } from 'vue';
import IlTable from './IlTable.vue';
import IlForm from './IlForm.vue';
import AppLayout from '@/Layouts/AppLayout.vue';

const props = defineProps({
    iller: {
        type: Object,
        required: true
    },
    filters: {
        type: Object,
        required: true,
        default: () => ({
            search: '',
            per_page: 20
        })
    }
});

const showModal = ref(false);
const selectedIl = ref(null);

const openModal = () => {
    selectedIl.value = null;
    showModal.value = true;
};

const editIl = (il) => {
    console.log('Editing Il:', il);
    selectedIl.value = il;
    showModal.value = true;
};

const closeModal = () => {
    showModal.value = false;
    setTimeout(() => {
        selectedIl.value = null;
    }, 100);
};
</script>
