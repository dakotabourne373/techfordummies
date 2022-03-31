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
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css"
        integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="<?="{$this->url}/styles/main.css"?>">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.3.1.min.js"
        integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>


    <title>ConnectUVA</title>
</head>

<body>
    <?php include "templates/header.php"?>

    <div class="container-fluid landing container">
	<div class="row">

		<div class="col-md-6">
			<h2>
            <span id="username"> </span>
			</h2>
			<p>
            <span id="join_date"> </span> | Total posts
			</p>

		</div>
		<div class="col-md-6">
			<h2>
				Bio
			</h2>
			<p>
            <span id="bio"> </span>
			</p>

		</div>
        <div id="dangerdanger">
            <button type="submit" class="btn btn-danger" id="delProfile" >DELETE PROFILE</button>
        </div>
	</div>

</div>

    <!-- <div class="container landing">
         <h3>
            <div>
                Username: <span id="username"> </span>
            </div>

            <div>
            Date Joined: <span id="join_date"> </span>
            </div>

            <div>
            Bio: <span id="bio"> </span>
            </div>
        </h3>

        <div id="dangerdanger">
            <button type="submit" class="btn btn-danger" id="delProfile" >DELETE PROFILE</button>
        </div>
    </div> -->

    <?php include "templates/footer.php"?>
</body>
    <script type="text/javascript">
        let getURL = "<?=$this->url?>api/getProfile";

        const params = new URLSearchParams(window.location.search);
        let id = params.get("id");
        if(id && id != <?=$_SESSION["userID"]?>) $("#dangerdanger").html("");
        console.log("id", id);
        if(!id) id = <?=$_SESSION["userID"]?>;

        
        $.post(getURL, {uid: id})
        .done(data => {
            console.log("username", data);
            if(data.length == 0){
                $("div.container").html($(`<div class='alert alert-danger'>This user does not exist!</div>`));
                return;
            }

            $("#username").text(data?.username);
            $("#join_date").text(data?.join_date);
            let bio = "This user does not have a bio";
            if(data.bio) bio = data?.bio;
            $("#bio").text(bio);
        });

        $("#delProfile").click(() => {
            $.post("<?=$this->url?>api/delProfile", {uid: id ? id : $_SESSION["userID"]})
            .done(resp => {
                console.log(resp);
                if(resp.url !== undefinded) window.location.replace(resp.url);
            });
        })
    </script>

</html>