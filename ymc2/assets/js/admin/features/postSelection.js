import { YMC_CONTAINER, YMC_POST_ID, YMC_PATH, YMC_AJAX_URL } from "../core/constants.js";
import { YMCLoadTemplate } from '../utils/templateUtils.js';
import { setupSortingPosts as sortSelectedPosts } from './sortPosts.js';


/**
 * Add Selected Posts
 */
export function addEventToSelectedPosts() {
    let postId= this.dataset.postId;
    let postTitle = this.querySelector('.post-title').innerText;
    let dataPosts = {
        postId: postId,
        postTitle: postTitle
    }
    this.classList.add('is-disabled');
    let tmplPosts = new YMCLoadTemplate('.ymc-main #general .selected-posts .list-posts', 'tmpl-selected-posts');
    tmplPosts.createAndWait(function(template, element) {
        element.insertAdjacentHTML("beforeend", template(dataPosts));
        YMC_CONTAINER.querySelector('#general .selected-posts .number-posts').innerText =
        YMC_CONTAINER.querySelectorAll('#general .selected-posts .list-posts .post-selected').length;
        sortSelectedPosts();
        removeSelectedPosts();
    });
}

export function addSelectedPosts() {
    YMC_CONTAINER.querySelectorAll('#general .feed-posts .js-scroll-posts .js-add-post').forEach(item => {
        item.addEventListener('click', addEventToSelectedPosts);
    });
}

export function removeSelectedPosts() {
    YMC_CONTAINER.querySelectorAll('#general .selected-posts .post-selected .js-post-remove').forEach((item) => {
        item.addEventListener('click', function () {
            let postId = this.dataset.postId;
            this.closest('.post-selected').remove();
            YMC_CONTAINER.querySelectorAll('#general .feed-posts .js-add-post').forEach((item) => {
                if( postId === item.dataset.postId) {
                    item.classList.remove('is-disabled');
                }
            });
            YMC_CONTAINER.querySelector('#general .selected-posts .number-posts').innerText =
            YMC_CONTAINER.querySelectorAll('#general .selected-posts .list-posts .post-selected').length;
        });
    });
}

