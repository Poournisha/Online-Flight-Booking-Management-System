<?php include_once 'helpers/helper.php'; ?>
<?php subview('header.php'); ?>
<link rel="stylesheet" href="assets/css/form.css">
<style>
.main-col {
    padding: 30px;
    background-color: whitesmoke;
    box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);  
    margin-top: 50px;   
}
.pass-form {
    background-color: white;
    border: 2px;
    border-style: dotted;
    padding: 20px;
    margin-top: 30px;
}

body {
  background: transparent;
}
@font-face {
  font-family: 'product sans';
  src: url('assets/css/Product Sans Bold.ttf');
  }
  h1 {
    font-size: 42px !important;
    margin-bottom: 20px;  
    font-family :'product sans' !important;
    font-weight: bolder;
  }
  input {
    border :0px !important;
    border-bottom: 2px solid #424242 !important;
    color :#424242 !important;
    border-radius: 0px !important;
    font-weight: bold !important;   
    margin-bottom: 10px;
  }
  label {
    color : #828282 !important;
    font-size: 19px;
  }  
@media screen and (max-width: 900px){
    body {
  background: #bdc3c7;  /* fallback for old browsers */
  background: -webkit-linear-gradient(to right, #2c3e50, #bdc3c7);  /* Chrome 10-25, Safari 5.1-6 */
  background: linear-gradient(to right, #2c3e50, #bdc3c7); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

}  
}
</style>
<?php
    if(isset($_GET['error'])) {
        if($_GET['error'] === 'invdate') {
          echo '<script>alert("Invalid date of birth")</script>';
      } else if($_GET['error'] === 'moblen') {
          echo '<script>alert("Invalid contact info")</script>';
      } else if($_GET['error'] === 'sqlerror') {
          echo"<script>alert('Database error')</script>";
      }
      echo"<script>location.replace(document.referer)</script>";
    }
    ?>
<?php if(isset($_SESSION['userId']) && isset($_POST['book_but'])) {   
    $flight_id = $_POST['flight_id'];
    $passengers = $_POST['passengers']; 
    $price = $_POST['price'];
    $class = $_POST['class'];
    $type = $_POST['type'];
    $ret_date = $_POST['ret_date'];
?>    
<main>
    <div class="container mb-5">
        <div class="col-md-12 main-col">
            <h1 class="text-center text-secondary">PASSENGER DETAILS</h1>  
            <form action="includes/pass_detail.inc.php" class="needs-validation mt-4" method="POST">
                <input type="hidden" name="type" value="<?php echo $type; ?>">   
                <input type="hidden" name="ret_date" value="<?php echo $ret_date; ?>">   
                <input type="hidden" name="class" value="<?php echo $class; ?>">   
                <input type="hidden" name="passengers" value="<?php echo $passengers; ?>">   
                <input type="hidden" name="price" value="<?php echo $price; ?>">   
                <input type="hidden" name="flight_id" value="<?php echo $flight_id; ?>">   

            <?php for($i = 1; $i <= $passengers; $i++) { ?>
                <div class="pass-form">  
                    <div class="form-row">
                        <div class="col-md">
                            <div class="input-group">
                                <label for="firstname<?php echo $i; ?>">Firstname</label>
                                <input type="text" name="firstname[]" id="firstname<?php echo $i; ?>" class="pl-0 pr-0" required style="width: 100%;">
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="input-group">
                                <label for="midname<?php echo $i; ?>">Middlename</label>
                                <input type="text" name="midname[]" id="midname<?php echo $i; ?>" class="pl-0 pr-0" required style="width: 100%;">
                            </div>
                        </div>

                        <div class="col-md">
                            <div class="input-group">
                                <label for="lastname<?php echo $i; ?>">Lastname</label>
                                <input type="text" name="lastname[]" id="lastname<?php echo $i; ?>" class="pl-0 pr-0" required style="width: 100%;">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md">
                            <div class="input-group">
                                <label for="mobile<?php echo $i; ?>">Contact No</label>
                                <input type="number" name="mobile[]" min="0" id="mobile<?php echo $i; ?>" required>
                            </div>
                        </div>
                        <div class="col-md">
                            <div class="form-group mt-3"> 
                                <label for="dob<?php echo $i; ?>">DOB</label>
                                <input id="dob<?php echo $i; ?>" name="date[]" type="text" required placeholder="MM/DD/YYYY">
                            </div>
                        </div>
                    </div>
                </div>   
            <?php } ?>    

            <div class="col text-center">
                <button name="pass_but" type="submit" class="btn btn-primary mt-4">
                    <div>
                        <i class="fa fa-lg fa-arrow-right"></i> Proceed  
                    </div>
                </button>
            </div>         
        </form>              
    </div>
</div>
<?php subview('footer.php'); ?> 

<script>
document.addEventListener("DOMContentLoaded", function () {
    let dobInputs = document.querySelectorAll("input[name='date[]']");

    dobInputs.forEach(function (dobInput) {
        dobInput.addEventListener("blur", function () {
            let dateValue = this.value.trim();
            let dateRegex = /^(0[1-9]|1[0-2])\/(0[1-9]|[12][0-9]|3[01])\/\d{4}$/; // MM/DD/YYYY

            if (!dateRegex.test(dateValue)) {
                alert("Please enter a valid date in MM/DD/YYYY format.");
                this.value = "";
            }
        });
    });
});
</script>

</main>

<?php } ?>
