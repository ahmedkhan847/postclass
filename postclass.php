<?php
include "DbConnection.php";

class Post
{

    protected $db = null;

    public function __construct()
    {

        $this->db = new DbConnection();

    }
    public function insertpost($a_name, $a_content, $imgname)
    {

        $con     = $this->db->OpenCon();
        $title   = $con->real_escape_string($a_name);
        $content = $con->real_escape_string($a_content);
        $img     = $con->real_escape_string($imgname);
        $query   = $con->prepare("INSERT INTO post(article_name, article_content, img) VALUES(?, ?, ?)");
        $query->bind_param("sss", $title, $content, $img);
        $result = $query->execute();
        if (!$result) {

            $error = $con->error;

            $this->db->CloseCon();
            return $error;
        }
        $result = true;
        return $result;
    }

    public function getarticle($articleid)
    {
        $con = $this->db->OpenCon();
        $id = $con->real_escape_string($articleid);

        $stmt = "SELECT article_name,article_content,img,DATE_FORMAT(date,'%d %b, %Y') as dates from post WHERE article_id = '$id'";

        $result = $con->query($stmt);

        if ($result->num_rows == 1) {
            $sql = $result;
        } else {
            $sql = "No article";
        }

        $this->db->CloseCon();

        return $sql;

    }

    public function deletearticle($articleid)
    {

        $con    = $this->db->OpenCon();
        $id = $con->real_escape_string($articleid);

        $query   = $con->prepare("DELETE FROM post WHERE articleid = ?");
        $query->bind_param("i",$id);
        $result =$query->execute();

        if (!$result) {

            $error = $con->error;
            $this->db->CloseCon();
            return $error;
        }
        $this->db->CloseCon();
        $result = true;
        return $result;
    }

    public function updatearticle($a_id, $a_content, $a_name, $a_image)
    {

        $con     = $this->db->OpenCon();
        $title   = $con->real_escape_string($a_name);
        $content = $con->real_escape_string($a_content);
        $img     = $con->real_escape_string($a_image);
        $query   = $con->prepare("UPDATE post SET article_name = ? , article_content = ?, img = ? WHERE article_id = ?");
        $query->bind_param("sssi", $title, $content, $img, $a_id);
        $result = $query->execute();
        if (!$result) {
            $error = $con->error;

            $this->db->CloseCon();
            return $error;
        }
        $result = true;
        return $result;

    }
}
?>