export function identifyColorStatus(status) {
    switch (status.toLowerCase()) {
        case 'pending':
            return 'warning'
        case 'canceled':
            return 'error'      
        case 'disapproved':
            return 'error'
        case 'approved':
            return 'success'
        case 'filed':
            return 'info'
        default:
            return 'default'
    }
}