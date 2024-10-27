<template>
    <div class="container mx-auto p-4 max-w-md">
        <h1 class="text-2xl font-bold mb-4">Currency Converter</h1>
        <form @submit.prevent="convertCurrency" class="space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div class="flex flex-col">
                    <select v-model="from" class="border p-2 rounded" :class="{ 'border-red-500': fromError }">
                        <option disabled value="">Select From Currency</option>
                        <option v-for="country in countries" :key="country.code" :value="country.code">
                            {{ country.name }} ({{ country.code }})
                        </option>
                    </select>
                    <div v-if="fromError" class="text-red-500 text-sm">From currency is required.</div>
                </div>

                <div class="flex flex-col">
                    <select v-model="to" class="border p-2 rounded" :class="{ 'border-red-500': toError }">
                        <option disabled value="">Select To Currency</option>
                        <option v-for="country in countries" :key="country.code" :value="country.code">
                            {{ country.name }} ({{ country.code }})
                        </option>
                    </select>
                    <div v-if="toError" class="text-red-500 text-sm">To currency is required.</div>
                </div>

                <div class="flex flex-col col-span-2">
                    <input type="number" v-model.number="amount" placeholder="Amount" class="border p-2 rounded" />
                    <div v-if="amountError" class="text-red-500 text-sm">{{ amountError }}</div>
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded col-span-2">
                    Convert
                </button>
            </div>
        </form>

        <!-- Display validation errors -->
        <div v-if="error" class="mt-4 text-red-500">
            {{ error }}
        </div>

        <!-- Display result -->
        <div v-if="result" class="mt-4 text-lg font-semibold">
            Result: {{ result }}
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';

// Static JSON data of countries with currency codes
const countries = [
    { code: 'USD', name: 'United States Dollar' },
    { code: 'INR', name: 'Indian Rupee' },
    { code: 'EUR', name: 'Euro' },
    { code: 'JPY', name: 'Japanese Yen' },
    { code: 'AUD', name: 'Australian Dollar' },
    // Add more countries as needed
];

const from = ref('');
const to = ref('');
const amount = ref(null); // Initialize as null for validation
const result = ref(null);
const error = ref(null);
const fromError = ref(false);
const toError = ref(false);
const amountError = ref(null);

const convertCurrency = async () => {
    // Reset previous errors
    error.value = null;
    result.value = null;
    fromError.value = false;
    toError.value = false;
    amountError.value = null;

    // Validation checks
    if (!from.value) {
        fromError.value = true;
    }
    if (!to.value) {
        toError.value = true;
    }

    // Check amount for being null and positive
    if (amount.value === null || amount.value === undefined) {
        amountError.value = "Amount is required and must be a positive number.";
    } else if (amount.value <= 0) {
        amountError.value = "Amount must be a positive number.";
    }

    // Check if From and To currencies are the same only if they are selected
    if (from.value && to.value && from.value === to.value) {
        error.value = "From and To currencies cannot be the same.";
    }

    // If any field is invalid, return early
    if (fromError.value || toError.value || amountError.value || error.value) {
        return;
    }

    try {
        // API request to convert currency
        const response = await axios.post('/convert', {
            from: from.value,
            to: to.value,
            amount: amount.value,
        });
        result.value = response.data.data.result;
    } catch (err) {
        // Handle API error
        error.value = err.response?.data?.error || 'An error occurred while converting currency';
    }
};
</script>

<style scoped>
.container {
    max-width: 500px;
}
</style>
