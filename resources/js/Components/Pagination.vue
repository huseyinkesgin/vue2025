<template>
    <div class="px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
        <div class="flex-1 flex justify-between items-center">
            <div class="text-sm text-gray-700">
                Toplam
                <span class="font-medium">{{ pagination.total }}</span>
                kayıttan
                <span class="font-medium">{{ startIndex }}</span>
                -
                <span class="font-medium">{{ endIndex }}</span>
                arası gösteriliyor
            </div>
            <div>
                <nav v-if="pagination.total > pagination.per_page"
                    class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <!-- Previous Page Button -->
                    <button @click="changePage(pagination.current_page - 1)" :disabled="pagination.current_page === 1"
                        :class="[
                            pagination.current_page === 1 ? 'cursor-not-allowed opacity-50' : 'hover:bg-gray-50',
                            'relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500'
                        ]">
                        <span class="sr-only">Önceki</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <!-- Page Numbers -->
                    <template v-for="page in displayedPages" :key="page">
                        <button v-if="page !== '...'" @click="changePage(page)" :class="[
                            page === pagination.current_page ? 'z-10 bg-blue-50 border-blue-500 text-blue-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50',
                            'relative inline-flex items-center px-4 py-2 border text-sm font-medium'
                        ]">
                            {{ page }}
                        </button>
                        <span v-else
                            class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">
                            ...
                        </span>
                    </template>

                    <!-- Next Page Button -->
                    <button @click="changePage(pagination.current_page + 1)"
                        :disabled="pagination.current_page === pagination.last_page" :class="[
                            pagination.current_page === pagination.last_page ? 'cursor-not-allowed opacity-50' : 'hover:bg-gray-50',
                            'relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500'
                        ]">
                        <span class="sr-only">Sonraki</span>
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </nav>
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';

const props = defineProps({
    pagination: {
        type: Object,
        required: true,
        validator: (obj) => {
            return [
                'total',
                'per_page',
                'current_page',
                'last_page'
            ].every(prop => prop in obj);
        }
    }
});

const emit = defineEmits(['page-changed']);

const startIndex = computed(() => {
    return (props.pagination.current_page - 1) * props.pagination.per_page + 1;
});

const endIndex = computed(() => {
    return Math.min(
        props.pagination.current_page * props.pagination.per_page,
        props.pagination.total
    );
});

const displayedPages = computed(() => {
    const current = props.pagination.current_page;
    const last = props.pagination.last_page;
    const delta = 2;
    const range = [];
    const rangeWithDots = [];

    for (let i = 1; i <= last; i++) {
        if (
            i === 1 ||
            i === last ||
            (i >= current - delta && i <= current + delta)
        ) {
            range.push(i);
        }
    }

    let l;
    for (const i of range) {
        if (l) {
            if (i - l === 2) {
                rangeWithDots.push(l + 1);
            } else if (i - l !== 1) {
                rangeWithDots.push('...');
            }
        }
        rangeWithDots.push(i);
        l = i;
    }

    return rangeWithDots;
});

const changePage = (page) => {
    if (page >= 1 && page <= props.pagination.last_page) {
        emit('page-changed', page);
    }
};
</script>