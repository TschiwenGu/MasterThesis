<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Search Result - Microservice Migration Meta-Approach Web-based Tool</title>

  <meta name="author" content="QiwenGu">

  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
  <link rel="stylesheet" type="text/css" href="css/style.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
  <script type="text/javascript" src="frontend.js"></script>
</head>

<body>
  <div class="container-fluid" style="padding:30px">
    <div class="row">
      <div class="col-md-12">
        <h3><button onClick="self.close()" type="button" class="btn btn-link btn-lg">&lt;&lt; Back to Index Page</button>
        </h3>
        <div class="page-header" style="margin-top:20px">
          <h1>
            Search String: <small id="DefaultString"></small>
          </h1>
          <h3 id="ShowSearchString"></h3>
        </div>

        <div class="row" style="margin-top:20px">
          <div class="col-md-12">
            <h4 id="ResultCount">
              Result list:
            </h4>
          </div>
        </div>
        <div class="row" style="margin-top:20px">
          <div class="col-md-3 text-center">
            <button class="btn btn-outline-success" name="sort" id="IDSort" value="IDSort" onclick="sortTable(1,0)">IDSort
            </button>
          </div>
          <div class="col-md-3 text-center">
            <button class="btn btn-outline-success" name="sort" id="YearSort" value="YearSort" onclick="sortTable(1,2)">YearSort
            </button>
          </div>
          <div class="col-md-3 text-center">
            <button class="btn btn-outline-success" name="sort" id="ScoreSort" value="ScoreSort" onclick="sortTable(1,4)">ScoreSort
            </button>
          </div>
          <div class="col-md-3 text-center">
            <button class="btn btn-outline-success" name="sort" id="RelevanceSort" value="RelevanceSort" onclick="sortTable(1,6)">RelevanceSort
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row" style="padding-left:30px ; padding-right:30px">
    <div class="col-md-12">
      <table class="table table-bordered" id="ResultTable">
        <thead>
          <tr class="table-success">
            <th>
              #
            </th>
            <th>
              Title
            </th>
            <th>
              Year
            </th>
            <th>
              Author
            </th>
            <th>
              Score(/5)
            </th>
            <th>
              Missing String
            </th>
            <th>
              Recommandation(%)
            </th>
          </tr>
        </thead>
        <tbody>

          <?php
          include('functions.php');

          $servername = "localhost";
          $username = "user";
          $password = "";
          $dbname = "migration";
          $resultArray = array();

          //Create connection
          $conn = new mysqli($servername, $username, $password, $dbname);

          //Check connection
          if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
          }

          //Call different method by different submit button
          if (isset($_REQUEST['subject'])) {
            /*# search-buttons were clicked
            switch ($_REQUEST['subject']) {
              case 'ShowAll':
                $resultArray = showAll($conn);
                break;
              case 'Search':*/
                $resultArray = search($conn);
                /*break;
            }*/
          }
          $conn->close();

          ?>
        </tbody>
      </table>
    </div>
  </div>

  <script>
    $(document).ready(function() {
      sortDescend(6) //Automatic sort table after page generated by match score
    });
  </script>
</body>
</html>