import axios from 'axios';

axios.defaults.baseURL = '/api/v1';

const api = {
    getPoll: uuid => axios.get(`/polls/${uuid}`),
    createPoll: data => axios.post(`/polls`, data),
    getVotes: uuid => axios.get(`/polls/${uuid}/votes`),
    vote: (uuid, data) => axios.post(`/polls/${uuid}/votes`, data),
}

export default api;
