<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Restaurant Management Dashboard</title>
  <!-- Bootstrap CSS -->
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="../assets/css/styles.css">
</head>
<body>

  <div class="container mt-4">
    <h1 class="mb-4 text-center">Restaurant Management Dashboard</h1>

    <div class="row">
      <div class="col-md-4">
        <div class="card mb-4">
          <div class="card-header">Employee Management</div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><a href="employee_CRUD/employee_add.php">Add Employee</a></li>
            <li class="list-group-item"><a href="view_edit_table.php?table=employees">View and Edit Employees</a></li>
          </ul>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card mb-4">
          <div class="card-header">Food Management</div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><a href="food_CRUD/food_add.php">Add Food Item</a></li>
            <li class="list-group-item"><a href="view_edit_table.php?table=food">View and Edit Food Items</a></li>
          </ul>
        </div>
      </div>

      <div class="col-md-4">
        <div class="card mb-4">
          <div class="card-header">Table Management</div>
          <ul class="list-group list-group-flush">
            <li class="list-group-item"><a href="orders_CRUD/orders_add.php">Assign/View Tables</a></li>
            <li class="list-group-item"><a href="view_edit_table.php?table=orders">Edit Tables</a></li>
          </ul>
        </div>
      </div>
    </div>
  </div>
    
  <!-- Return to Home Page Button -->
    <div class="text-center mt-4">
      <a href="../../index.php" class="btn btn-primary">Return to Home Page</a>
    </div>  

  <!-- Bootstrap JS and dependencies -->
  <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

</body>
</html>

