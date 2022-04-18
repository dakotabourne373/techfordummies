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

<body class="bg loading">
    <?php include "templates/header.php" ?>


    <div class="container landing">

        <div class="container-fluid padd">
            <div class="row">
                <div class="container col-md-12">
                    <h2 id="pageName">
                        Forum
                    </h2>
                    <p>
                        Check out the posts below
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <nav aria-label="Page navigation example">
                        <ul class="pagination">
                            <li class="page-item">
                                <a class="page-link" href="#" aria-label="Previous">
                                    <span aria-hidden="true">&laquo;</span>
                                </a>
                            </li>
                            <!-- <li class="page-item"><a class="page-link" href="#">1</a></li> -->
                            <li class="page-item" id="next">
                                <a class="page-link" href="#" aria-label="Next">
                                    <span aria-hidden="true">&raquo;</span>
                                </a>
                            </li>
                        </ul>
                    </nav>
                </div>
                <div class="col-md-6 text-end">
                    <a role="button" class="btn btn-primary" href="<?= isset($_SESSION["userID"]) ? $this->url . "newPost" : $this->url . "signup" ?>">New Post</a>
                </div>
            </div>
        </div>

        <!-- table building -->
        <div class="padd">
            <div class="container-fluid border border-dark bg-light" id="posts">

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
        <div class="padd pt-1">
            <div id="export" class="text-end">

            </div>
        </div>
    </div>

    <?php include "templates/footer.php" ?>
</body>

<script type="text/javascript">
    var pages = 1;
    var curPage = 1;
    var loadedData = [];
    var loadingCat = true;
    var temp = $("div#postDiv").clone();

    $("#1").click(() => {
        window.location.href = ("<?= $this->url ?>post/?id=1");
        return false;
    });

    const params = new URLSearchParams(window.location.search);
    let category = params.get("category");

    if (category) {
        const url = "<?= $this->url ?>api/getCategorizedPosts";

        $.get(url + `?category=${category}`)
            .done(data => {
                loadedData = data;
                loadingCat = false;
                pages = Math.ceil(data.length / 10);
                console.log(data);
                generatePagination(pages);
                $("#posts").html("");
                $("#pageName").text(data[0]?.cname);

                for (let i = (curPage - 1) * 10; i < 10; i++) {
                    if (i == data.length) break;
                    let row = temp.clone();
                    row.find("#title").text(data[i]?.title);
                    row.find("#posted").text(data[i]?.date_posted);
                    // row.find("#2").remove();
                    row.find("#author").text(data[i]?.username)
                    let img = row.find("img");
                    img.on('click', () => {
                        window.location.href = ("<?= $this->url ?>profile/?id=" + data[i]?.uid)
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
                        window.location.href = ("<?= $this->url ?>post/?id=" + data[i]?.pid);
                    });
                    row.hover(() => {
                        row.addClass("hover");
                    }, () => {
                        row.removeClass("hover");
                    });
                    $("#posts").append(row);
                }
                let exportBtn = $("<button type='submit' class='btn btn-secondary mb-1' id='exportBtn'>Export</button>");
                exportBtn.click(() => {
                    window.location.href = "<?= $this->url ?>api/getCategorizedPosts/?category=" + category;
                });
                $("#export").html("");
                $("#export").append(exportBtn);

            })
    } else {
        const url = "<?= $this->url ?>api/getCategories";

        // Get the categories to load onto the page
        $.get(url)
            .done(data => {
                loadedData = data;
                console.log(data);
                pages = Math.ceil(data.length / 10);
                console.log(pages)
                generatePagination(pages);
                $("#posts").html("");

                for (let i = (curPage - 1) * 10; i < 10; i++) {
                    if (i == data.length) break;
                    let row = temp.clone();
                    row.find("#title").text(data[i]?.cname);
                    row.find("#posted").text("");
                    let thumbBtn = $('<button aria-label="postLike" type="button" class="btn btn-outline-secondary mb-1" id="thumbBtn"></button>');
                    let thumb = $(`<i class="bi" id="likeIcon"></i>`);
                    thumb.addClass(data[i]?.liked ? "bi-hand-thumbs-up-fill" : "bi-hand-thumbs-up");
                    thumbBtn.click(() => {
                        let url = "<?= $this->url ?>";
                        const hasClass = thumb.hasClass("bi-hand-thumbs-up-fill");
                        url += hasClass ? "api/removeCategoryLike" : "api/addCategoryLike";
                        $.post(url, {
                                catID: data[i]?.catID
                            })
                            .done(data => {
                                console.log("before", data);
                                if (data?.error) return;
                                thumb.removeClass(hasClass ? "bi-hand-thumbs-up-fill" : "bi-hand-thumbs-up");
                                thumb.addClass(hasClass ? "bi-hand-thumbs-up" : "bi-hand-thumbs-up-fill");
                                console.log("after", data);

                            });
                    })
                    console.log(thumbBtn[0]);
                    thumbBtn.append(thumb);
                    row.find("#2").html(thumbBtn);
                    row.addClass("pb-4");
                    row.click((e) => {
                        if (e.target == thumb[0]) return;
                        if (e.target == thumbBtn[0]) return;
                        window.location.href = ("<?= $this->url ?>posts/?category=" + data[i]?.catID);
                    })
                    row.hover(() => {
                        row.addClass("hover");
                    }, () => {
                        row.removeClass("hover");
                    })
                    $("#posts").append(row);
                }
            })
    }

    $("body").removeClass("loading");

    function generatePagination(totalPages) {
        // adds the previous button functionality
        var element = $("body").find("[aria-label='Previous']");

        element.click(() => {
            console.log("current page before", curPage);
            let temporary = curPage;
            curPage = Math.max(curPage - 1, 1);
            console.log("current page after", curPage);
            if (temporary != curPage) {
                $(".active").removeClass("active");
                let all_a = $(`#${curPage}`).addClass("active");

                generateNewPage();
            }
        });

        // adds the next button functionality
        var element = $("body").find("[aria-label='Next']");

        element.click(() => {
            console.log("current page before", curPage);
            let temporary = curPage;
            curPage = Math.min(curPage + 1, totalPages);
            console.log("current page after", curPage);
            if (temporary != curPage) {
                $(".active").removeClass("active");
                let all_a = $(`#${curPage}`).addClass("active");

                generateNewPage();
            }
        });

        // adds page number functionality
        for (let i = 0; i < totalPages; i++) {
            let num = $(`<li class="page-item" id="${i+1}"></li>`);
            if (i == 0) num.addClass("active");
            num.append($(`<a class="page-link" href="#">${i + 1}</a>`));
            num.click(() => {
                console.log("current page before", curPage);
                curPage = num.text();
                console.log("current page after", curPage)
                $(".active").removeClass("active");
                num.addClass("active");
                generateNewPage();
            })
            $("#next").before(num);
        }
    }

    function generateNewPage() {
        console.log(loadedData);
        console.log(temp);
        $("#posts").html("");
        if (loadingCat) {
            let maximum = ((curPage - 1) * 10) + 10
            for (let i = maximum - 10; i < maximum; i++) {
                if (i == loadedData.length) return;
                let row = temp.clone();
                row.find("#title").text(loadedData[i]?.cname);
                row.find("#posted").text("");
                row.find("#2").remove();
                row.addClass("pb-4");
                row.click(() => {
                    window.location.href = ("<?= $this->url ?>posts/?category=" + loadedData[i]?.catID);
                })
                row.hover(() => {
                    row.addClass("hover");
                }, () => {
                    row.removeClass("hover");
                })
                $("#posts").append(row);
            }
        } else {
            let maximum = ((curPage - 1) * 10) + 10
            for (let i = maximum - 10; i < maximum; i++) {
                if (i == loadedData.length) break;
                let row = temp.clone();
                row.find("#title").text(loadedData[i]?.title);
                row.find("#posted").text(loadedData[i]?.date_posted);
                // row.find("#2").remove();
                row.find("#author").text(loadedData[i]?.username)
                let img = row.find("img");
                img.on('click', () => {
                    window.location.href = ("<?= $this->url ?>profile/?id=" + loadedData[i]?.uid)
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
                    window.location.href = ("<?= $this->url ?>post/?id=" + loadedData[i]?.pid);
                });
                row.hover(() => {
                    row.addClass("hover");
                }, () => {
                    row.removeClass("hover");
                });
                $("#posts").append(row);
            }
            console.log("hit");
            let exportBtn = $("<button type='submit' class='btn btn-secondary mb-1' id='exportBtn'>Export</button>");
            exportBtn.click(() => {
                window.location.href = "<?= $this->url ?>api/getCategorizedPosts/?category=" + category;
            });
            $("#export").html("");
            $("#export").append(exportBtn);

        }
    }
</script>

</html>