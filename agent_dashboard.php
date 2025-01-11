<?php
    include "./layout_top.php";

    if(!isset($_SESSION["agent"])) {
        header("Location: ".BASE_URL."agent-login");
        exit();
    }

    try{
        $stmtAgent=$pdo->prepare("
            SELECT
                COUNT(properties.id) as total_properties,
                COUNT(orders.id) as total_orders,
                agents.*
            FROM
                agents
            LEFT JOIN
                orders ON orders.agent_id=agents.id
            LEFT JOIN
                properties ON properties.agent_id=agents.id    
            WHERE
                agents.id=?
            GROUP BY
                agents.id
        ");
        $stmtAgent->execute([$_SESSION["agent"]["id"]]);
        $agent=$stmtAgent->fetch(pdo::FETCH_ASSOC);
    }catch(PDOException $err){
        $error_message=$err->getMessage();
    }
?>

<div class="page-top" style="background-image: url('https://placehold.co/1300x260')">
    <div class="bg"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Agent Dashboard</h2>
            </div>
        </div>
    </div>
</div>

<div class="page-content user-panel">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-md-12">
                <?php include "./layout_nav_agent.php"?>
            </div>
            <div class="col-lg-9 col-md-12">
                <h3>Hello, <?php echo $agent["full_name"]?></h3>
                <p>See all the statistics at a glance:</p>

                <div class="row box-items">
                    <div class="col-md-4">
                        <div class="box1">
                            <h4><?php echo $agent["total_properties"]?></h4>
                            <p>Properties</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box2">
                            <h4><?php echo $agent["total_orders"]?></h4>
                            <p>Orders</p>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="box3">
                            <h4>??</h4>
                            <p>Messages</p>
                        </div>
                    </div>
                </div>

                <h3 class="mt-5">Recent Properties</h3>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tbody>
                            <tr>
                                <th>SL</th>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                            <tr>
                                <td>1</td>
                                <td>1375 Stanley Avenue</td>
                                <td>Villa</td>
                                <td>New York</td>
                                <td>
                                    <span class="badge bg-success">Active</span>
                                </td>
                                <td>
                                    <a href="" class="btn btn-warning btn-sm text-white"><i class="fas fa-edit"></i></a>
                                    <a href="" class="btn btn-danger btn-sm" onClick="return confirm('Are you sure?');"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                            <tr>
                                <td>2</td>
                                <td>3780 Ash Avenue</td>
                                <td>Condo</td>
                                <td>Boston</td>
                                <td>
                                    <span class="badge bg-danger">Pending</span>
                                </td>
                                <td>
                                    <a href="" class="btn btn-warning btn-sm text-white"><i class="fas fa-edit"></i></a>
                                    <a href="" class="btn btn-danger btn-sm" onClick="return confirm('Are you sure?');"><i class="fas fa-trash-alt"></i></a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include "./layout_footer.php"?>