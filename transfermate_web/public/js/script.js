$(document).ready(function () {
    /**
     * Reset form.
     */
    $("#clearButton").on('click', function (event) {
        event.stopPropagation();
        event.preventDefault();

        $(':input', '#searchBooksForm').not(':button, :submit, :reset').val('');

        removeUrlParam('search');

        location.reload();
    });

    /**
     * Remove parameter from url.
     *
     * @param key Parameter name.
     */
    function removeUrlParam(key) {
        if (history.pushState) {
            let searchParams = new URLSearchParams(window.location.search);
            searchParams.delete(key);
            let newurl = window.location.protocol + "//" + window.location.host + window.location.pathname;
            window.history.pushState({path: newurl}, '', newurl);
        }
    }
});