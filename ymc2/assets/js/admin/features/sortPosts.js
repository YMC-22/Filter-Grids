
export function setupSortingPosts() {
    jQuery('.selected-posts .js-post-sortable').sortable({
        axis: 'y',
        cursor: "move",
        opacity: 1,
        delay: 150,
        containment: "parent",
        start: function( event, ui ) {
            ui.item[0].closest('.js-post-sortable').classList.add('dragging');
        },
        stop: function( event, ui ) {
            ui.item[0].closest('.js-post-sortable').classList.remove('dragging');
        }
    });
}
