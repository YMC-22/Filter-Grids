import { getDataParams, updateDataParams } from "./updateDataParams.js";
import { fetchFilteredPosts } from "./fetchFilteredPosts.js";

/**
 * Observer for IntersectionObserver
 * @type {IntersectionObserver}
 */
export function initInfiniteScroll(container) {

    /**
     * Options for IntersectionObserver
     * @type {{root: null, rootMargin: string, threshold: number}}
     */
    const optionsInfinityScroll = {
        root: null,
        rootMargin: '0px',
        threshold: 0.8
    }

    return new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const dataParams = getDataParams(container);
                const paged = ++dataParams.paged;

                updateDataParams(container, {
                    paged: paged
                });
                fetchFilteredPosts(container,'append');
                observer.unobserve(entry.target);
            }
        })
    }, optionsInfinityScroll);

}