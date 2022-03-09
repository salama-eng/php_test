<?php
session_start();



$connect=mysqli_connect("localhost","root","","php_test");
if(isset($_POST["add_to_cart"]))
{
  if(isset($_SESSION["shopping_cart"]))
  {
    $item_array_id=array_column($_SESSION["shopping_cart"],'item_id');
    if(!in_array($_GET['id'],$item_array_id))
    {
        $count=count($_SESSION["shopping_cart"]);
        $item_array=array(
        "item_id"  =>$_GET["id"],
        "item_name"  =>$_POST["hidden_name"],
        "item_price"  =>$_POST["hidden_price"],
        "item_quantity"  =>$_POST["quantity"]
        );
        $_SESSION["shopping_cart"][$count]= $item_array;
    }
    else{ echo'<script>alert("item already added" )</script>';
      echo'<script>window.location="index.php"</script>';
      
    }
  }else
  {
    $item_array=array(
      "item_id"  =>$_GET["id"],
      "item_name"  =>$_POST["hidden_name"],
      "item_price"  =>$_POST["hidden_price"],
      "item_quantity"  =>$_POST["quantity"]
    );
  
    $_SESSION["shopping_cart"][0]=$item_array;

  }
}

if(isset($_GET["action"]))
{
  if($_GET["action"]=="delete")
  {
    foreach($_SESSION["shopping_cart"] as $keys=>$values)
    {
      if($values["item_id"]==$_GET["id"])
      {
        unset($_SESSION["shopping_cart"][$key]);
        echo '<script> alert("item removed</script>';
        echo'<script>window.location="index.php"</script>';
      }
    }
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link href="style.css" type="text/css" rel="stylesheet" media="all">
  <title>Document</title>
</head>
<body> <h3>Shopping cart</h3>
  <div class="container" style="display: flex;
   justify-items: center;flex-direction: column;" >
   <div style=" display:flex">
    <?php
$query="select * from products";

      $result=mysqli_query($connect,$query);

      if(mysqli_num_rows($result)!=0)
      {
        while($row=mysqli_fetch_assoc($result))
        {
         
        ?>
        <div clas="con" >
          <form method="post" action="index.php?action=add&id=<?php echo $row["id"];?>">
        
        
            <div class="con1">
           
              <img src="assets/<?php echo $row['product_img'];?>" alt="jee" width="180px">
              <h4><?php echo $row['product_name'];?></h4>
              <em>$<?php echo $row['price'];?></em>
              <input type="number" name="quantity" value="1">
              <input type="hidden" name="hidden_name" value="<?php echo $row['product_name'];?>">
              <input type="hidden" name="hidden_price" value="<?php echo $row['price'];?>">
              <input type="submit" name="add_to_cart" value="Add to cart">
            </div>
          </form>
        </div>
<?php

        }
      }
    ?>
 </div>
 <div></div>
 <h3>order datails cart</h3>
 <div >

   <table>
     <tr>
       <th>item name</th>
       <th>Quantity</th>
       <th>price</th>
       <th>total</th>
       <th>Action</th>
     </tr>
     <?php
     if(!empty($_SESSION["shopping_cart"]))
{
  $total=0;
  foreach($_SESSION['shopping_cart'] as $keys => $values)
  {
?>
<tr>
  <td><?php echo$values["item_name"]; ?></td>
  <td><?php echo$values["item_quantity"]; ?></td>
  <td><?php echo$values["item_price"]; ?></td>
  <td><?php echo number_format($values["item_quantity"]*$values["item_price"],2) ?></td>
  <td><a href="index.php?action=delete&id=<?php echo $values['item_id'];?>"> <span>Remove</span></a></td>
</tr>



<?php
   $total=$total+($values["item_quantity"]*$values["item_price"]);
   
  }

  ?>
 
  <tr> 
   <td>total</td>
   <td>$<?php echo number_format($total,2);?></td>
  </tr>
  <?php
}
     ?>
   </table>

 </div>



  </div>
</body>
</html>

