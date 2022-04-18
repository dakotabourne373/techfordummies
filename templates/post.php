<!-- Dakota Bourne and Matthew Reid -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="description" content="About page for connect uva">
    <meta name="author" content="Dakota and Matthew">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="uva, research, collaboration, professors">

    <link rel="icon" type="image/x-icon" href="/db2nb/techfordummies/favicon.ico">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= "{$this->url}/styles/main.css" ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>


    <title>TechForDummies</title>
</head>

<body class="bg">
    <?php include "templates/header.php" ?>

    <div class="container landing">

        <div class="container-fluid">

            <!-- post section template -->
            <div class="row border border-dark rounded bg-light pt-2 pb-2 mb-2" id="post">
                <div class="col-md-2 p-1 border-secondary border-end align-items-center" style="text-align: center">
                    <img alt="Bootstrap Image Preview" width="75" height="75" src="https://www.layoutit.com/img/sports-q-c-140-140-3.jpg" class="rounded-circle" />
                    <h4 id="postUsername">
                        USer
                    </h4>
                    <h4 id="postDate">
                        4/14/2022
                    </h4>
                </div>
                <div class="col-md-9 p-1">
                    <h4 id="postTitle">
                        Why fart smell?
                    </h4>
                    <p id="postText">
                        This is a big question
                    </p>
                </div>
                <div class="col-sm-1" style="text-align: right">
                    <div id="dangerdanger">
                    </div>
                    <span id="postLikes"></span>
                    <button aria-label="postLike" type="button" class="btn btn-outline-secondary mb-1" id="postLike">
                        <i class="bi" id="likeIcon"></i>
                    </button>
                    <button aria-label="bookmark" type="button" class="btn btn-outline-secondary" id="bookmark">
                        <i class="bi" id="bookmarkIcon"></i>
                    </button>
                </div>
            </div>
        </div>

        <!-- comment section template -->
        <div class="container-fluid pl-3" id="commentDiv">
            <div class="row" id="comment">
                <div class="col-md-1" style="display: flex; justify-content: center; align-items: center;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="100%" fill="currentColor" class="bi bi-arrow-return-right" viewBox="0 0 16 16">
                        <path fill-rule="evenodd" d="M1.5 1.5A.5.5 0 0 0 1 2v4.8a2.5 2.5 0 0 0 2.5 2.5h9.793l-3.347 3.346a.5.5 0 0 0 .708.708l4.2-4.2a.5.5 0 0 0 0-.708l-4-4a.5.5 0 0 0-.708.708L13.293 8.3H3.5A1.5 1.5 0 0 1 2 6.8V2a.5.5 0 0 0-.5-.5z" />
                    </svg>
                </div>
                <div class="col row border border-dark rounded bg-light pt-2 pb-2 mb-2 ml-3">
                    <div class="col-md-2 p-1 border-secondary border-end">
                        <img alt="Bootstrap Image Preview" width="75" height="75" src="https://www.layoutit.com/img/sports-q-c-140-140-3.jpg" class="rounded-circle" />
                        <h4 id="commentUsername">
                            User 2
                        </h4>
                        <h4 id="commentDate">
                            4/14/2022
                        </h4>
                    </div>
                    <div class="col-md-8">
                        <p id="commentText">
                            Cus smelly person. yes. bad man. feel bad bout self.
                        </p>
                    </div>
                    <div class="col-sm-1" style="text-align: right">
                        <span id="likes"></span>
                        <button aria-label="like" type="button" class="btn btn-outline-secondary" id="like">
                            <i class="bi"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <?php
        // if user is logged in then allow them to comment, else prompt them to login
        $commentText = isset($_SESSION["userID"]) ? '<form id="commentForm" style="padding-left: 10%" class="container mt-2">
            <h3 style="text-align: center">
                Add your comment!
            </h3>
            <div class="form-group mb-3 row">
                <label for="comment"></label>
                <textarea class="form-control" id="comment" rows="3" name="comment" required></textarea>
            </div>
            <input type="submit" class="col-md-2 btn btn-primary text-center" value="Add your Comment" style="text-align: right"/>
        </form>' :
            "<div class='alert alert-warning mt-2' style='margin-left: 8%'>Hey! Want to comment? Try <a href='" . $this->url . "login'> logging in</a> or <a href='" . $this->url . "signup'> signing up</a></div>";
        echo $commentText;
        ?>
    </div>
    <?php include "templates/footer.php" ?>
</body>

<script type="text/javascript">
    const params = new URLSearchParams(window.location.search);
    let id = params.get("id");
    let commentTemplate = $("#comment").clone();
    $("div#commentDiv").html("");
    let delBtn = $("<button class='btn btn-danger'>Delete</button>");

    // Gets the data for the current post
    const url = "<?= $this->url ?>api/getPost";
    $.get(url + `?id=${id}`)
        .done(data => {
            console.log(data);
            if (data["error"]) {
                $("div.container").html("<div class='alert alert-danger'>An error has occurred on the page, please go back and try again</div>");
            } else {

                //loads base post data
                $("#postUsername").text(data?.post[0]?.username);
                $("#postDate").text(data?.post[0]?.date_posted);
                $("#postTitle").text(data?.post[0]?.title);
                $("#postText").text(data?.post[0]?.text);
                $("#postLikes").text(data?.post[0]?.total_likes);

                <?php
                //if the user is logged in, then we check if the logged in user matches the creator of the post, if so add the delete button, and its functionality
                if (isset($_SESSION["userID"])) {
                    echo "if ({$_SESSION['userID']} == data?.post[0]?.uid) {
                    let btn = $(`<button type='submit' class='btn btn-danger mb-1' id='delPost'>DELETE</button>`);
                    // console.log(btn);
                    btn.click(() => {
                        let url = ' {$this->url}api/deletePost';
                        console.log('hit');
                        $.post(url, {
                                pid: id,
                                uid: data?.post[0]?.uid
                            })
                            .done(data => {
                                console.log(data);
                                if (!data?.error) window.location.href = ('{$this->url}index');
                            });
                    });
                    $('#dangerdanger').html(btn);
                }";
                }
                ?>

                //liked and bookmark functionality for the post
                var isLiked = data?.post?.liked;
                var isBookmarked = data?.post?.bookmarked;

                //initial state
                $("#likeIcon").addClass(isLiked ? "bi-hand-thumbs-up-fill" : "bi-hand-thumbs-up");
                $("#bookmarkIcon").addClass(isBookmarked ? "bi-star-fill" : "bi-star");

                //if the like is clicked, then we remove or add the like depending on the state
                $("#postLike").click(() => {
                    let url = "<?= $this->url ?>";
                    url += isLiked ? "api/removeLike" : "api/addLike";
                    $.post(url, {
                            pid: id
                        })
                        .done(data => {
                            console.log(data);
                            if (data?.error) return;
                            $("#likeIcon").removeClass(isLiked ? "bi-hand-thumbs-up-fill" : "bi-hand-thumbs-up");
                            $("#likeIcon").addClass(isLiked ? "bi-hand-thumbs-up" : "bi-hand-thumbs-up-fill");
                            $("#postLikes").text(isLiked ? $("#postLikes").text() - 1 : parseInt($("#postLikes").text()) + 1);
                            isLiked = !isLiked;
                        });
                });

                // if the bookmark button is clicked, the nwe remove or add the bookmark depending on the state
                $("#bookmark").click(() => {
                    let url = "<?= $this->url ?>";
                    url += isBookmarked ? "api/removeBookmark" : "api/addBookmark";
                    $.post(url, {
                            pid: id
                        })
                        .done(data => {
                            console.log(data);
                            if (data?.error) return;
                            $("#bookmarkIcon").removeClass(isBookmarked ? "bi-star-fill" : "bi-star");
                            $("#bookmarkIcon").addClass(isBookmarked ? "bi-star" : "bi-star-fill");
                            isBookmarked = !isBookmarked;
                        });
                });

                //adds the comment data into the page
                data?.comments.forEach(c => {
                    let temp = commentTemplate.clone();
                    //loads the base data of the comment
                    temp.find("#commentUsername").text(c?.username);
                    temp.find("#commentDate").text(c?.date_posted);
                    temp.find("#commentText").text(c?.text);
                    temp.find("#likes").text(c?.total_likes);

                    //like button functionality
                    temp.find("i.bi").addClass(c?.liked ? "bi-hand-thumbs-up-fill" : "bi-hand-thumbs-up");
                    temp.find("#like").click((e) => {
                        console.log(e);
                        let url = "<?= $this->url ?>";
                        const hasClass = temp.find("i.bi").hasClass("bi-hand-thumbs-up-fill");
                        url += hasClass ? "api/removeLikeComment" : "api/addLikeComment";
                        $.post(url, {
                                cid: c?.cid
                            })
                            .done(data => {
                                console.log(data);
                                if (data?.error) return;
                                temp.find("i.bi").removeClass(hasClass ? "bi-hand-thumbs-up-fill" : "bi-hand-thumbs-up");
                                temp.find("i.bi").addClass(hasClass ? "bi-hand-thumbs-up" : "bi-hand-thumbs-up-fill");
                                temp.find("#likes").text(hasClass ? temp.find("#likes").text() - 1 : parseInt(temp.find("#likes").text()) + 1);
                            });
                    })
                    $("#commentDiv").append(temp);
                })
            }
        });
    // on form submit, adds comment to database
    $("#commentForm").submit(e => {
        const url = "<?= $this->url ?>api/addComment";
        $.post(url, {
                pid: id,
                text: $("textarea#comment").val()
            })
            .done(data => console.log(data));
        window.location.reload();
        e.preventDefault();
    })
</script>

</html>