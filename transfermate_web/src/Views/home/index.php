<?php require_once __DIR__ . '/../partial/header.php'; ?>

    <div class="container mt-5">
        <h2 class="text-center">Search Books</h2>
        <form id="authorBooksForm" class="mt-5 mb-5">
            <div class="row justify-content-center">
                <div class="col-4">
                    <input type="text"
                           id="author"
                           name="author"
                           class="form-control"
                           placeholder="Author"
                           value="<?php echo isset($_GET['author']) ? trim($_GET['author']) : '' ?>"/>
                </div>
                <div class="col-auto">
                    <button type="submit" id="ukPostsFormSubmit" class="btn btn-primary">Search</button>
                </div>
                <div class="col-auto">
                    <button type="button" id="clearButton" class="btn btn-success">Clear</button>
                </div>
            </div>
        </form>

        <div class="searchResults container mt-5">
            <table class="table table-striped">
                <thead>
                <tr>
                    <th scope="col">Name</th>
                    <th scope="col">Author</th>
                </tr>
                </thead>
                <tbody>

                <?php
                $searchParams = array();
                if (isset($_GET['author']) && count($_GET) === 1) {
                    $searchParams = array('author' => trim($_GET['author']));
                }

                $books = $this->model->search($searchParams);
                if (count($books) > 0) {
                    foreach ($books as $book) {
                        ?>
                        <tr>
                            <td>
                                <?php echo $book['name']; ?>
                            </td>
                            <td>
                                <?php echo $book['author']; ?>
                            </td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="2">
                            No results
                        </td>
                    </tr>
                    <?php
                }
                ?>

                </tbody>
            </table>
        </div>
    </div>

<?php require_once __DIR__ . '/../partial/footer.php'; ?>