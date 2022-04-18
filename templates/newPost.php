<!-- 
    Dakota Bourne - db2nb
    Matthew Reid - mrr7rn
 -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <meta name="description" content="Create a new Research project on connect uva">
    <meta name="author" content="Dakota and Matthew">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="keywords" content="uva, research, collaboration, professors">

    <link rel="icon" type="image/x-icon" href="/db2nb/TechForDummies/favicon.ico">
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
        <h2>Add your question below!</h2>
        <?php
        if (!empty($error_msg)) {
            echo "<div class='alert alert-danger'>$error_msg</div>";
        }
        ?>

        <form id="target">
            <div class="form-row">
                <div class="form-group col-md-9">
                    <label for="inputTitle">Title</label>
                    <input type="text" class="form-control" id="inputTitle" name="title" maxlength="40" required>
                </div>
                <!-- <div class="dropdown-menu form-group col-md-6">
                </div> -->
                <div class="form-group col-md-3">
                    <label for="sel1">Select Category:</label>
                    <select class="form-control" id="sel1">
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label for="exampleFormControlTextarea1">Details</label>
                <textarea class="form-control" id="details" rows="3" name="summary" required></textarea>
            </div>
            <input type="submit" class="btn btn-primary" value="Create a new Post" />
        </form>
    </div>

    <?php include "templates/footer.php" ?>

    <script type="text/javascript">
        let body = $("div.container").children().clone();
        $("div.container").text("LOADING");

        $.get("<?= $this->url ?>api/getCategories")
            .done(data => {
                $("div.container").html(body);

                for (let i = 0; i < data.length; i++) {
                    $("select.form-control")
                        .append($("<option>", {
                            class: "dropdown-item",
                            id: data[i]?.catID
                        }).text(data[i]?.cname));
                }

                $("#target").submit(function(e) {
                    let url = "<?= $this->url ?>api/createPost";

                    $.post(url, {
                            uid: "<?= $_SESSION["userID"] ?>",
                            cid: $("select.form-control").find("option:selected").attr("id"),
                            title: $("input#inputTitle").val(),
                            text: $("#details").val()
                        })
                        .done(data => {
                            console.log("data", data);
                            window.location.href = (`<?= $this->url ?>post/?id=${data["pid"]}`);
                        })
                    e.preventDefault();
                });
            });
    </script>
</body>

</html>