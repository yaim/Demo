<template>
    <div class="min-w-full">
        <div style="width: 284px;" class="block shadow mt-8 mx-auto overflow-hidden sm:rounded-lg border-b border-gray-200">
            <table class="align-middle min-w-full">
                <thead>
                    <tr>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Time
                        </th>
                        <th class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <tr v-for="log in logs" :key="log.attempted_at">
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            <div class="text-sm leading-5 text-gray-900">{{log.attempted_at | moment('from', 'now')}}</div>
                            <div class="text-sm leading-5 text-gray-500">{{log.attempted_at}}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-no-wrap border-b border-gray-200">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                Successful
                            </span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    const axios = require('axios');

    export default {
        data: function() {
            return {
                logs: [],
            }
        },
        mounted: function() {
            const token = localStorage.getItem('token');

            axios.get('/api/auth/successful-attempts', {
                headers: {
                    'Authorization': `Bearer ${token}`
                }
            })
            .then(response => {
                this.logs = response.data.data
            })
            .catch(error => {
                localStorage.setItem('token', null);

                this.$router.push({name: 'login'});
            });
        }
    }
</script>
