<?php
    $result = "";
    if(isset($_POST['delete']) || isset($_POST['update']) || isset($_POST['insert'])){
        // $postData->file 
        $ch = curl_init();
        $postData = array();
        curl_setopt($ch, CURLOPT_URL, 'https://us-central1-pybotoeg.cloudfunctions.net/store-file');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, true);
        if( isset($_POST['delete'])){
            $postData['fileName'] = $_FILES["del_file"]["name"];
            $postData['method'] = $_POST['delete'];
            $postData["content"] = null;
            //echo $postData;
        }
        if(isset($_POST['update'])){
            $postData["fileName"] = $_FILES["update_file"]["name"];
            $postData["content"] = file_get_contents($_FILES["update_file"]["tmp_name"]);
            $postData['method'] = $_POST['update'];
            #$postData = json_encode($_POST);
            #$json = json_decode($_POST);
            #$json->update_file = file_get_contents($_FILES["update_file"]["tmp_name"]);
            #echo $json;
        }
        if(isset($_POST['insert'])){
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
        $postJson = json_encode($postData);
        $headers = array();
        $headers[] = 'Authorization: _ENV["bearer (gcloud auth print-identity-token)"]';
        $headers[] = 'Content-Type: application/json';
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postJson);
        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            echo 'Error:' . curl_error($ch);
        }
        curl_close($ch);
    }
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <title>Manage Files</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
                    <td><input type="submit" name="update" value="Update"></td>
                    <td>
                    <?php
                    if(isset($_POST['update']))
                        echo($result);
                    else echo("");
                    ?>
                </tr>
            </form>
            <th colspan="3">
                <h3>Delete File: </h3>
            </th>
            <tr>
                <form method="post" action="manage_bucket.php">
                    <td>
                        <p><b>Enter File Name: </b></p>
                    </td>
                    <td><input type="text" id="del_file" name="del_file" /></td>
                    <td><input type="submit" name="delete" value="Delete"></td> <!-- onclick="callAPI();" -->
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
        </form>
    </div>
</body>
</html>