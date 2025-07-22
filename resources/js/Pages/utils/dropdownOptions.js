const currentYear = new Date().getFullYear();
export const years = [];
for (let i = 0; i < 7; i++) {
    const year = currentYear - 1 + i;
    years.push({
        label: String(year),
        value: String(year),
    });
}

export const weeks = [];
for (let w = 1; w < 52; w++) {
    weeks.push({
        label: `Week ${w}`,
        value: w,
    });
}

export function currentWeek(date = new Date()) {
  const firstDayOfYear = new Date(date.getFullYear(), 0, 1);
  const pastDaysOfYear = (date - firstDayOfYear + (firstDayOfYear.getTimezoneOffset() - date.getTimezoneOffset()) * 60 * 1000) / 86400000;

  // adjust if the starting of the week is sunday or monday
  // just add + 1 if starting on monday
  const weekNumber = Math.ceil((pastDaysOfYear + firstDayOfYear.getDay()) / 7);
  return weekNumber;
}