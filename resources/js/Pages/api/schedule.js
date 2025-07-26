import axios from "axios";

export function fetchSchedule(user_id, year, week) {
    return axios.get("/schedule/list", {
        params: {
            user_id: user_id,
            year: year,
            week: week,
        },
    });
}
