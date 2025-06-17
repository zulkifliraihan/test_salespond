<template>
    <div class="min-h-screen bg-gray-50">
        <!-- Header -->
        <div class="bg-white shadow-sm border-b">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
                <h1 class="text-3xl font-bold text-gray-900">Call Logs</h1>
                <p class="mt-2 text-gray-600">Manage your business call logs</p>
            </div>
        </div>
        <!-- Main Content -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
            <!-- Filters Section -->
            <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Filter Call Logs - Date Range</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Company Filter -->
                    <div>
                        <label for="start-date-filter" class="block text-sm font-medium text-gray-700 mb-2">
                            Start Date
                        </label>
                        <input 
                            id="start-date-filter" 
                            v-model="filters.startDate" 
                            type="date"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                            @input="handleFilterChange"
                        />
                    </div>
                    <div>
                        <label for="end-date-filter" class="block text-sm font-medium text-gray-700 mb-2">
                            End Date
                        </label>
                        <input 
                            id="end-date-filter" 
                            v-model="filters.endDate" 
                            type="date"
                            class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
                            @input="handleFilterChange"
                        />
                    </div>
                    <!-- Role Filter -->
                </div>
                <!-- Validation Message -->
                <div v-if="dateFilterError" class="mt-4 p-3 bg-red-50 border border-red-200 rounded-md">
                    <p class="text-sm text-red-600">{{ dateFilterError }}</p>
                </div>
                <!-- Clear Filters -->
                <div class="mt-4 flex justify-end">
                    <button @click="clearFilters"
                        class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition-colors">
                        Clear Filters
                    </button>
                </div>
            </div>
            
            <!-- Loading State -->
            <div v-if="loading" class="bg-white rounded-lg shadow-sm border p-8 text-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"></div>
                <p class="mt-2 text-gray-600">Loading callLogs...</p>
            </div>

            <!-- Results Summary -->
            <div v-if="!loading && pagination" class="mb-6">
                <p class="text-gray-600">
                    Showing {{ pagination.from }} to {{ pagination.to }} of {{ pagination.total }} call logs
                </p>
            </div>
            
            <!-- CallLogs List -->
            <div v-if="!loading" class="bg-white rounded-lg shadow-sm border overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Contact Name
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Timestamp
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider text-center">
                                    Duration (seconds)
                                </th>
                                <th
                                    class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <tr v-for="callLog in callLogs" :key="callLog.id"
                                class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div
                                                class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center">
                                                <span class="text-sm font-medium text-white">
                                                    {{ callLog.contact.name.charAt(0).toUpperCase() }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ callLog.contact.name }}
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900">{{ dateFormat(callLog.created_at) }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="text-sm text-gray-900">{{ callLog.duration }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 capitalize">
                                        {{ callLog.status }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <!-- Empty State -->
                <div v-if="callLogs.length === 0" class="text-center py-12">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No callLogs found</h3>
                    <p class="mt-1 text-sm text-gray-500">Try adjusting your filters to see more results.</p>
                </div>
            </div>
            
            <!-- Pagination Component -->
           <PaginationNew 
                :links="pagination ? pagination.links : []" 
                @page-changed="handlePageChange"
            />
        </div>
    </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import _ from 'lodash'
import PaginationNew from '@/components/PaginationNew.vue'

export default {
    name: 'CallLogsView',
    components: {
        PaginationNew
    },
    data() {
        return {
            filters: {
                startDate: '',
                endDate: '',
                paginate: {
                    pagination: true,
                    page: 1,
                    limit: 10
                },
                page: 1,
                limit: 10,
            },
            searchTimeout: null,
            isInitialized: false,
            dateFilterError: ''
        }
    },
    computed: {
        ...mapGetters('callLogs', ['callLogs', 'pagination', 'loading'])
    },
    async mounted() {
        this.initializeFilters()
        await this.loadCallLogs()
        this.isInitialized = true
    },
    methods: {
        ...mapActions('callLogs', ['fetchCallLogs']),
        
        dateFormat(date) {
              return new Date(date).toISOString().split('T')[0]
        },

        initializeFilters() {
            // Sync filters dengan query parameters saat pertama kali load
            const query = this.$route.query
            this.filters.paginate.page = parseInt(query.page) || 1
            this.filters.startDate = query.startDate || ''
            this.filters.endDate = query.endDate || ''
        },
        
        validateDateFilters() {
            console.log('Validating date filters:', this.filters.startDate, this.filters.endDate)
            this.dateFilterError = ''
            
            if (!this.filters.startDate && !this.filters.endDate) {
                return true
            }
            
            if (!this.filters.startDate && this.filters.endDate) {
                this.dateFilterError = 'Please fill in the Start Date field'
                return false
            }
            
            if (this.filters.startDate && !this.filters.endDate) {
                this.dateFilterError = 'Please fill in the End Date field'
                return false
            }
            
            if (this.filters.startDate && this.filters.endDate) {
                if (new Date(this.filters.startDate) > new Date(this.filters.endDate)) {
                    this.dateFilterError = 'Start Date cannot be later than End Date'
                    return false
                }
            }
            
            return true
        },

        async loadCallLogs() {
            console.log('loadCallLogs', this.filters)

            if (!this.validateDateFilters()) {
                return
            }

            try {
                const query = {
                    pagination: this.filters.paginate.pagination,
                    page: this.filters.paginate.page > 1 ? this.filters.paginate.page : undefined,
                    limit: this.filters.paginate.limit,
                    'filter[start_date]': this.filters.startDate ? this.filters.startDate : undefined,
                    'filter[end_date]': this.filters.endDate ? this.filters.endDate : undefined
                }
                console.log('Fetching callLogs with query:', query)

                await this.fetchCallLogs(query)
            } catch (error) {
                console.error('Error loading callLogs:', error)
            }
        },

        async clearFilters() {
            this.filters.startDate = ''
            this.filters.endDate = ''
            this.filters.paginate.page = 1
            this.dateFilterError = '' 
            
            this.updateURL()
            await this.loadCallLogs()
        },
        
        handlePageChange(page) {
            this.filters.paginate.page = page
            this.updateURL()
            this.loadCallLogs()
        },
        
        updateURL() {
            const query = {}
            
            if (this.filters.paginate.page > 1) {
                query.page = this.filters.paginate.page
            }
            
            if (this.filters.startDate) {
                query.startDate = this.filters.startDate
            }
            
            if (this.filters.endDate) {
                query.endDate = this.filters.endDate
            }
            
            if (JSON.stringify(query) !== JSON.stringify(this.$route.query)) {
                this.$router.replace({ 
                    path: this.$route.path, 
                    query: query 
                })
            }
        },

        handleFilterChange: _.debounce(function () {
            console.log('handleFilterChange with debounce', this.filters)
            this.filters.paginate.page = 1
            this.updateURL()
            this.loadCallLogs()
        }, 400)
    }
}
</script>