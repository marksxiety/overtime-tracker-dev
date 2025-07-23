import axios from "axios";

export function fetchShiftList() {
    return axios.get("/shift/list");
}