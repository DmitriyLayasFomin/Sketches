<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>search</title>
    <link rel="stylesheet" href="assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/Navigation-Clean.css">
    <link rel="stylesheet" href="assets/css/Navigation-with-Search.css">
	<link rel="stylesheet" href="assets/css/styles.css">
	<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-light navbar-expand-md d-lg-flex navigation-clean">
        <div class="container"><a class="navbar-brand" href="#">Пример страницы поиска</a><button class="navbar-toggler" data-toggle="collapse" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
            <div class="collapse navbar-collapse"
                id="navcol-1">
                <ul class="nav navbar-nav ml-auto"></ul>
            </div>
        </div>
    </nav>
    <div><input class="border rounded shadow d-inline-block float-left" type="text" name="names" pattern="[^'\x22]+" id="search" style="margin-left: 3%;min-width: 26%;min-height: 35px;">
	<input type="submit" name="button" id="bsearch" /></div>
    <ul id="list" style="margin-top: 7%;margin-left: 10px;">
       
    </ul>
  
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
<script>
$(document).ready(function(e){
	$("#search").keyup(function(){
		$("#list").show();
		var text = $(this).val();
		$("#bsearch").on("click", function(){$.ajax({
			type: 'POST',
			url: 'search.php',
			data: {txt: text, pagination_id: $('#pgl').attr('pg') },
			success: function(data){
				$("#list").html(data);
				
			}
		});
		
		});
	})
});
</script>


<script>
$(document).ready(function(e){
	$("#search").keyup(function(){
		$("#list").show();
		var text = $(this).val();
		$("#pgl").on("click", function(){$.ajax({
			type: 'POST',
			url: 'search.php',
			data: {txt: text, pagination_id: $('#pgl').attr('pg') },
			success: function(data){
				$("#list").html(data);
				
			}
		});
		
		});
	})
});
</script>

</body>
</html>