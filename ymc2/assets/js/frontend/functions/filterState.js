
export const globalSelectedTerms = new Set();

export function getAllSelectedTermIds() {
    return Array.from(globalSelectedTerms);
}
export function clearGlobalSelectedTerms() {
    globalSelectedTerms.clear();
}