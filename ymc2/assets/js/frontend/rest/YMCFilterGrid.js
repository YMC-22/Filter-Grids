import {updateDataParams} from "../utils/updateDataParams.js";
import {fetchFilteredPosts} from "../utils/fetchFilteredPosts.js";
import { openPopup } from "../functions/initPopupTriggers.js";


(function () {
    if (typeof window.YMCFilterGrid !== 'undefined') {
        console.error('YMCFilterGrid is existed.');
        return;
    }
    window.YMCFilterGrid = {

        config: {
            container: '#ymc-filter-1',
            filters: {}
        },

        init(container) {
            if (typeof container !== 'string') {
                console.error('YMCFilterGrid.init: container must be a selector string.');
                return;
            }
            const element = document.querySelector(container);
            if (!element) {
                throw new Error(`YMCFilterGrid.init: container "${container}" not found`);
            }
            this.config.container = element;
        },

        setFilter(paged, options = {}) {
            const { sendRequest = true } = options;
            const container = this.config.container;
            updateDataParams(container, { paged, ...this.config.filters });
            if (sendRequest) {
                fetchFilteredPosts(container).then(r => {});
            }
        },

        filterByTerm(taxonomy, termId, sendRequest= true) {
            if (typeof taxonomy !== 'string' || typeof termId !== 'string') {
                console.error('filterByTerm: Both arguments must be strings.');
                return;
            }
            let taxArray = taxonomy
                .split(',')
                .map(item => item.trim())
                .filter(item => item.length > 0);

            let termsArray = termId
                .split(',')
                .map(item => item.trim())
                .filter(item => item.length > 0);

            if (taxArray.length === 0 || termsArray.length === 0) {
                console.error('filterByTerm: taxonomy and termId must not be empty.');
                return;
            }
            this.config.filters['taxonomies'] = taxArray;
            this.config.filters['terms'] = termsArray;
            this.setFilter(1, { sendRequest: sendRequest });
        },

        filterByPostStatus(status, sendRequest = true) {
            if (typeof status !== 'string') {
                console.error('filterByPostStatus: status must be a string.');
                return;
            }

            this.config.filters['post_status'] = status;
            this.setFilter(1, { sendRequest });
        },

        sortPosts(orderBy, orderDirection = 'asc', options = {}) {
            const { metaKey = '',
                    metaValue = 'meta_value',
                    multipleFields = null,
                    sendRequest = true } = options;

            this.config.filters['post_order_by'] = orderBy;
            this.config.filters['post_order'] = orderDirection;

            if (orderBy === 'meta_key') {
                this.config.filters['order_meta_key'] = metaKey;
                this.config.filters['order_meta_value'] = metaValue;
            } else if (orderBy === 'multiple_fields' && Array.isArray(multipleFields)) {
                this.config.filters['post_order_by_multiple'] = { fields: multipleFields };
            }

            this.setFilter(1, { sendRequest });
        },

        filterByMeta(metaQuery, relation = 'AND', sendRequest = true) {
            if (!Array.isArray(metaQuery)) {
                console.error('filterByMeta: metaQuery must be an array.');
                return;
            }
            const rel = (relation === 'AND' || relation === 'OR') ? relation : 'AND';

            this.config.filters['meta_query'] = metaQuery;
            this.config.filters['meta_query_relation'] = rel;

            this.setFilter(1, { sendRequest });
        },

        filterByDate(dateQuery = {}, sendRequest = true) {
            if (typeof dateQuery !== 'object' || Array.isArray(dateQuery)) {
                console.error('filterByDate: Argument must be an object.');
                return;
            }

            this.config.filters['date_query'] = dateQuery;
            this.setFilter(1, { sendRequest });
        },

        search(keyword, termIds = [], sendRequest = true) {
            if (typeof keyword !== 'string') {
                console.error('search: keyword must be a string.');
                return;
            }
            this.config.filters['search'] = keyword.trim();

            if (Array.isArray(termIds)) {
                termIds = termIds.map(id => id.toString().trim()).filter(id => id.length > 0);
            } else if (typeof termIds === 'string') {
                termIds = termIds.split(',').map(id => id.trim()).filter(id => id.length > 0);
            } else {
                console.error('search: termIds must be an array or a comma-separated string.');
                return;
            }

            if (termIds.length > 0) {
                this.config.filters['terms'] = termIds;
            } else {
                delete this.config.filters['terms'];
            }

            this.setFilter(1, { sendRequest });
        },

        choicePosts(selectedPosts = [], excludedPosts = 'no', sendRequest = true) {
            if (Array.isArray(selectedPosts)) {
                selectedPosts = selectedPosts.map(id => id.toString().trim()).filter(id => id.length > 0);
            } else if (typeof selectedPosts === 'string') {
                selectedPosts = selectedPosts.split(',').map(id => id.trim()).filter(id => id.length > 0);
            } else {
                console.error('choicePosts: selectedPosts must be an array or a comma-separated string.');
                return;
            }

            if (selectedPosts.length === 0) {
                console.error('choicePosts: selectedPosts cannot be empty.');
                return;
            }
            this.config.filters['selected_posts'] = selectedPosts;
            this.config.filters['excluded_posts'] = excludedPosts === 'yes' ? 'yes' : 'no';
            this.setFilter(1, { sendRequest });
        },

        getPosts() {
            const container = this.config.container;
            if (!container) {
                console.error('getPosts: container is not initialized.');
                return;
            }
            updateDataParams(container, { paged: 1, ...this.config.filters });
            fetchFilteredPosts(container).then(r => {});
        },

        pageUpdated(page) {
            if (typeof page !== 'number' || isNaN(page) || page < 1) {
                console.error('PageUpdated: page must be a positive number.');
                return;
            }
            const container = this.config.container;
            if (!container) {
                console.error('PageUpdated: container is not initialized.');
                return;
            }
            updateDataParams(container, { paged: page, ...this.config.filters });
            fetchFilteredPosts(container).then(r => {});
        },

        openFilterPopup(postId) {
            if (typeof postId !== 'string' && typeof postId !== 'number') {
                console.error('openFilterPopup: postId must be a string or number.');
                return;
            }
            const triggerElement = document.querySelector(`.js-ymc-popup-trigger[data-post-id="${postId}"]`);

            if (!triggerElement) {
                console.error(`openFilterPopup: trigger element for post_id=${postId} not found.`);
                return;
            }
            const fakeEvent = {
                preventDefault: () => {}
            };

            openPopup.call(triggerElement, fakeEvent);
        }

    };

})();


