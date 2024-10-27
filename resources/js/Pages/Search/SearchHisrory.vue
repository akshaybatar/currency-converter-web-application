<template>
    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-2xl font-semibold mb-4 flex justify-between items-center">
            Currency Search History
            <button @click="fetchCurrencyHistory" class="text-blue-500 hover:text-blue-700 focus:ring-blue-300">
                <svg fill="currentColor" height="25px" width="25px" version="1.1" id="Layer_1"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    viewBox="0 0 383.748 383.748" xml:space="preserve">
                    <g>
                        <path d="M62.772,95.042C90.904,54.899,137.496,30,187.343,30c83.743,0,151.874,68.13,151.874,151.874h30
                       C369.217,81.588,287.629,0,187.343,0c-35.038,0-69.061,9.989-98.391,28.888C70.368,40.862,54.245,56.032,41.221,73.593
                       L2.081,34.641v113.365h113.91L62.772,95.042z" />
                        <path d="M381.667,235.742h-113.91l53.219,52.965c-28.132,40.142-74.724,65.042-124.571,65.042
                       c-83.744,0-151.874-68.13-151.874-151.874h-30c0,100.286,81.588,181.874,181.874,181.874c35.038,0,69.062-9.989,98.391-28.888
                       c18.584-11.975,34.707-27.145,47.731-44.706l39.139,38.952V235.742z" />
                    </g>
                </svg>
            </button>
        </h1>
        <table class="min-w-full border border-gray-300">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border text-left">From Currency</th>
                    <th class="p-3 border text-left">To Currency</th>
                    <th class="p-3 border text-left">Amount</th>
                    <th class="p-3 border text-left">Result</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="history in currencyHistories" :key="history.id">
                    <td class="p-3 border">{{ history.from_currency }}</td>
                    <td class="p-3 border">{{ history.to_currency }}</td>
                    <td class="p-3 border">{{ history.amount }}</td>
                    <td class="p-3 border">{{ history.result }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script setup>
import axios from 'axios';
import { onMounted, ref } from 'vue';

const currencyHistories = ref([]); // Reactive variable to store currency history

const fetchCurrencyHistory = async () => {
    try {
        const response = await axios.get('/search-history'); // Adjust the endpoint as necessary
        currencyHistories.value = response.data; // Assuming the API returns an array of history objects
    } catch (error) {
        // Check if the error response exists and handle specific cases
        if (error.response) {
            // The request was made, and the server responded with a status code
            if (error.response.status === 429) {
                // Handle 429 error specifically
                console.error('Too Many Requests: Please wait before trying again.');
                // Optionally display a notification or set an error state to show in the UI
                alert('Too many requests. Please try again later.');
            } else {
                console.error('Error fetching currency history:', error.response.data.message || error.message);
                alert('An error occurred: ' + (error.response.data.message || error.message));
            }
        } else {
            // Something happened in setting up the request that triggered an Error
            console.error('Error fetching currency history:', error.message);
            alert('An unexpected error occurred: ' + error.message);
        }
    }
};

onMounted(fetchCurrencyHistory);
</script>
