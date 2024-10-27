<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import CurrencyConverter from './Currency/CurrencyConverter.vue';
import { Head } from '@inertiajs/vue3';
import SearchHisrory from './Search/SearchHisrory.vue';


</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <div class="py-2">
            <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        You're logged in!
                    </div>
                </div>
            </div>
        </div>
        <CurrencyConverter>
            <!-- CurrencyConverter component is here -->
        </CurrencyConverter>

        <SearchHisrory />

    </AuthenticatedLayout>

</template>
