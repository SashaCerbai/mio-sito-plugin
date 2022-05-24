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
        $draw = $_SESSION["counter"] + 1;
        $urlDiBase = "http://localhost:8080/index.php";
        $query="select count(id) as tot from employees";
        
        //array del json
        $arrayJSON = array();
    
        //$arrayJSON['_links'] = links($page, $size, $last, $urlDiBase);

        /*$arrayJSON['page']=array(
            "size"=> $size,
            "totalElements"=> $conta,
            "totalPages"=> $last,
            "number"=> $page

        );*/

        //switch per GET, POST, ecc...
        switch($_SERVER['REQUEST_METHOD']){
    
            case 'POST':
                                                    
                if($searchVal!=""){
                    $arrayJSON['data'] = GET_FILTERED($searchVal,$page*$size, $size );
                    $arrayJSON['recordsFiltered'] = $conta;
                    $arrayJSON['recordsTotal'] = $conta;
                    echo json_encode($arrayJSON);
                }else{
                    $arrayJSON['data'] = GET($page*$size, $size);
                    $arrayJSON['recordsFiltered'] = $conta;
                    $arrayJSON['recordsTotal'] = $conta;
                    echo json_encode($arrayJSON);
                }
                echo $searchVal;
                break;
                        
            /*case 'POST':
                $data = json_decode(file_get_contents('php://input'), true);
                POST($data["first_name"], $data["last_name"], $data["gender"]);
    
                echo json_encode($data);
                break;*/
    
            /*case 'PUT':
                $data = json_decode(file_get_contents('php://input'), true);
                PUT($data["first_name"], $data["last_name"], $data["gender"], $id);
    
                echo json_encode($data);
                break;*/
    
            /*case 'DELETE':
                DELETE($id);
    
                if(($key = array_search('id: '. $id, $arrayJSON)) !== false){
                    unset($arrayJSON[$key]);
                }
    
                echo json_encode($arrayJSON);
                break;*/
            
            default:
                header("HTTP/1.1 400 BAD REQUEST");
                break;

        }

        function contaRighe(){
            require("connessione.php");
            $query = "SELECT count(*) FROM employees";
    
            $result = $mysqli-> query($query);
            $row = $result-> fetch_row();
    
            return $row[0];
        }   //fare i robo per le pag

        function contaRisultati($filter){
            require("connessione.php");
            $query = "SELECT count(*) FROM employees 
                      WHERE id like '$filter' 
                      OR birth_date like '$filter' 
                      OR first_name like '$filter' 
                      OR last_name like '$filter' 
                      OR gender like '$filter' 
                      OR hire_date like '$filter'";
            
            $result = $mysqli-> query($query);
            $row = $result-> fetch_row();
    
            return $row[0];
        }

        function GET_FILTERED($searchValue, $page, $lenght){
            require("connessione.php");
            $query = "SELECT * FROM employees
            WHERE id like '%$searchValue%'
            OR first_name like '%$searchValue%'
            OR birth_date like '%$searchValue%'
            OR last_name like '%$searchValue%'
            OR hire_date like '%$searchValue%'
            OR gender like '%$searchValue%'
            ORDER BY id LIMIT $page, $lenght";
    
            $rows = array();
    
            if($result = $mysqli-> query($query)){
                while($row = $result-> fetch_assoc()){
                    $rows[] = $row;
                }
            }
    
            return $rows;
        }

        function href($urlDiBase, $page, $size){
            return $urlDiBase . "?page=" . $page . "&size=" . $size;
        }
    
        //vari link
        function links($page, $size, $last, $urlDiBase){
            $links = array(
                "first" => array ( "href" => href($urlDiBase, 0, $size)),
                "self" => array ( "href" => href($urlDiBase, $page, $size), "templated" => true),
                "last" => array ( "href" => href($urlDiBase, $last, $size))
            );
            
            if($page > 0){
                $links["prev"] = array( "href" => href($urlDiBase, $page - 1, $size));
            }
            
            if($page < $last){
                $links["next"] = array ( "href" => href($urlDiBase, $page + 1, $size));
            }
            
            return $links;
        }

        //metodi get, post, ecc...

        function GET($page, $lenght){
            require("connessione.php");
            $query = "SELECT * FROM employees ORDER BY id LIMIT $page, $lenght";
            $rows = array();
    
            if($result = $mysqli-> query($query)){
                while($row = $result-> fetch_assoc()){
                    $rows[] = $row;
                }
            }
    
            return $rows;
        }
    
        function GET_BY_ID($id){
            require("connessione.php");
            $query = "SELECT * FROM employees WHERE id = $id";
            $rows = array();
    
            if($result = $mysqli-> query($query)){
                while($row = $result-> fetch_assoc()){
                    $rows[] = $row;
                }
            }
    
            return $rows;
        }
    
        function POST($firstN, $lastN, $g){
            require("connessione.php");
            $query = "INSERT INTO employees (first_name, last_name, gender) VALUES ('$firstN', '$lastN', '$g')";
            $result = $mysqli-> query($query);
    
        }
    
        function PUT($firstN, $lastN, $g, $id){
            require("connessione.php");
            $query = "UPDATE employees SET first_name = '$firstN', last_name = '$lastN', gender = '$g' WHERE id = '$id'";
            $result = $mysqli-> query($query);
            
        }
    
        function DELETE($id){
            require("connessione.php");
            $query = "DELETE FROM employees WHERE id = $id";
            $result = $mysqli-> query($query);
            
        }
