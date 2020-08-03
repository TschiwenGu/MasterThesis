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

</head>

<body>
  <div class="container-fluid" style="padding:30px">
    <div class="row">
      <div class="col-md-12">
        <h3><a href="index.php" class="badge badge-default">&lt;&lt; Back to Index Page</a>
        </h3>
        <div class="page-header" style="margin-top:20px">
          <h1>
            Search String: <small id="DefaultString"></small>
          </h1>
          <label id="ShowSearchString"></label>
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
            <button class="btn btn-outline-success" name="sort" id="IDSort" value="IDSort" onclick="sortTable(0)">IDSort
            </button>
          </div>
          <div class="col-md-3 text-center">
            <button class="btn btn-outline-success" name="sort" id="YearSort" value="YearSort" onclick="sortTable(2)">YearSort
            </button>
          </div>
          <div class="col-md-3 text-center">
            <button class="btn btn-outline-success" name="sort" id="ScoreSort" value="ScoreSort" onclick="sortTable(4)">ScoreSort
            </button>
          </div>
          <div class="col-md-3 text-center">
            <button class="btn btn-outline-success" name="sort" id="RelevanceSort" value="RelevanceSort" onclick="sortTable(6)">RelevanceSort
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
              Recommand(%)
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

          if (isset($_REQUEST['subject'])) {
            # search-buttons were clicked
            switch ($_REQUEST['subject']) {
              case 'ShowAll':
                $resultArray = showAll($conn);
                break;
              case 'Search':
                $resultArray = search($conn);
                break;
            }
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

    var idAscend = true;
    var yearAscend = true;
    var scoreAscend = true;
    var relevanceAscend = true;

    function sortTable(mode) {
      switch (mode) {
        case (0): //Sort by id
          sort(0, idAscend);
          idAscend = !idAscend;
          break;
        case (2): //Sort by Year
          sort(0, true); //reset table by sorting by id ascend
          sort(6, false); //higer relevant conreibution have higher priority
          sort(2, yearAscend);
          yearAscend = !yearAscend;
          break;
        case (4): //Sort by Score
          sort(0, true); //reset table by sorting by id ascend
          sort(6, false); //higer relevant conreibution have higher priority
          sort(4, scoreAscend);
          scoreAscend = !scoreAscend;
          break;
        case (6): //Sort by Relevance
          sort(0, true); //reset table by sorting by id ascend
          sort(6, relevanceAscend);
          relevanceAscend = !relevanceAscend;
          break;
      }
    }


    //Integrated sorting modified from W3C
    function sort(mode, order) {
      var table, rows, switching, i, x, y, shouldSwitch;
      table = document.getElementById("ResultTable");
      switching = true;

      /*Make a loop that will continue until no switching has been done:*/
      while (switching) {
        //start by saying: no switching is done:
        switching = false;
        rows = table.rows;
        /*Loop through all table rows (except the first, which contains table headers):*/
        for (i = 1; i < (rows.length - 1); i++) {
          //start by saying there should be no switching:
          shouldSwitch = false;
          /*Get the two elements you want to compare,
          one from current row and one from the next:*/
          x = rows[i].getElementsByTagName("TD")[mode];
          y = rows[i + 1].getElementsByTagName("TD")[mode];

          if (order == true) { //ascend
            //check if the two rows should switch place:
            if (Number(x.innerHTML) > Number(y.innerHTML)) {
              //if so, mark as a switch and break the loop:
              shouldSwitch = true;
              break;
            }
          } else { //descend
            if (Number(x.innerHTML) < Number(y.innerHTML)) {
              //if so, mark as a switch and break the loop:
              shouldSwitch = true;
              break;
            }
          }
        }
        if (shouldSwitch) {
          /*If a switch has been marked, make the switch
          and mark that a switch has been done:*/
          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
          switching = true;
        }
      }
    }

    //Ascend sorting from W3C
    function sortAscend(mode) {
      var table, rows, switching, i, x, y, shouldSwitch;
      table = document.getElementById("ResultTable");
      switching = true;

      /*Make a loop that will continue until no switching has been done:*/
      while (switching) {
        //start by saying: no switching is done:
        switching = false;
        rows = table.rows;
        /*Loop through all table rows (except the first, which contains table headers):*/
        for (i = 1; i < (rows.length - 1); i++) {
          //start by saying there should be no switching:
          shouldSwitch = false;
          /*Get the two elements you want to compare,
          one from current row and one from the next:*/
          x = rows[i].getElementsByTagName("TD")[mode];
          y = rows[i + 1].getElementsByTagName("TD")[mode];
          //check if the two rows should switch place:
          if (Number(x.innerHTML) > Number(y.innerHTML)) {
            //if so, mark as a switch and break the loop:
            shouldSwitch = true;
            break;
          }
        }
        if (shouldSwitch) {
          /*If a switch has been marked, make the switch
          and mark that a switch has been done:*/
          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
          switching = true;
        }
      }
    }


    //Descned sorting modified from W3C
    function sortDescend(mode) {
      var table, rows, switching, i, x, y, shouldSwitch;
      table = document.getElementById("ResultTable");
      switching = true;

      /*Make a loop that will continue until no switching has been done:*/
      while (switching) {
        //start by saying: no switching is done:
        switching = false;
        rows = table.rows;
        /*Loop through all table rows (except the first, which contains table headers):*/
        for (i = 1; i < (rows.length - 1); i++) {
          //start by saying there should be no switching:
          shouldSwitch = false;
          /*Get the two elements you want to compare,
          one from current row and one from the next:*/
          x = rows[i].getElementsByTagName("TD")[mode];
          y = rows[i + 1].getElementsByTagName("TD")[mode];
          //check if the two rows should switch place:
          if (Number(x.innerHTML) < Number(y.innerHTML)) {
            //if so, mark as a switch and break the loop:
            shouldSwitch = true;
            break;
          }
        }
        if (shouldSwitch) {
          /*If a switch has been marked, make the switch
          and mark that a switch has been done:*/
          rows[i].parentNode.insertBefore(rows[i + 1], rows[i]);
          switching = true;
        }
      }
    }
  </script>
</body>

</html>