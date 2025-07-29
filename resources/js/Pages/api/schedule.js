import axios from "axios";

export function fetchSchedule(year, week) {
    return axios.get("/schedule/list", {
        params: {
            year: year,
            week: week,
        },
    });
}

export function submitSchedule(info) {
    return axios.post("/schedule/submit", {
        schedule: info,
    });
}

export function fetchUserSchedule(year, month, day) {
    return axios.get("/schedule/user", {
        params: { year, month, day },
    });
}