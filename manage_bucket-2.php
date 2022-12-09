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
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous"> -->
</head>

<body>
    <div style="align-self:center;">
        <table style="margin-left: auto;margin-right: auto;">
            <thead colspan="3">
                <h2 style="text-align:center;">Manage Your Files</h2>
            </thead>
            <tr>
                <form enctype="multipart/form-data" method="post" action="manage_bucket.php">
                    <td>
                        <p><b>Insert New File: </b></p>
                    </td>
                    <td>
                        <input type="hidden" name="MAX FILE SIZE" value="3000000" />
                        <input type="file" id="insert_file" name="insert_file" />
                    </td>
                    <td><input type="submit" name="insert" value="Insert"></td>
                    <td>
                    <?php
                    
                    if(isset($_POST['insert'])){
                        echo($result);
                    }
                    else echo("");
                    ?>
                    </td>
                </form>
            </tr>
            <th colspan="3">
                <h3 style="text-align:center;">Update Existing File:</h3>
            </th>
            <tr>
            <form enctype="multipart/form-data" method="post" action="manage_bucket.php">
                <tr>
                    <td>
                        <p><b>Enter File Name: </b></p>
                    </td>
                    <td><input type="text" id="file_name" name="file_name" /></td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <input type="hidden" name="MAX FILE SIZE" value="3000000" />
                        <input type="file" name="update_file" id="update_file">
                    </td>
                    <!-- <td>
                        <select style="width:4rem; height:2rem; border-width:2px; border-radius:0.4rem;" name="file_name">
                            <?php
                            // if(isset($file_list) && count($file_list)>0){
                            //     foreach($file_list as $name){
                            //         echo "<option style='width:4rem; height:2rem;' value='".$name."'>".$name."</option>";
                            //     }
                            // }   
                            ?>
                        </select>
                    </td> -->
                    <!-- <td> -->
                    <td><input type="submit" name="update" value="Update"></td>
                    <td>
                    <?php
                    if(isset($_POST['update']))
                        echo($result);
                    else echo("");
                    ?>
                    </td>
                </tr>
            </form>
                        </tr>
                        <tr>
            <th colspan="3">
                <h3>Delete File: </h3>
            </th></tr>
            <tr>
                <form method="post" action="manage_bucket.php">
                    <td>
                        <p><b>Enter File Name: </b></p>
                    </td>
                    <td><input type="text" id="del_file" name="del_file" /></td>
                    <td><input type="submit" name="delete" value="Delete"></td>
                    <!-- <td>
                        <select style="width:4rem; height:2rem; border-width:2px; border-radius:0.4rem;" name="del_file">
                            <?php
                            // echo "=====> ".$file_list;
                            // if(count($file_list)>0){
                            //     echo "------> ".$file_list;
                            //     foreach($file_list as $name){
                            //         echo "<option style='width:4rem; height:2rem;' value='".$name."'>".$name."</option>";
                            //     }
                            // }   
                            ?>
                        </select> 
                    </td>-->
                    <td>
                        <?php
                        if(isset($_POST['delete'])){
                            echo($result);
                        }
                        else{
                            echo("");
                        }
                        ?>
                    </td>
                </form>
            </tr>
        </table>
        <!-- </form> -->
    </div>
    

</body>
</html>