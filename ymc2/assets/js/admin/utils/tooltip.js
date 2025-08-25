export function tooltip($) {
    $('.js-btn-tooltip').tooltip({
            classes: {
                "ui-tooltip": "ymc-tooltip",
                "ui-tooltip-content": "ymc-tooltip-content",
            },
            position: {
                my: "center bottom-20",
                at: "center",
                collision: "fit"
            },
            content: function () {
                return $(this).attr("data-tooltip-html");
            }
        }
    )
}

