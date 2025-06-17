<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <h1 class="text-3xl font-bold text-gray-900">Contacts</h1>
        <p class="mt-2 text-gray-600">Manage your business contacts</p>
      </div>
    </div>
    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Filters Section -->
      <div class="bg-white rounded-lg shadow-sm border p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">
          Filter Contacts
        </h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
          <!-- Company Filter -->
          <div>
            <label
              for="company-filter"
              class="block text-sm font-medium text-gray-700 mb-2"
            >
              Company
            </label>
            <input
              id="company-filter"
              v-model="filters.company"
              type="text"
              placeholder="Search by company name..."
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
              @input="handleFilterChange"
            />
          </div>
          <!-- Role Filter -->
          <div>
            <label
              for="role-filter"
              class="block text-sm font-medium text-gray-700 mb-2"
            >
              Role
            </label>
            <select
              id="role-filter"
              v-model="filters.roleId"
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
              @change="handleFilterChange"
            >
              <option value="">All Roles</option>
              <option v-for="role in roles" :key="role.id" :value="role.id">
                {{ role.name }}
              </option>
            </select>
          </div>
          <!-- Favorite Filter -->
          <div>
            <label
              for="favorite-filter"
              class="block text-sm font-medium text-gray-700 mb-2"
            >
              Favorite Contacts
            </label>
            <select
              id="favorite-filter"
              v-model="filters.isFavorite"
              class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-colors"
              @change="handleFilterChange"
            >
              <option value="">All Data</option>
              <option :value="true">Favorite</option>
              <option :value="false">Not Favorite</option>
            </select>
          </div>
        </div>
        <!-- Clear Filters -->
        <div class="mt-4 flex justify-end">
          <button
            @click="clearFilters"
            class="px-4 py-2 text-sm text-gray-600 hover:text-gray-800 transition-colors"
          >
            Clear Filters
          </button>
        </div>
      </div>

      <!-- Loading State -->
      <div
        v-if="loading"
        class="bg-white rounded-lg shadow-sm border p-8 text-center"
      >
        <div
          class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mx-auto"
        ></div>
        <p class="mt-2 text-gray-600">Loading contacts...</p>
      </div>

      <!-- Results Summary -->
      <div class="flex items-center justify-between mb-6">
        <div v-if="!loading && pagination">
          <p class="text-gray-600">
            Showing {{ pagination.from }} to {{ pagination.to }} of
            {{ pagination.total }} contacts
          </p>
        </div>
        <button
          @click="$router.push('/call-logs')"
          class="inline-flex items-center px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 hover:bg-blue-100 border border-blue-200 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
          <svg
            class="w-4 h-4 mr-2"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"
            ></path>
          </svg>
          View Call Logs
        </button>
      </div>

      <!-- Contacts List -->
      <div
        v-if="!loading"
        class="bg-white rounded-lg shadow-sm border overflow-hidden"
      >
        <div class="overflow-x-auto">
          <table class="w-full">
            <thead class="bg-gray-50">
              <tr>
                <th
                  class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Name
                </th>
                <th
                  class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Phone
                </th>
                <th
                  class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Company
                </th>
                <th
                  class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Role
                </th>
                <th
                  class="px-6 py-4 text-left text-xs font-medium text-gray-500 uppercase tracking-wider"
                >
                  Action
                </th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
              <tr
                v-for="contact in contacts"
                :key="contact.id"
                class="hover:bg-gray-50 transition-colors"
              >
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                      <div
                        class="h-10 w-10 rounded-full bg-blue-500 flex items-center justify-center"
                      >
                        <span class="text-sm font-medium text-white">
                          {{ contact.name.charAt(0).toUpperCase() }}
                        </span>
                      </div>
                    </div>
                    <div class="ml-4">
                      <div class="text-sm font-medium text-gray-900">
                        {{ contact.name }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ contact.phone }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900">{{ contact.company }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span
                    class="inline-flex px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800"
                  >
                    {{ contact.role_name || contact.role?.name }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap flex gap-2">
                  <!-- Favorite Button -->
                  <button
                    @click="toggleFavorite(contact)"
                    :class="[
                      'inline-flex items-center px-3 py-2 text-sm font-medium rounded-md transition-colors focus:outline-none focus:ring-2',
                      contact.is_favorite
                        ? 'bg-yellow-400 text-white hover:bg-yellow-500 focus:ring-yellow-300'
                        : 'bg-gray-200 text-gray-700 hover:bg-gray-300 focus:ring-gray-300',
                    ]"
                    :title="contact.is_favorite ? 'Unfavorite' : 'Favorite'"
                  >
                    <svg
                      class="w-4 h-4 mr-2"
                      fill="currentColor"
                      viewBox="0 0 20 20"
                    >
                      <path
                        v-if="contact.is_favorite"
                        d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.286 3.95a1 1 0 00.95.69h4.162c.969 0 1.371 1.24.588 1.81l-3.37 2.448a1 1 0 00-.364 1.118l1.287 3.95c.3.921-.755 1.688-1.54 1.118l-3.37-2.449a1 1 0 00-1.176 0l-3.37 2.449c-.784.57-1.838-.197-1.539-1.118l1.287-3.95a1 1 0 00-.364-1.118L2.07 9.377c-.782-.57-.38-1.81.588-1.81h4.162a1 1 0 00.95-.69l1.286-3.95z"
                      />
                      <path
                        v-else
                        fill-rule="evenodd"
                        d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 18.657l-6.828-6.828a4 4 0 010-5.657z"
                        clip-rule="evenodd"
                      />
                    </svg>
                    {{ contact.is_favorite ? 'Unfavorite' : 'Favorite' }}
                  </button>
                  <button
                    @click="callContact(contact)"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-green-600 hover:bg-green-700 rounded-md transition-colors focus:outline-none focus:ring-2 focus:ring-green-500"
                  >
                    <svg
                      class="w-4 h-4 mr-2"
                      fill="none"
                      stroke="currentColor"
                      viewBox="0 0 24 24"
                    >
                      <path
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"
                      ></path>
                    </svg>
                    Call
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <!-- Empty State -->
        <div v-if="contacts.length === 0" class="text-center py-12">
          <svg
            class="mx-auto h-12 w-12 text-gray-400"
            fill="none"
            stroke="currentColor"
            viewBox="0 0 24 24"
          >
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"
            ></path>
          </svg>
          <h3 class="mt-2 text-sm font-medium text-gray-900">
            No contacts found
          </h3>
          <p class="mt-1 text-sm text-gray-500">
            Try adjusting your filters to see more results.
          </p>
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
import { mapGetters, mapActions } from "vuex";
import _ from "lodash";
import PaginationNew from "@/components/PaginationNew.vue";

export default {
  name: "ContactsView",
  components: {
    PaginationNew,
  },
  data() {
    return {
      filters: {
        company: "",
        role: "",
        roleId: "",
        isFavorite: "",
        paginate: {
          pagination: true,
          page: 1,
          limit: 10,
        },
        page: 1,
        limit: 10,
      },
      searchTimeout: null,
      isInitialized: false,
    };
  },
  computed: {
    ...mapGetters("contacts", ["contacts", "pagination", "loading", "roles"]),
    ...mapGetters("callLogs", {
      callLogsLoading: "loading",
    }),
  },
  async mounted() {
    this.initializeFilters();
    await this.loadContacts();
    await this.fetchRoles();
    this.isInitialized = true;
  },
  methods: {
    ...mapActions("contacts", ["fetchContacts", "fetchRoles", "updateContact"]),
    ...mapActions("callLogs", ["createCallLog"]),

    initializeFilters() {
      // Sync filters dengan query parameters saat pertama kali load
      const query = this.$route.query;
      this.filters.paginate.page = parseInt(query.page) || 1;
      this.filters.company = query.company || "";
      this.filters.role = query.role || "";
    },

    async loadContacts() {
      console.log("loadContacts", this.filters);
      try {
        const query = {
          pagination: this.filters.paginate.pagination,
          page:
            this.filters.paginate.page > 1
              ? this.filters.paginate.page
              : undefined,
          limit: this.filters.paginate.limit,
          "filter[company]": this.filters.company
            ? this.filters.company
            : undefined,
          "filter[role_id]": this.filters.roleId
            ? this.filters.roleId
            : undefined,
          "filter[is_favorite]": (this.filters.isFavorite == true || this.filters.isFavorite == false) && this.filters.isFavorite !== "" ? this.filters.isFavorite : undefined,
        };
        console.log("Fetching contacts with query:", query);

        await this.fetchContacts(query);
      } catch (error) {
        console.error("Error loading contacts:", error);
      }
    },

    async clearFilters() {
      this.filters.company = undefined;
      this.filters.role = undefined;
      this.filters.paginate.page = undefined;

      this.updateURL();
      await this.loadContacts();
    },

    handlePageChange(page) {
      this.filters.paginate.page = page;
      this.updateURL();
      this.loadContacts();
    },

    updateURL() {
      const query = {};

      if (this.filters.paginate.page > 1) {
        query.page = this.filters.paginate.page;
      }

      if (this.filters.company) {
        query.company = this.filters.company;
      }

      if (this.filters.roleId) {
        // Get the role name from roles array
        this.filters.role = this.roles.find(
          (r) => r.id === this.filters.roleId
        ).name;

        query.role = this.filters.role;
      }

      if ((this.filters.isFavorite == true || this.filters.isFavorite == false) && this.filters.isFavorite !== "") {
        query.is_favorite = this.filters.isFavorite;
      }

      if (JSON.stringify(query) !== JSON.stringify(this.$route.query)) {
        this.$router.replace({
          path: this.$route.path,
          query: query,
        });
      }
    },

    async callContact(contact) {
      console.log("Calling contact:", contact);
      alert(`Calling ${contact.name} at ${contact.phone}`);
      const callLog = {
        contact_id: contact.id,
        // Random duration from 1 to 60 seconds
        duration: Math.floor(Math.random() * 60) + 1,
        // Random status just 2 options: 'completed' or 'missed'
        status: Math.random() > 0.5 ? "completed" : "missed",
      };

      await this.createCallLog(callLog);

      alert(
        `Call initiated to ${contact.name} at ${contact.phone}. Call log has been created with a duration of ${callLog.duration} seconds and status of ${callLog.status}`
      );
    },

    async toggleFavorite(contact) {
        console.log("Toggling favorite for contact:", contact)

        alert(
          `Toggling favorite for ${contact.name} at ${contact.phone}`
        )

        contact.is_favorite = !contact.is_favorite
        await this.updateContact({ contactId: contact.id, data: { is_favorite: contact.is_favorite } })
        
        alert(
          `${contact.name} has been ${
            contact.is_favorite ? "added to" : "removed from"
          } favorites`
        )
    },

    handleFilterChange: _.debounce(function () {
      console.log("handleFilterChange with debounce", this.filters);
      this.filters.paginate.page = 1;
      this.updateURL();
      this.loadContacts();
    }, 400),
  },
};
</script>
