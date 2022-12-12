<?php 
function call_API($params){
    if(!empty($params)) {
        $ch = curl_init();
        //curl_setopt($ch, CURLOPT_URL, 'https://us-central1-pybotoeg.cloudfunctions.net/save-file');
        curl_setopt($ch, CURLOPT_URL, 'https://us-central1-pybotoeg.cloudfunctions.net/store-file');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        
        $postJson = json_encode($params);
        $headers = array();
        $headers[] = 'Authorization: _ENV["bearer (gcloud auth print-identity-token)"]';
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postJson);
        $result = curl_exec($ch);
        //---------------------------
        try {
            $result = curl_exec($ch);
        } catch (Exception $e) {
            echo '<script>alert("Error: "'. $e->getMessage() . ')</script>';
            //echo 'Error: ', $e->getMessage(), "\n";
        }
        //---------------------
        if (curl_errno($ch)) {
            echo '<script>alert("Error: "'.curl_errno($ch).' '. curl_error($ch) . ')</script>';
            //echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
        return $result;
    }
    else {
        echo '<script>alert("Invalid Input!")</script>';
    }
}
?>

<?php
    $result = "";
    $file_list=array();
    $postData = array();
    $displayHidden = false;
    $getData['method'] = 'Get_File_List';
    #$file_list = call_API($getData);
    #echo "huhxu---->".$j;
    #$json = json_decode($j, true);
    #if(isset($json['file_list']))
    #$file_list = $json['file_list'];
    if(isset($_POST['delete']) || isset($_POST['update']) || isset($_POST['insert'])){
        // $postData->file 
        // curl_setopt($ch, CURLOPT_URL, 'https://us-central1-pybotoeg.cloudfunctions.net/store-file');
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_POST, true);
        if( isset($_POST['delete']) && $_POST['']){
            $postData['fileName'] = $_POST["del_file"];
            $postData['method'] = $_POST['delete'];
            //$postData["content"] = null;
            //echo $postData;
        }
        if(isset($_POST['update']) && !($_FILES["update_file"]["error"] == 4)){
            if(!empty($_POST['file_name'])){
                $postData["fileName"] = $_POST['file_name'];
            } else {
                $postData["fileName"] = $_FILES["update_file"]["name"];
            }
            $postData["content"] = file_get_contents($_FILES["update_file"]["tmp_name"]);
            $postData['method'] = $_POST['update'];
            #$postData = json_encode($_POST);
            #$json = json_decode($_POST);
            #$json->update_file = file_get_contents($_FILES["update_file"]["tmp_name"]);
            #echo $json;
        }
        if(isset($_POST['insert']) && !empty($_FILES["insert_file"]["tmp_name"])){
            $postData['fileName'] = $_FILES["insert_file"]["name"];
            $postData['content'] = file_get_contents($_FILES["insert_file"]["tmp_name"]);
            $postData['method'] = $_POST['insert'];
            
            // $postData = json_encode($_POST);
            // $content = file_get_contents($_FILES["insert_file"]["tmp_name"]);
            // echo "content--> ".$content;
            // // echo "postData-->".json_decode($postData);
            // $file = $_FILES["insert_file"]["tmp_name"];
            // echo "files-->".$file;

            // $json = json_decode(strval($_POST), false);
            // $postData->user_file = file_get_contents($_FILES["insert_file"]["tmp_name"]);
            //echo "array---->".$postData;
        }
        
        $result = call_API($postData);
    }
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Files</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
</head>

<body>

    <nav class="navbar bg-danger navbar-dark shadow">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Manage Your Files</a>
        </div>
    </nav>

    <div class="container-fluid">

        <div class="row">
            <ul class="list-group mt-3 col-md-6 offset-md-3">
                <li class="list-group-item shadow mb-2 border rounded-0">
                    <form enctype="multipart/form-data" method="post" action="manage_bucket.php">
                        <div>
                            <label class="form-label h6 mt-3">Insert New File:</label>
                            <input type="hidden" name="MAX FILE SIZE" value="3000000" />
                            <input class="form-control" type="file" id="insert_file" name="insert_file" >
                        </div>
                        <div class="d-flex">
                            <input type="submit" class="btn btn-outline-danger mt-2 btn-sm ms-auto" name="insert" value="Insert"></td>
                        </div>
                        <?php
                        if(isset($_POST['insert'])){
                            echo($result);
                        }
                        else echo("");
                        ?>
                    </form>
                </li>
                <li class="list-group-item shadow mb-2 border rounded-0">
                    <label class="form-label h6 mt-3">Update Existing File:</label>
                    <form enctype="multipart/form-data" method="post" action="manage_bucket.php">
                        <div>
                            <label class="form-label">Enter File Name:</label>
                            <input type="text" class="form-control" id="file_name" name="file_name" placeholder="Enter File Name">
                        </div>
                        <div>
                            <input type="hidden" name="MAX FILE SIZE" value="3000000" />
                            <input class="form-control mt-2" type="file" name="update_file" id="update_file">
                        </div>
                        <div class="d-flex">
                            <input type="submit" class="btn btn-outline-danger mt-2 btn-sm ms-auto" name="update" value="Update"></td>
                        </div>
                        <?php
                        if(isset($_POST['update']))
                            echo($result);
                        else echo("");
                        ?>
                    </form>
                </li>
                <li class="list-group-item shadow mb-2 border rounded-0">
                    <label class="form-label h6 mt-3">Delete File:</label>
                    <form enctype="multipart/form-data" method="post" action="manage_bucket.php">
                        <div>
                            <label class="form-label">Enter File Name:</label>
                            <input type="text" class="form-control" id="del_file" name="del_file" placeholder="Enter File Name">
                        </div>
                        <div class="d-flex">
                            <input type="submit" class="btn btn-outline-danger mt-2 btn-sm ms-auto" name="delete" value="Delete"></td>
                        </div>
                        <?php
                            if(isset($_POST['delete'])){
                                echo($result);
                            }
                            else{
                                echo("");
                            }
                            ?>
                    </form>
                </li>
            </ul>
        </div>
    </div>
    
        
        <!-- </form> -->

</body>
</html>