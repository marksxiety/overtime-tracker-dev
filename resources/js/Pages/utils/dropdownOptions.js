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
