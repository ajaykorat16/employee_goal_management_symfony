let loadingMore = false;
var swiper;

var Main = Main || {};
(function($, module) {

    //----------- goalsList-load-more ---------------//
    $(window).on('scroll', function () {
        let $table = $("#employeeGoals");
        let totalItems = $("#employeeGoalsList").data('total-items');
        let userId = $("employeeGoalsList").data('user-id');
        let url = 'admin_goal_load_more';
        module.listingLoader($table, totalItems, url);
    });
    //----------- /goalsList-load-more ---------------//

    module.listingLoader = function($table, totalItems, url) {
        let scrolled = window.scrollY;
        let availableScroll = Math.max($(document).height() - $(window).height(), 0);
        let scrollPercentage = Math.round(scrolled / availableScroll * 100);

        if (scrollPercentage > 90 && !loadingMore) {
            let loadedItems = $table.find('tbody tr:not(.no-results-row)').length;
            let hasMore = totalItems > loadedItems;

            if (hasMore) {
                loadingMore = true;
                const loadMoreUrl = Routing.generate(url, { offset: loadedItems });

                $(".loading-image").removeClass("visibility-hidden");

                $.get(loadMoreUrl, function(data, status, jqXHR) {
                    if (jqXHR.getResponseHeader('X-Authenticated') == 'NO') {
                        return window.location.reload();
                    }

                    if (data.content && data.content.length > 0) {
                        data.content.forEach(function(html) {
                            $table.find('tbody').append(html);
                        });
                    }

                    loadingMore = false;
                    $(".loading-image").addClass("visibility-hidden");
                }).fail(function(xhr, status, error) {
                    console.error("Error loading more goals:", error);
                    loadingMore = false;
                    $(".loading-image").addClass("visibility-hidden");
                });
            }
        }
    };

})(jQuery, Main);