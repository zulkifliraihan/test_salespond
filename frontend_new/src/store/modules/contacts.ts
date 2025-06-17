import axios from "axios";

const apiUrl = import.meta.env.API_URL || "http://localhost:8000";

const initialState = () => {
  return {
    status: null,
    loading: false,
    data: [],
    pagination: null,
    roles: [],
  };
};

export default {
  namespaced: true,
  state: initialState(),
  mutations: {
    SET_CONTACTS(state: any, payload: any) {
      if (payload.paginate) {
        state.data = payload.contacts.data;
        state.pagination = {
          from: payload.contacts.from,
          to: payload.contacts.to,
          links: payload.contacts.links,
          current_page: payload.contacts.current_page,
        };
      } else {
        state.data = payload;
        state.pagination = null;
      }
    },
    SET_LOADING(state: any, loading: boolean) {
      state.loading = loading;
    },
    SET_ROLES(state: any, roles: any) {
      state.roles = roles;
    },
    RESET_STATE(state: any) {
      Object.assign(state, initialState());
    },
  },
  actions: {
    async fetchContacts({ commit }: { commit: Function }, params: any = {}) {
      console.log("fetchContacts vuex", params);
      commit("SET_LOADING", true);
      try {
        const response = await axios.get(`${apiUrl}/api/contacts`, { params });
        console.log("response", response.data.data);
        commit("SET_CONTACTS", response.data.data);
        commit("SET_LOADING", false);

        return response.data.data;
      } catch (error) {
        console.error("Error fetching contacts:", error);
        commit("SET_LOADING", false);
        throw error;
      }
    },
    async fetchContact({ commit }: { commit: Function }, id: any) {
      commit("SET_LOADING", true);
      try {
        const response = await axios.get(`${apiUrl}/api/contacts/${id}`);
        commit("SET_CONTACTS", { paginate: false, data: [response.data.data] });
        commit("SET_LOADING", false);
      } catch (error) {
        console.error("Error fetching contact:", error);
        commit("SET_LOADING", false);
      }
    },
    async updateContact(
      { commit }: { commit: Function },
      { contactId, data }: { contactId: number; data: any }
    ) {
      commit("SET_LOADING", true);
      try {
        const response = await axios.put(
          `${apiUrl}/api/contacts/${contactId}`,
          data
        );
        console.log("updateContact response", response);
        // commit('SET_CONTACTS', { paginate: false, data: [response.data.data] });
        commit("SET_LOADING", false);
        return response.data.data;
      } catch (error) {
        console.error("Error updating contact:", error);
        commit("SET_LOADING", false);
        throw error;
      }
    },
    async fetchRoles({ commit }: { commit: Function }) {
      commit("SET_LOADING", true);
      try {
        const response = await axios.get(`${apiUrl}/api/roles`);
        console.log("fetchRoles response", response);
        commit("SET_ROLES", response.data.data);
        commit("SET_LOADING", false);
      } catch (error) {
        console.error("Error fetching roles:", error);
        commit("SET_LOADING", false);
      }
    },
    resetState({ commit }: { commit: Function }) {
      commit("RESET_STATE");
    },
  },
  getters: {
    contacts: (state: any) => state.data,
    pagination: (state: any) => state.pagination,
    loading: (state: any) => state.loading,
    roles: (state: any) => state.roles,
  },
};
