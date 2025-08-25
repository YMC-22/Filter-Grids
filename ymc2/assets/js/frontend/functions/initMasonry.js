import { ymcHooks } from "../utils/hooks.js";

export function initMasonry(filter) {
    if (filter && filter.dataset.gridStyle === 'masonry') {
        let filterID = filter.dataset.filterId;

        ymcHooks.addAction(`ymc/grid/after_update_${filterID}`, function(data, container) {
            let postsGrid = container.querySelector('.js-ajax-content');
            let posts_count = data.posts_count;

            postsGrid.classList.add('posts-grid--masonry');
            masonryGrid(postsGrid, filterID, posts_count);
        });
    }
}

/**
 * Function to set up Masonry Grid layouts.
 *
 * @param container
 * @param filterID
 * @param posts_count
 */
function masonryGrid(container, filterID, posts_count) {

    // Default Parameters
    let staticContent = false;
    let items         = posts_count || 4;
    let gutter        = 20;
    let maxColumns    = 4;
    let useMin        = false;
    let useTransform  = true;
    let animate       = false;
    let center        = false;

    staticContent = ymcHooks.applyFilters('ymc/grid/masonry/staticContent', staticContent);
    staticContent = ymcHooks.applyFilters('ymc/grid/masonry/staticContent_'+ filterID, staticContent);

    gutter = ymcHooks.applyFilters('ymc/grid/masonry/gutter', gutter);
    gutter = ymcHooks.applyFilters('ymc/grid/masonry/gutter_'+ filterID, gutter);

    maxColumns = ymcHooks.applyFilters('ymc/grid/masonry/maxColumns', maxColumns);
    maxColumns = ymcHooks.applyFilters('ymc/grid/masonry/maxColumns_'+ filterID, maxColumns);

    useMin = ymcHooks.applyFilters('ymc/grid/masonry/useMin', useMin);
    useMin = ymcHooks.applyFilters('ymc/grid/masonry/useMin_'+ filterID, useMin);

    useTransform = ymcHooks.applyFilters('ymc/grid/masonry/useTransform', useTransform);
    useTransform = ymcHooks.applyFilters('ymc/grid/masonry/useTransform_'+ filterID, useTransform);

    animate = ymcHooks.applyFilters('ymc/grid/masonry/animate', animate);
    animate = ymcHooks.applyFilters('ymc/grid/masonry/animate_'+ filterID, animate);

    center = ymcHooks.applyFilters('ymc/grid/masonry/center', center);
    center = ymcHooks.applyFilters('ymc/grid/masonry/center_'+ filterID, center);

    let magicGrid = new MagicGrid({
        container: container,
        static: staticContent,
        items: items,
        gutter: gutter,
        maxColumns: maxColumns,
        useMin: useMin,
        useTransform: useTransform,
        animate: animate,
        center: center
    });

    magicGrid.onReady(() => {
        ymcHooks.doAction('ymc/grid/masonry/magicGrid_ready', container);
        ymcHooks.doAction('ymc/grid/masonry/magicGrid_ready_'+ filterID, container);
    });

    magicGrid.onPositionComplete(() => {
        ymcHooks.doAction('ymc/grid/masonry/magicGrid_position_complete', container);
        ymcHooks.doAction('ymc/grid/masonry/magicGrid_position_complete_'+ filterID, container);
    });

    magicGrid.listen();
    magicGrid.positionItems();

}