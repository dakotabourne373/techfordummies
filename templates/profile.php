<!-- 
    Dakota Bourne - db2nb
    Matthew Reid - mrr7rn
 -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="description" content="Your Profile on connect uva">
    <meta name="author" content="Dakota and Matthew">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="uva, research, collaboration, professors">

    <link rel="icon" type="image/x-icon" href="/tempfordummies/favicon.ico">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="<?= "{$this->url}/styles/main.css" ?>">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>


    <title>TechForDummies</title>
</head>

<body>
    <?php include "templates/header.php" ?>

    <div class="landing container">
        <div class="container-fluid padd mb-3">
            <div class="row">

                <div class="col-md-6">
                    <h2>
                        <span id="username"> </span>
                    </h2>
                    <p>
                        <span id="join_date"> </span> | Total posts
                    </p>

                </div>
                <div class="col-md-6" style="text-align: right">
                    <h2>
                        Bio
                    </h2>
                    <p>
                        <span id="bio"> </span>
                    </p>

                </div>
            </div>
            <div id="buttonsRow" class="row">
                <div class="col-md-6" id="dangerdanger">

                </div>
                <div class="col-md-6 text-end" id="editable">

                </div>
            </div>
        </div>

        <div class="container-fluid padd" id="yourSavedDiv">
            <h2>Saved Posts</h2>
            <div class="row">
                <div class="col-md-6">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item">
                                <a class="page-link" href="#savedNum" aria-label="savedPrevious">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <!-- <li class="page-item"><a class="page-link" href="#">1</a></li> -->
                            <li class="page-item" id="nextSaved">
                                <a class="page-link" href="#savedNum" aria-label="savedNext">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>

        <!-- table building -->
        <div class="padd">
            <div class="container-fluid border border-dark bg-light" id="savedPosts">

                <!-- new post -->
                <div class="row border-bottom coloring" id="postDiv">
                    <div class="col-md-8 mt-3 mb-1" id="1">
                        <h5 clas="mt-1" style="margin-bottom: 0" id="title">Title</h5>
                        <p class="mb-0" id="posted">By someone, Wednesday at 10:00 am</p>
                    </div>
                    <div class="col-md-4 mt-3" style="text-align: center" id="2">
                        <img alt="Bootstrap Image Preview" width="75" height="75" src="https://www.layoutit.com/img/sports-q-c-140-140-3.jpg" class="rounded-circle mb-0" />
                        <p class="mb-0" id="author">last activity by Someone</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="container-fluid padd mt-4" id="yourPostsDiv">
            <div class="row">
                <div class="container col-md-12">
                    <h2>
                        Your Posts
                    </h2>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <nav aria-label="Page navigation example">
                            <ul class="pagination">
                                <li class="page-item">
                                    <a class="page-link" href="#myNum" aria-label="myPrevious">
                                        <span aria-hidden="true">&laquo;</span>
                                    </a>
                                </li>
                                <!-- <li class="page-item"><a class="page-link" href="#">1</a></li> -->
                                <li class="page-item" id="nextMy">
                                    <a class="page-link" href="#myNum" aria-label="myNext">
                                        <span aria-hidden="true">&raquo;</span>
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- table building -->
        <div class="padd">
            <div class="container-fluid border border-dark bg-light" id="myPosts">

                <!-- new post -->

            </div>
        </div>
    </div>

    <?php include "templates/footer.php" ?>
</body>
<script type="text/javascript">
    let getURL = "<?= $this->url ?>api/getProfile";
    var loadedSaved = [];
    var loadedOwned = [];
    var savedPages = 1;
    var myPages = 1;
    var savedCurPage = 1;
    var myCurPage = 1;
    var temp = $("div#postDiv").clone();

    const params = new URLSearchParams(window.location.search);
    let id = params.get("id");
    if (!id) id = "<?= isset($_SESSION["userID"]) ? $_SESSION["userID"] : 0 ?>";
    if (id == 0) {
        window.location.href = ("<?= $this->url ?>signup");
    }
    console.log("id", id);


    $.post(getURL, {
            uid: id
        })
        .done(data => {
            console.log("data", data);
            // if user is logged in and on their own profile, then display their saved posts and their own posts
            if (id == "<?= isset($_SESSION["userID"]) ? $_SESSION["userID"] : 0 ?>") {
                loadedSaved = data?.savedPosts;
                loadedOwned = data?.myPosts;

                if (!loadedSaved) {
                    $("#yourSavedDiv").remove();
                    $("#savedPosts").remove();
                } else {
                    savedPages = Math.ceil(loadedSaved?.length / 10);
                    generateSavedPagination();
                    generateSavedPage();
                }

                if (!loadedOwned) {
                    $("#yourPostsDiv").remove();
                    $("#myPosts").remove();
                } else {
                    myPages = Math.ceil(loadedOwned?.length / 10);
                    generateMyPagination();
                    generateMyPage();
                }
            } else {
                $("#yourPostsDiv").remove();
                $("#myPosts").remove();
                $("#yourSavedDiv").remove();
                $("#savedPosts").remove();

            }
            // check user exists
            if (data.length == 0) {
                $("div.container").html($(`<div class='alert alert-danger'>This user does not exist!</div>`));
                return;
            }
            // fill in user information 
            $("#username").text(data?.profile?.username);
            $("#join_date").text(data?.profile?.join_date);
            var bio = "This user does not have a bio";
            if (data?.profile?.bio) bio = data?.profile?.bio;
            $("#bio").text(bio);

            // sets up buttons to delete profile and edit bio
            let delBtn = $('<button type="submit" class="btn btn-danger" id="delProfile">DELETE PROFILE</button>');
            let followBtn = $('<button type="submit" class="btn" id="followProfile" ></button>');

            // depeing on if the page is the current users profile or another users profile adds follow button
            if (id && id != <?= isset($_SESSION["userID"]) ? $_SESSION["userID"] : 0 ?>) {
                followBtn.addClass(data?.isFollowed ? "btn-warning" : "btn-secondary");
                followBtn.text(data?.isFollowed ? "Unfollow User" : "Follow User");
                let url = "<?= $this->url ?>";
                url += data?.isFollowed ? "api/unfollowUser" : "api/followUser";
                followBtn.click(() => {
                    $.post(url, {
                            uid: id
                        })
                        .done(data => {
                            window.location.reload();
                        });
                });
                $("#dangerdanger").html(followBtn);
            } else {
                // adds delete button, and bio editing functionality
                <?php
                if (isset($_SESSION['userID'])) {
                    echo "delBtn.click(() => {
            $.post('{$this->url}api/delProfile', {
                    uid: id ? id : {$_SESSION['userID']}
                })
                .done(resp => {
                    console.log(resp);
                    if (resp.url !== undefined) window.location.href = (resp.url);
                });
        })
        $('#dangerdanger').html(delBtn);";
                }
                ?>
                let bioBtn = $('<button type="submit" class="btn btn-secondary" id="editBio">Edit Bio</button>');
                let saveBtn = $('<button type="submit" class="btn btn-warning" id="editBio">Save Bio</button>');
                bioBtn.click(() => {
                    let input = $(`<textarea class="form-control" id="bioinput" rows="3" name="summary" required>${bio}</textarea>`);
                    $("span#bio").html(input);
                    saveBtn.click(() => {
                        let url = "<?= $this->url ?>api/updateBio";
                        $.post(url, {
                                bio: $("textarea#bioinput").val()
                            })
                            .done(data => {
                                if (!data?.error) window.location.reload();
                            });
                    });
                    $("#editable").html(saveBtn);
                });
                $('#editable').html(bioBtn);
            }
        });

    function generateSavedPagination() {

        // adds previous page functionality to saved posts
        var element = $("body").find("[aria-label='savedPrevious']");

        element.click(() => {
            console.log("current page before", savedCurPage);
            let temporary = savedCurPage;
            savedCurPage = Math.max(savedCurPage - 1, 1);
            console.log("current page after", savedCurPage);
            if (temporary != savedCurPage) {
                $(".active.saved").removeClass("active");
                let all_a = $(`#savedNum${savedCurPage}`).addClass("active");

                generateSavedPage();
            }
        });

        // adds next page functionality to saved posts
        var element = $("body").find("[aria-label='savedNext']");

        element.click(() => {
            console.log("current page before", savedCurPage);
            let temporary = savedCurPage;
            savedCurPage = Math.min(savedCurPage + 1, savedPages);
            console.log("current page after", savedCurPage);
            if (temporary != savedCurPage) {
                $(".active.saved").removeClass("active");
                let all_a = $(`#savedNum${savedCurPage}`).addClass("active");
                generateSavedPage();
            }
        });

        // adds page number functionality to saved posts
        for (let i = 0; i < savedPages; i++) {
            let num = $(`<li class="page-item saved" id="savedNum${i+1}"></li>`);
            if (i == 0) num.addClass("active");
            num.append($(`<a class="page-link" href="#">${i + 1}</a>`));
            num.click(() => {
                console.log("current saved page before", savedCurPage);
                savedCurPage = num.text();
                console.log("current saved page after", savedCurPage)
                $(`.active.saved`).removeClass("active");
                num.addClass("active");
                generateSavedPage();
            })
            $("#nextSaved").before(num);
        }
        console.log(loadedSaved);
    }

    function generateMyPagination() {
        var element = $("body").find("[aria-label='myPrevious']");

        // adds previous page functionality to users own posts

        element.click(() => {
            console.log("current page before", myCurPage);
            let temporary = myCurPage;
            myCurPage = Math.max(myCurPage - 1, 1);
            console.log("current page after", myCurPage);
            if (temporary != myCurPage) {
                $(".active.my").removeClass("active");
                let all_a = $(`#myNum${myCurPage}`).addClass("active");

                generateMyPage();
            }
        });

        // adds next page functionality to users own posts
        var element = $("body").find("[aria-label='myNext']");

        element.click(() => {
            console.log("current page before", myCurPage);
            let temporary = myCurPage;
            myCurPage = Math.min((myCurPage + 1), myPages);
            // console.log(myPages);
            // myCurPage += 1;
            console.log("current page after", myCurPage);
            if (temporary != myCurPage) {
                $(".active.my").removeClass("active");
                let all_a = $(`#myNum${myCurPage}`).addClass("active");

                generateMyPage();
            }
        });

        // adds page number functionality to users own posts
        for (let i = 0; i < myPages; i++) {
            let num = $(`<li class="page-item my" id="myNum${i+1}"></li>`);
            if (i == 0) num.addClass("active");
            num.append($(`<a class="page-link" href="#myNum">${i + 1}</a>`));
            num.click(() => {
                console.log("current my page before", myCurPage);
                myCurPage = num.text();
                console.log("current my page after", myCurPage)
                $(".active.my").removeClass("active");
                num.addClass("active");
                generateMyPage();
            })
            $("#nextMy").before(num);
        }
    }

    function generateSavedPage() {
        // generates table of saved posts
        console.log(loadedSaved);
        console.log(temp);
        $("#savedPosts").html("");
        let maximum = ((savedCurPage - 1) * 10) + 10
        for (let i = maximum - 10; i < maximum; i++) {
            if (i == loadedSaved.length) return;
            let row = temp.clone();
            row.find("#title").text(loadedSaved[i]?.title);
            row.find("#posted").text(loadedSaved[i]?.date_posted);
            // row.find("#2").remove();
            row.find("#author").text(loadedSaved[i]?.username)
            let img = row.find("img");
            img.on('click', () => {
                window.location.href = ("<?= $this->url ?>profile/?id=" + loadedSaved[i]?.uid)
            });
            img.hover(() => {
                row.removeClass("hover");
                img.addClass("imghover");
            }, () => {
                row.addClass("hover");
                img.removeClass("imghover");
            })
            // row.addClass("pb-4");
            row.click((e) => {
                if (e.target == img[0]) return;
                console.log("img", img[0]);
                console.log("tager", e.target);
                console.log("curtarget", e.currentTarget)
                window.location.href = ("<?= $this->url ?>post/?id=" + loadedSaved[i]?.pid);
            });
            row.hover(() => {
                row.addClass("hover");
            }, () => {
                row.removeClass("hover");
            });
            $("#savedPosts").append(row);
        }
    }

    function generateMyPage() {
        // generates table of users own posts
        console.log(loadedOwned);
        console.log(temp);
        $("#myPosts").html("");
        let maximum = ((myCurPage - 1) * 10) + 10
        for (let i = maximum - 10; i < maximum; i++) {
            if (i == loadedOwned.length) return;
            let row = temp.clone();
            row.find("#title").text(loadedOwned[i]?.title);
            row.find("#posted").text(loadedOwned[i]?.date_posted);
            // row.find("#2").remove();
            row.find("#author").text(loadedOwned[i]?.username)
            let img = row.find("img");
            img.on('click', () => {
                window.location.href = ("<?= $this->url ?>profile/?id=" + loadedOwned[i]?.uid)
            });
            img.hover(() => {
                row.removeClass("hover");
                img.addClass("imghover");
            }, () => {
                row.addClass("hover");
                img.removeClass("imghover");
            })
            // row.addClass("pb-4");
            row.click((e) => {
                if (e.target == img[0]) return;
                console.log("img", img[0]);
                console.log("tager", e.target);
                console.log("curtarget", e.currentTarget)
                window.location.href = ("<?= $this->url ?>post/?id=" + loadedOwned[i]?.pid);
            });
            row.hover(() => {
                row.addClass("hover");
            }, () => {
                row.removeClass("hover");
            });
            $("#myPosts").append(row);
        }
    }
</script>

</html>