<template>
  <div class="mt-4">
    <nav class="flex items-center justify-between px-4 border-t border-gray-200 sm:px-0">
      <template v-for="(page, key) in links">
        <div :key="key">
            <!-- Previous Button -->
            <div v-if="key === 0" class="flex flex-1 w-0 -mt-px">
              <button v-if="page.url"
                @click="changePage(page.url)"
                class="inline-flex items-center pt-4 pr-1 text-sm font-medium text-gray-500 border-t-2 border-transparent hover:text-gray-700 hover:border-gray-300">
                <svg class="w-5 h-5 mr-3 text-gray-400" viewBox="0 0 256 256">
                  <path d="M10 20L30 40" fill="currentColor"/>
                </svg>
                Previous
              </button>
              <span v-else class="inline-flex items-center pt-4 pr-1 text-sm font-medium text-gray-400 border-t-2 border-transparent">Previous</span>
            </div>
    
            <!-- Next Button -->
            <div v-else-if="key === links.length - 1" class="flex justify-end flex-1 w-0 -mt-px">
              <button v-if="page.url"
                @click="changePage(page.url)"
                class="inline-flex items-center pt-4 pl-1 text-sm font-medium text-gray-500 border-t-2 border-transparent hover:text-gray-700 hover:border-gray-300">
                Next
                <svg class="w-5 h-5 ml-3 text-gray-400" viewBox="0 0 256 256">
                  <path d="M10 20L30 40" fill="currentColor"/>
                </svg>
              </button>
              <span v-else class="inline-flex items-center pt-4 pr-1 text-sm font-medium text-gray-400 border-t-2 border-transparent">Next</span>
            </div>
    
            <!-- Dots -->
            <div v-else-if="page.label === '...'" class="hidden md:-mt-px md:flex">
              <span class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium text-gray-500 border-t-2 border-transparent rounded-md">{{ page.label }}</span>
            </div>
    
            <!-- Numbered Pages -->
            <div v-else class="hidden md:-mt-px md:flex">
              <button v-if="page.url"
                @click="changePage(page.url)"
                class="inline-flex items-center px-4 py-2 mt-4 text-sm font-medium border-t-2 rounded-md"
                :class="page.active
                  ? 'border-teal-600 text-teal-600 bg-gray-200 border-2'
                  : 'text-gray-500 hover:text-gray-700 hover:border-gray-300 border-transparent hover:bg-gray-200'">
                {{ page.label }}
              </button>
            </div>
        </div>
      </template>
    </nav>
  </div>
</template>

<script>
export default {
  name: 'Pagination',
  props: {
    links: {
      type: Array,
      required: true
    }
  },
  methods: {
        getLink(url) {
            const query = new URL(url).search;
            return {
                path: this.$route.path,
                query: {
                    ...this.$route.query,
                    ...Object.fromEntries(new URLSearchParams(query))
                }
            }
        },
        changePage(url) {
            const query = new URL(url).search;
            const page = parseInt(Object.fromEntries(new URLSearchParams(query)).page) || 1;
            
            console.log('Changing page to:', page);
            
            // Emit event ke parent component instead of directly manipulating router
            this.$emit('page-changed', page)
        }

  }
}
</script>
