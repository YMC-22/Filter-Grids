import { YMC_CONTAINER, YMC_POST_ID, YMC_PATH, YMC_AJAX_URL, YMC_LAYOUT } from "../core/constants.js";
import { setPreloader, removePreloader } from '../utils/preloaderUtils.js';
import { YMCLoadTemplate } from '../utils/templateUtils.js';
import { setupSortTerms } from './sortTerms.js';
import { addEventToSelectedPosts, addSelectedPosts } from "./postSelection.js";
import { thickBoxTaxonomy } from './thickBoxTaxonomy.js';
import { thickBoxTerm } from './thickBoxTerm.js';


/**
 * Get Taxonomies
 *  @returns {Promise<void>}
 */
export function getTaxonomies() {
    YMC_CONTAINER.querySelector('#general .js-post-types').addEventListener('change', callbackGetTaxonomies);
}

/**
 * Get Terms
 */
export function getTerms() {
    YMC_CONTAINER.querySelector('#general .js-tax-insert').addEventListener('click', callbackGetTerms);
}

/**
 * Clear Terms
 */
export function clearTaxonomies() {
    YMC_CONTAINER.querySelector('#general .js-tax-clear').addEventListener('click', callbackClearTaxonomies);
}

/**
 * Update Taxonomies
 */
export function updatedTaxonomies() {
    YMC_CONTAINER.querySelector('#general .js-tax-updated').addEventListener('click', callbackUpdatedTaxonomies);
}

async function callbackGetTaxonomies() {
    let isCpt = confirm(`Are you sure change Post Type? IMPORTANT! Post Type Changes remove all taxonomies, terms and selected posts.`);
    if( isCpt ) {
        let option = YMC_CONTAINER.querySelectorAll('#general #ymc-post-types option:checked');
        let postTypes = Array.from(option).map(el => el.value);
        let params = {
            action: 'action_get_taxonomies',
            nonce_code : _ymc_fg_object.getTaxAjax_nonce,
            payload: JSON.stringify({
                "post_types": postTypes,
                "post_id": YMC_POST_ID
            })
        }
        try {
            setPreloader();
            _ymc_fg_object.loadedFeedPosts_page = 2;
            const response = await fetch(YMC_AJAX_URL, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams(params).toString()
            });

            if (response.ok) {
                const data = await response.json();
                let taxonomies = data.taxonomies;
                let posts = data.posts;
                let dataTax = {};
                let dataPosts = {};
                let found_posts = data.found_posts;
                let postsWrapper = YMC_CONTAINER.querySelector('#general .posts-wrapper .js-scroll-posts');
                let numberPosts = YMC_CONTAINER.querySelector('#general .posts-wrapper .feed-posts .number-posts');

                numberPosts.innerHTML = found_posts;
                // Get posts
                if(posts.length > 0) {
                    dataPosts = { isPosts: true, posts: posts }
                } else {
                    dataPosts = { noPosts: true, no_posts_found: 'No posts found' }
                }
                let tmplPosts = new YMCLoadTemplate('.ymc-main #general .js-scroll-posts', 'tmpl-feed-posts');
                tmplPosts.createAndWait(function(template, element) {
                    YMC_CONTAINER.querySelector('.feed-posts .js-scroll-posts').innerHTML = '';
                    YMC_CONTAINER.querySelector('.selected-posts .js-post-sortable').innerHTML = '';
                    YMC_LAYOUT.querySelector('.js-filter-group').innerHTML = '';
                    const selectFilterBuilder = YMC_LAYOUT.querySelector('.js-toggle-filter-builder');
                    selectFilterBuilder.value = 'default';
                    selectFilterBuilder.dispatchEvent(new Event('change'));
                    element.insertAdjacentHTML("beforeend", template(dataPosts));
                    postsWrapper.querySelectorAll('.js-add-post').forEach(item => {
                        item.removeEventListener('click', addEventToSelectedPosts);
                    });
                    addSelectedPosts();
                });

                // Get taxonomies
                if(Object.keys(taxonomies).length > 0) {
                    dataTax = { taxonomies: taxonomies, isTax: true }
                } else {
                    dataTax = { isTax: false }
                }
                let tmplTax = new YMCLoadTemplate('.ymc-main .js-tax-insert', 'tmpl-taxonomy', dataTax);
                tmplTax.create(function() {
                    removePreloader();
                    (taxonomies.length === 0) ?
                        YMC_CONTAINER.querySelector('.js-control-bar').classList.add('is-hidden') :
                        YMC_CONTAINER.querySelector('.js-control-bar').classList.remove('is-hidden');
                        YMC_CONTAINER.querySelector('.terms-wrapper .js-term-insert').innerHTML = '';
                        YMC_CONTAINER.querySelector('.terms-wrapper').classList.add('is-hidden');
                    thickBoxTaxonomy();
                });
            } else {
                console.error("Error HTTP: " + response.status);
            }
        } catch(e) {
            console.error('Error occurred:', e);
        }
    }
    else {
        let previousValues = this.dataset.previousValue.split(',');
        YMC_CONTAINER.querySelectorAll('#general #ymc-post-types option').forEach((el) => {
            el.selected = previousValues.includes(el.value);
        });
    }
}

async function callbackGetTerms(e) {
    if('INPUT' === e.target.nodeName) {
        let slug = e.target.value;
        let label = e.target.dataset.label;
        if(e.target.checked) {
            let params = {
                action: 'action_get_terms',
                nonce_code : _ymc_fg_object.getTermAjax_nonce,
                payload: JSON.stringify({
                    'slug'  : slug,
                    'label' : label,
                    'post_id': YMC_POST_ID
                })
            }
            try {
                setPreloader();
                const response = await fetch(YMC_AJAX_URL,
                    {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: new URLSearchParams(params).toString()
                    });

                if (response.ok) {
                    const data = await response.json();
                    let dataObject = data.data_obj;
                    let isTerms = (dataObject.terms.length > 0) ? true : [];

                    let dataTerms = {
                        isTerms: isTerms,
                        tax_slug: dataObject.tax_slug,
                        tax_label: dataObject.tax_label,
                        terms: dataObject.terms,
                        no_terms: 'No terms found'
                    }

                    let tmplTerm = new YMCLoadTemplate('.ymc-main .js-term-insert', 'tmpl-term');
                    tmplTerm.createAndWait(function(template, element) {
                        removePreloader();
                        element.insertAdjacentHTML("beforeend", template(dataTerms));
                        YMC_CONTAINER.querySelector('.terms-wrapper').classList.remove('is-hidden');
                        setupSortTerms();
                        thickBoxTerm();
                    });
                }

            } catch (e) {
                console.error('Error occurred:', e);
            }

        } else {
            let isTax = confirm("Are you sure you want to disable this taxonomy?");
            if( isTax ) {
                YMC_CONTAINER.querySelector(`.js-term-insert .js-tax-${slug}`).remove();
                if(YMC_CONTAINER.querySelectorAll(`.js-term-insert .cel`).length === 0) {
                    YMC_CONTAINER.querySelector('.terms-wrapper').classList.add('is-hidden');
                }
            } else {
                e.target.checked = true;
            }
        }
    }
}

async function callbackClearTaxonomies(e) {
    e.preventDefault();
    let isClear = confirm("Are you sure you want to remove all terms of taxonomies?");
    if( isClear ) {
        let params = {
            action: 'action_remove_terms',
            nonce_code : _ymc_fg_object.removeTermsAjax_nonce,
            payload: JSON.stringify({
                "post_id": YMC_POST_ID
            })
        }
        try {
            setPreloader();
            const response = await fetch(YMC_AJAX_URL,
                {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: new URLSearchParams(params).toString()
                });

            if (response.ok) {
                const data = await response.json();
                removePreloader();
                YMC_CONTAINER.querySelectorAll('.terms-wrapper .terms-grid .cel').forEach(el => {
                    el.remove();
                });
                YMC_CONTAINER.querySelector('.terms-wrapper').classList.add('is-hidden');
                YMC_CONTAINER.querySelectorAll('#general .js-tax-insert .js-tax-checkbox').forEach((el) => {
                    el.checked = false;
                });
            }

        } catch (e) {
            console.error('Error occurred:', e)
        }
    }
}

async function callbackUpdatedTaxonomies(e) {
    e.preventDefault();
    let option = YMC_CONTAINER.querySelectorAll('#general #ymc-post-types option:checked');
    let postTypes = Array.from(option).map(el => el.value);
    let params = {
        action: 'action_updated_taxonomies',
        nonce_code : _ymc_fg_object.updatedTaxAjax_nonce,
        payload: JSON.stringify({
            "post_types": postTypes
        })
    }
    try {
        setPreloader();
        const response = await fetch(YMC_AJAX_URL,
            {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams(params).toString()
            });

        if (response.ok) {
            const data = await response.json();
            let allTaxonomies = data.taxonomies;
            let taxExist = [];
            let dataTax = {}
            let taxTotal = Object.keys(allTaxonomies).length;
            let tmplTerm = new YMCLoadTemplate('.ymc-main .js-tax-insert', 'tmpl-taxonomy');

            YMC_CONTAINER.querySelectorAll('.js-tax-insert .taxonomies-list__item').forEach((el) => {
                let slug = el.dataset.taxName;
                taxExist.push(slug);
            });

            if(taxTotal > 0) {
                // Add new taxonomy
                if(taxTotal > taxExist.length) {
                    for (let key in allTaxonomies) {
                        if(!taxExist.includes(key)) {
                            dataTax = { 'taxonomies': { [key]: allTaxonomies[key] }, 'isTax': true }
                            tmplTerm.createAndWait(function(template, element) {
                                element.insertAdjacentHTML("beforeend", template(dataTax));
                            });
                        }
                    }
                }
                // Delete taxonomy
                if(taxTotal < taxExist.length) {
                    taxExist.forEach((item) => {
                        if(! Object.keys(allTaxonomies).includes(item)) {
                            YMC_CONTAINER.querySelector('.js-tax-insert [data-tax-name="'+item+'"]')?.remove();
                            YMC_CONTAINER.querySelector('.js-term-insert .js-tax-'+item+'')?.remove();
                        }
                    });
                }
            }
            removePreloader();
        }

    } catch (e) {
        console.error('Error occurred:', e)
    }
}
