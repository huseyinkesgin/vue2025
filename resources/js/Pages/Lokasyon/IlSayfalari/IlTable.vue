<template>
    <div class="flex flex-col h-full">
        <!-- Tablo Üst Kısmı -->
        <div class="flex justify-between items-center mb-4 px-6 py-3 bg-white border-b">
            <!-- Per Page Select -->
            <div class="flex items-center space-x-4">
                <label class="text-sm text-gray-600">Sayfa başına:</label>
                <select 
                    v-model="perPage" 
                    @change="handlePerPageChange"
                    class="border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500"
                >
                    <option v-for="size in [10, 20, 50, 100]" :key="size" :value="size">
                        {{ size }}
                    </option>
                </select>
            </div>

            <!-- Search Box -->
            <div class="flex items-center">
                <div class="relative">
                    <input
                        type="text"
                        v-model="search"
                        placeholder="Ara..."
                        class="pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm focus:ring-blue-500 focus:border-blue-500"
                    >
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tablo Container -->
        <div class="flex-1 overflow-x-auto">
            <table class="min-w-full border-collapse">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col"
                            class="px-6 py-2 text-left text-xs font-medium tracking-wider text-[#800000] capitalize border border-gray-300">
                            Kod</th>
                        <th scope="col"
                            class="px-6 py-2 text-left text-xs font-medium tracking-wider text-[#800000] capitalize border border-gray-300">
                            Il adi</th>
                        <th scope="col"
                            class="px-6 py-2 text-left text-xs font-medium tracking-wider text-[#800000] capitalize border border-gray-300">
                            Durum</th>
                        <th scope="col"
                            class="px-6 py-2 text-left text-xs font-medium tracking-wider text-[#800000] capitalize border border-gray-300">
                            Aciklama</th>
                        <th scope="col"
                            class="px-6 py-2 text-left text-xs font-medium tracking-wider text-[#800000] capitalize border border-gray-300">
                            Eklenme t.</th>
                        <th scope="col"
                            class="px-6 py-2 text-left text-xs font-medium tracking-wider text-[#800000] capitalize border border-gray-300">
                            Duzenlenme t.</th>
                        <th scope="col" class="px-6 py-2 text-left text-xs font-medium tracking-wider text-[#800000] capitalize border border-gray-300">
                            İşlemler
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="(il, index) in iller.data" 
                        :key="il.id" 
                        :class="[
                            index % 2 === 0 ? 'bg-white' : 'bg-blue-50',
                            'hover:bg-orange-100'
                        ]"
                        @click="handleEdit(il)"
                        style="cursor: pointer"
                    >
                        <td class="px-6 py-2 whitespace-nowrap text-xs border border-gray-300">{{ il.Kod }}</td>
                        <td class="px-6 py-2 whitespace-nowrap text-xs border border-gray-300">{{ il.IlAdi }}</td>
                        <td class="px-6 py-2 whitespace-nowrap text-xs border border-gray-300">{{ il.Durum }}</td>
                        <td class="px-6 py-2 whitespace-nowrap text-xs border border-gray-300">{{ il.Aciklama }}</td>
                        <td class="px-6 py-2 whitespace-nowrap text-xs border border-gray-300">{{ il.eklenme_tarihi_format }}</td>
                        <td class="px-6 py-2 whitespace-nowrap text-xs border border-gray-300">{{ il.duzenlenme_tarihi_format }}</td>
                        <td class="px-6 py-2 whitespace-nowrap text-xs border border-gray-300">
                            <button 
                                @click.stop="confirmDelete(il)" 
                                class="text-red-600 hover:text-red-800"
                            >
                                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Delete Modal -->
        <DeleteModal 
            v-if="showDeleteModal"
            @confirm="handleDelete"
            @cancel="showDeleteModal = false"
        />
        
        <!-- Pagination - Sabit Alt Kısım -->
        <div class="sticky bottom-0 bg-white border-t shadow-md">
            <Pagination 
                :pagination="iller" 
                @page-changed="handlePageChange"
            />
        </div>
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import { router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';
import DeleteModal from '@/Components/DeleteModal.vue';

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

const emit = defineEmits(['edit']);
const search = ref(props.filters?.search || '');
const perPage = ref(props.filters?.per_page || 20);

// Arama için debounce fonksiyonu
const debouncedSearch = debounce((value) => {
    router.get(
        route('il.index'),
        { search: value, per_page: perPage.value },
        {
            preserveState: true,
            preserveScroll: true,
            only: ['iller']
        }
    );
}, 300);

// Search değişikliğini izle
watch(search, (value) => {
    debouncedSearch(value);
});

const handlePerPageChange = () => {
    router.get(
        route('il.index'),
        { 
            per_page: perPage.value,
            search: search.value
        },
        {
            preserveState: true,
            preserveScroll: true,
            only: ['iller']
        }
    );
};

const handleEdit = (il) => {
    emit('edit', il);
};

const handlePageChange = (page) => {
    router.get(
        route('il.index'),
        { 
            page: page,
            per_page: perPage.value,
            search: search.value
        },
        {
            preserveState: true,
            preserveScroll: true,
            only: ['iller']
        }
    );
};

const showDeleteModal = ref(false);
const selectedIl = ref(null);

const confirmDelete = (il) => {
    selectedIl.value = il;
    showDeleteModal.value = true;
};

const handleDelete = () => {
    router.delete(route('il.destroy', selectedIl.value.id), {
        onBefore: () => {
            showDeleteModal.value = false;
        },
        onSuccess: () => {
            selectedIl.value = null;
            // Sayfayı yeniden yükle
            router.get(
                route('il.index'),
                { 
                    search: search.value,
                    per_page: perPage.value
                },
                {
                    preserveState: true,
                    preserveScroll: true,
                    only: ['iller']
                }
            );
        },
        onError: (error) => {
            console.error('Silme işlemi başarısız:', error);
        }
    });
};
</script>
