import axios from "axios"

const apiUrl = import.meta.env.API_URL || 'http://localhost:8000'

const initialState = () => {
  return {
    status: null,
    loading: false,
    data: [],
    pagination: null,
  }
}

export default {
    namespaced: true,
    state: initialState(),
    mutations: {
        SET_CALL_LOGS(state: any, payload: any) {
            if (payload.paginate) {
                state.data = payload.call_logs.data
                state.pagination = {
                    from: payload.call_logs.from,
                    to: payload.call_logs.to,
                    links: payload.call_logs.links,
                    current_page: payload.call_logs.current_page,
                }
                console.log('setState', state)
            } else {
                state.data = payload
                state.pagination = null
            }
        },
        SET_LOADING(state: any, loading: boolean) {
            state.loading = loading
        },
        RESET_STATE(state: any) {
            Object.assign(state, initialState())
        }
    },
    actions: {
        async fetchCallLogs({ commit }: { commit: Function }, params: any = {}) {
            console.log('fetchCallLogs vuex', params)
            commit('SET_LOADING', true)
            try {
                const response = await axios.get(`${apiUrl}/api/call-logs`, { params })
                console.log('response', response.data.data)
                commit('SET_CALL_LOGS', response.data.data)
                commit('SET_LOADING', false)
                
                return response.data.data
            } catch (error) {
                console.error("Error fetching call logs:", error)
                commit('SET_LOADING', false)
                throw error
            }
        },
        async createCallLog({ commit }: { commit: Function }, callLog: any) {
            commit('SET_LOADING', true)
            try {
                const response = await axios.post(`${apiUrl}/api/call-logs`, callLog)
                console.log('response', response.data.data)
                // commit('SET_CALL_LOGS', response.data.data)
                commit('SET_LOADING', false)
                
                return response.data.data
            } catch (error) {
                console.error("Error creating call log:", error)
                commit('SET_LOADING', false)
                throw error
            }
        }

    },
    getters: {
        callLogs: (state: any) => state.data,
        loading: (state: any) => state.loading,
        pagination: (state: any) => state.pagination,
    }
}