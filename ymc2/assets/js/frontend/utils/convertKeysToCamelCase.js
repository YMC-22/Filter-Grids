
export function convertKeysToCamelCase(obj) {
    if (Array.isArray(obj)) {
        return obj.map(item => convertKeysToCamelCase(item));
    } else if (obj !== null && typeof obj === 'object') {
        return Object.keys(obj).reduce((acc, key) => {
            const camelKey = toCamelCase(key);
            acc[camelKey] = convertKeysToCamelCase(obj[key]);
            return acc;
        }, {});
    }
    return normalizeValue(obj);
}
function toCamelCase(str) {
    return str.replace(/_([a-z])/g, (_, letter) => letter.toUpperCase());
}

function normalizeValue(value) {
    if (typeof value === 'string') {
        const lower = value.toLowerCase();
        if (lower === 'true') return true;
        if (lower === 'false') return false;
        if (!isNaN(value) && value.trim() !== '') return Number(value);
    }
    return value;
}


