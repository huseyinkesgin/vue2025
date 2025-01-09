<template>
    <div>
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

        <!-- Tablo -->
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
                </tr>
            </thead>
            <tbody>
                <tr v-for="(il, index) in iller.data" 
                    :key="il.id" 
                    :class="[
                        index % 2 === 0 ? 'bg-white' : 'bg-blue-50',
                        'hover:bg-orange-100 cursor-pointer'
                    ]" 
                    @click.stop="handleEdit(il)"
                >
                    <td class="px-6 py-2 whitespace-nowrap text-xs border border-gray-300">{{ il.Kod }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-xs border border-gray-300">{{ il.IlAdi }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-xs border border-gray-300">{{ il.Durum }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-xs border border-gray-300">{{ il.Aciklama }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-xs border border-gray-300">{{ il.eklenme_tarihi_format }}</td>
                    <td class="px-6 py-2 whitespace-nowrap text-xs border border-gray-300">{{ il.duzenlenme_tarihi_format }}</td>
                </tr>
            </tbody>
        </table>
        
        <!-- Pagination -->
        <Pagination 
            :pagination="iller" 
            @page-changed="handlePageChange"
        />
    </div>
</template>

<script setup>
import { ref, watch } from 'vue';
import Pagination from '@/Components/Pagination.vue';
import { router } from '@inertiajs/vue3';
import debounce from 'lodash/debounce';

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
</script>
