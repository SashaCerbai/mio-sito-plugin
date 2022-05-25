<?PHP
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: *');
require("connessione.php");
header('Content-Type: application/json');

//variabili varie
$page = @$_POST["start"] ?? 0;
$size = @$_POST["length"] ?? 10;
$id = @$_POST["id"] ?? 0;
$searchVal = $_POST["search"]["value"];
$results = contaRisultati($searchVal);
$conta = contaRighe();



//array del json
$arrayJSON = array();


//switch per GET, POST, ecc...
switch ($_SERVER['REQUEST_METHOD']) {

    case 'POST':


        if ($searchVal != "") {
            $arrayJSON['data'] = GET_FILTERED($searchVal, $page, $size);
            $arrayJSON['recordsFiltered'] = $results;
            $arrayJSON['recordsTotal'] = $conta;
            echo json_encode($arrayJSON);
        } else {
            $arrayJSON['data'] = GET($page, $size);
            $arrayJSON['recordsFiltered'] = $conta;
            $arrayJSON['recordsTotal'] = $conta;
            echo json_encode($arrayJSON);
        }
        break;



    default:
        header("HTTP/1.1$stmt-> 400 BAD REQUEST");
        break;
}

function contaRighe()
{
    require("connessione.php");
    $query = "SELECT count(*) FROM employees";

    $result = $mysqli->query($query);
    $row = $result->fetch_row();

    return $row[0];
}   //fare i robo per le pag

function contaRisultati($filter)
{
    require("connessione.php");
    $string = '%' . $filter . "%";
    $stmt = mysqli_prepare($mysqli, "SELECT count(*) FROM employees 
                      WHERE id = ?
                      OR first_name like ?
                      OR last_name like ?
                ");
    $stmt->bind_param("iss", $filter, $string, $string);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_row();

    return $row[0];
}

function GET_FILTERED($searchValue, $page, $lenght)
{
    require("connessione.php");
    $string = '%' . $searchValue . "%";
    $rows = array();
    $result;


    $stmt = mysqli_prepare($mysqli, "SELECT * FROM employees WHERE id = ? OR first_name like ? OR last_name like ? ORDER BY id LIMIT ?, ?");

    $stmt->bind_param("issii", $searchValue, $string, $string, $page, $lenght);

    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = mysqli_fetch_assoc($result)) {
        $rows[] = $row;
    }

    return $rows;
}


//metodi get, post, ecc...

function GET($page, $lenght)
{
    require("connessione.php");
    $query = "SELECT * FROM employees ORDER BY id LIMIT $page, $lenght";
    $rows = array();

    if ($result = $mysqli->query($query)) {
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
    }

    return $rows;
}
?>