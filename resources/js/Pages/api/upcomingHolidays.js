// upcomingHolidays.js
import axios from "axios";

let currentYear = new Date().getFullYear();
let currentDate = new Date().toLocaleDateString("en-CA", {
    timeZone: "Asia/Manila",
});

function formatHolidayDate(date) {
    let dateInstance = new Date(date);

    return dateInstance.toLocaleDateString("en-US", {
        month: "long",
        day: "numeric",
        year: "numeric",
    });
}

let url = `https://date.nager.at/api/v3/publicholidays/${currentYear}/PH`;

export default async function fetchUpcomingHolidays() {
    let res = await axios.get(url);
    let response = res.data;
    let upcoming_holidays = [];

    if (Array.isArray(response) && response.length > 0) {
        upcoming_holidays = response
            .filter((holiday) => holiday.date >= currentDate)
            .map((holiday) => ({
                date: formatHolidayDate(holiday.date),
                localName: holiday.localName,
                name: holiday.name,
            }));
    }

    return upcoming_holidays;
}
