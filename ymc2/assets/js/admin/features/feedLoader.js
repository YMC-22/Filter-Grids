import { YMC_CONTAINER, YMC_POST_ID, YMC_PATH, YMC_AJAX_URL } from "../core/constants.js";
import { removePreloader, setPreloader } from "../utils/preloaderUtils.js";
import { YMCLoadTemplate } from "../utils/templateUtils.js";
import { addEventToSelectedPosts, addSelectedPosts } from "./postSelection.js";


/**
 * Load Selected Posts
 */
export function loadedFeedPosts() {
    YMC_CONTAINER.querySelector('#general .posts-wrapper .js-scroll-posts').addEventListener('scroll', callbackLoadedFeedPosts);
}

/**
 * Search Posts
 */
export function setupSearchFeedPosts() {
    YMC_CONTAINER.querySelector('#general .posts-wrapper .js-field-search').addEventListener('input', callbackIsVisibleClearButton);
    YMC_CONTAINER.querySelector('#general .posts-wrapper .js-btn-search').addEventListener('click', callbackSearchFeedPosts);
    YMC_CONTAINER.querySelector('#general .posts-wrapper .js-btn-clear').addEventListener('click', callbackClearSearchFeedPosts);
}

/**
 * Callback Functions
 */

function callbackLoadedFeedPosts() {
    const postsWrapper = YMC_CONTAINER.querySelector('#general .posts-wrapper .js-scroll-posts');
    const optionsScroll = {
        root: postsWrapper,
        rootMargin: '0px',
        threshold: 0.9
    }
    const postsObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting && !entry.target.classList.contains('is-loaded')) {
                entry.target.classList.add('is-loaded');
                let postTypes = Array.from(YMC_CONTAINER.querySelectorAll('#general #ymc-post-types option:checked')).map(el => el.value);
                let params = {
                    action: 'action_selected_posts',
                    nonce_code : _ymc_fg_object.selectPostsAjax_nonce,
                    payload: JSON.stringify({
                        "post_types": postTypes,
                        "paged" : _ymc_fg_object.loadedFeedPosts_page,
                        "post_id" : YMC_POST_ID,
                        "posts_per_page" : 20
                    })
                }
                setPreloader('#general .posts-wrapper .js-scroll-posts');
                fetch(YMC_AJAX_URL, {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: new URLSearchParams(params).toString()
                }).then(function(response) {
                    if(response.ok === true) {
                        return response.json();
                    } else {
                        console.error('Error occurred:', response.status);
                    }
                }).then((data) => {
                    let dataPosts = {};
                    if(data.posts.length > 0) {
                        dataPosts = { 'isPosts': true, 'posts': data.posts }
                    }

                    let tmplPosts = new YMCLoadTemplate('.ymc-main #general .js-scroll-posts', 'tmpl-feed-posts');
                    tmplPosts.createAndWait(function(template, element) {
                        removePreloader();
                        element.insertAdjacentHTML("beforeend", template(dataPosts));
                        _ymc_fg_object.loadedFeedPosts_page++;
                        postsWrapper.querySelectorAll('.js-add-post').forEach(item => {
                            item.removeEventListener('click', addEventToSelectedPosts);
                        });
                        addSelectedPosts();
                    });
                }).catch(error => console.error(error));
            }
        })
    }, optionsScroll);
    postsObserver.observe(postsWrapper.querySelector('li:last-child'));
}

async function callbackSearchFeedPosts() {
    let postsWrapper = YMC_CONTAINER.querySelector('#general .posts-wrapper .js-scroll-posts');
    let numberPosts = YMC_CONTAINER.querySelector('#general .posts-wrapper .feed-posts .number-posts');
    let keyword = YMC_CONTAINER.querySelector('#general .posts-wrapper .js-field-search').value.toLowerCase();
    if( keyword.length > 0 ) {
        let option = YMC_CONTAINER.querySelectorAll('#general #ymc-post-types option:checked');
        let postTypes = Array.from(option).map(el => el.value);
        let params = {
            action: 'action_search_feed_posts',
            nonce_code : _ymc_fg_object.searchFeedPostsAjax_nonce,
            payload: JSON.stringify({
                "post_types": postTypes,
                "post_id" : YMC_POST_ID,
                'phrase': keyword
            })
        }
        try {
            setPreloader('#general .posts-wrapper .js-scroll-posts');
            this.classList.add('button--disabled');
            const response = await fetch(YMC_AJAX_URL, {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: new URLSearchParams(params).toString()
            });

            if (response.ok) {
                let data = await response.json();
                let dataPosts = {};
                let found_posts = data.found_posts;
                if(data.posts.length > 0) {
                    dataPosts = { 'isPosts': true, 'posts': data.posts }
                } else {
                    dataPosts = { 'noPosts': true, 'no_posts_found': 'No posts found' }
                }

                numberPosts.innerHTML = found_posts;
                this.classList.remove('button--disabled');
                postsWrapper.removeEventListener('scroll', callbackLoadedFeedPosts);
                postsWrapper.scrollTo({ top: 0, left: 0, behavior: 'smooth' });

                let tmplPosts = new YMCLoadTemplate('.ymc-main #general .js-scroll-posts', 'tmpl-feed-posts');
                tmplPosts.createAndWait(function(template, element) {
                    removePreloader();
                    element.innerHTML = template(dataPosts);
                    postsWrapper.querySelectorAll('.js-add-post').forEach(item => {
                        item.removeEventListener('click', addEventToSelectedPosts);
                    });
                    addSelectedPosts();
                });
            }
        } catch(e) {
            console.error('Error occurred:', e)
        }
    }
}

function callbackIsVisibleClearButton() {
    if( this.value.length > 0 ) {
        this.nextElementSibling.classList.remove('is-hidden')
    } else {
        this.nextElementSibling.classList.add('is-hidden');
        callbackClearSearchFeedPosts().then(r => {});
    }
}

async function callbackClearSearchFeedPosts() {
    let postsWrapper = YMC_CONTAINER.querySelector('#general .posts-wrapper .js-scroll-posts');
    let numberPosts = YMC_CONTAINER.querySelector('#general .posts-wrapper .feed-posts .number-posts');
    let searchField = YMC_CONTAINER.querySelector('#general .posts-wrapper .js-field-search');
    let btnClear = YMC_CONTAINER.querySelector('#general .posts-wrapper .js-btn-clear');
    let option = YMC_CONTAINER.querySelectorAll('#general #ymc-post-types option:checked');
    let postTypes = Array.from(option).map(el => el.value);
    const url = YMC_AJAX_URL;
    const selector = '#general .posts-wrapper .js-scroll-posts';
    const params = new URLSearchParams({
        action: 'action_selected_posts',
        nonce_code : _ymc_fg_object.selectPostsAjax_nonce,
        payload: JSON.stringify({
            "post_types": postTypes,
            "paged" : 1,
            "post_id" : YMC_POST_ID,
            "posts_per_page" : 20
        })
    });

    btnClear.classList.add('is-hidden');
    searchField.value = '';
    searchField.focus();

    try {
        setPreloader(selector);
        const response = await fetch(url, {
            method: 'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: params.toString()
        });

        if (response.ok) {
            let data = await response.json();
            let dataPosts = {};
            let found_posts = data.found_posts;
            if(data.posts.length > 0) {
                dataPosts = { 'isPosts': true, 'posts': data.posts }
            } else {
                dataPosts = { 'noPosts': true, 'no_posts_found': 'No posts found' }
            }

            numberPosts.innerHTML = found_posts;
            postsWrapper.scrollTo({ top: 0, left: 0, behavior: 'smooth' });

            let tmplPosts = new YMCLoadTemplate('.ymc-main #general .js-scroll-posts', 'tmpl-feed-posts');
            tmplPosts.createAndWait(function(template, element) {
                element.innerHTML = template(dataPosts);
                _ymc_fg_object.loadedFeedPosts_page = 2;
                postsWrapper.querySelectorAll('.js-add-post').forEach(item => {
                    item.removeEventListener('click', addEventToSelectedPosts);
                });
                addSelectedPosts();
                postsWrapper.addEventListener('scroll', callbackLoadedFeedPosts);
            });
        }

    } catch(e) {
        console.error('Error occurred:', e)
    } finally {
        removePreloader(selector);
    }

}

