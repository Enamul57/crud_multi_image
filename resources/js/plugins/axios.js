import axios from 'axios';

const api = axios.create({
    baseURL:"http://127.0.0.1:8000",
    timeout:5000,
    headers:{
        "Content-Type":"application/json",
        Accept:"application/json",
    },
    withCredentials: true,
    withXSRFToken: true,
    xsrfCookieName: "XSRF-TOKEN",
    xsrfHeaderName: "X-XSRF-TOKEN",
});

export default api;