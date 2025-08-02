export function getEmployeeOvertimeStats(requests) {
    let totalovertimehours = 0;
    let pendingrequests = 0;
    let rejectedrequests = 0;

    requests.forEach((item) => {
        if (item.status.toUpperCase() === "PENDING") {
            pendingrequests++;
        }

        if (item.status.toUpperCase() === "DISAPPROVED") {
            rejectedrequests++;
        }

        if (item.status.toUpperCase() === "APPROVED") {
            totalovertimehours += item.hours;
        }
    });

    return { totalovertimehours, pendingrequests, rejectedrequests };
}

// for approver getApproverOvertimeRequests()
// for admin computeGlobalOvertimeStats()
