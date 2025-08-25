export const ymcHooks = {
    normalize(hookName) {
        return hookName.replace(/\//g, '.');
    },

    addAction(hookName, callback, priority = 10) {
        wp.hooks.addAction(this.normalize(hookName), 'ymc-filter', callback, priority);
    },

    doAction(hookName, ...args) {
        wp.hooks.doAction(this.normalize(hookName), ...args);
    },

    addFilter(hookName, callback, priority = 10) {
        wp.hooks.addFilter(this.normalize(hookName), 'ymc-filter', callback, priority);
    },

    applyFilters(hookName, value, ...args) {
        return wp.hooks.applyFilters(this.normalize(hookName), value, ...args);
    }
};

if (
    !window.ymcHooks ||
    typeof window.ymcHooks.addAction !== 'function' ||
    typeof window.ymcHooks.doAction !== 'function'
) {
    window.ymcHooks = ymcHooks;
}



