<?php

    include '../../../includes/settings.php';
    error_reporting(0);

    if (!isset($_SESSION))
    {
        session_start();
    }

    if (!isset($_SESSION['username']))
    {
        header("Location: ../../../account/login.php");
        exit();
    }

?>

<?php
    function xss_clean($data)
    {
        return strip_tags($data);
    }
?>
                <?php
                   $id = xss_clean(mysqli_real_escape_string($con, $_GET['id']));
                   $client = xss_clean(mysqli_real_escape_string($con, $_GET['user']));
                ?>



<!DOCTYPE html>
<html lang="en">


<head>
    <title>SamzyAuth - A Secure Licensing Solution</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="title" content="SamzyAuth - A Secure Licensing Solution">
	<meta name="description" content="A Secure Licensing Solution for Developers">
	<meta property="og:type" content="website">
	<meta property="og:url" content="https://auth.samzy.dev">
	<meta property="og:title" content="SamzyAuth - A Secure Licensing Solution">
	<meta property="og:description" content="A Secure Licensing Solution for Developers">
	<meta property="og:image" content="">
	<meta property="twitter:card" content="summary_large_image">
	<meta property="twitter:url" content="https://auth.samzy.dev">
	<meta property="twitter:title" content="SamzyAuth - A Secure Licensing Solution">
	<meta property="twitter:description" content="A Secure Licensing Solution for Developers">
	<meta property="twitter:image" content="">
    <link rel="icon" type="image/jpg" href="https://samzy.dev/profile.jpg"/>
    <link href="https://fonts.googleapis.com/css?family=Nunito:400,600,700" rel="stylesheet">
    <link href="../../../bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="../../../assets/css/plugins.css" rel="stylesheet" type="text/css" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.css">
    <script src="https://cdn.jsdelivr.net/npm/notyf@3/notyf.min.js"></script>
    

    <link rel="stylesheet" type="text/css" href="../../../plugins/table/datatable/datatables.css">
    <link rel="stylesheet" type="text/css" href="../../../plugins/table/datatable/dt-global_style.css">

</head>
<body>
    
<?php 

    $id = xss_clean(mysqli_real_escape_string($con, $_GET['id']));
    $username = xss_clean(mysqli_real_escape_string($con, $_SESSION['username']));
    $result = mysqli_query($con, "SELECT * FROM `programs` WHERE `owner` = '$username' AND `id` = '$id'") or die(mysqli_error($con));
    $ban_check = mysqli_query($con, "SELECT * FROM `owners` WHERE `username` = '$username' AND `isbanned` = '1'") or die(mysqli_error($con));

    if (mysqli_num_rows($ban_check) >= 1)
    {
        echo "<meta http-equiv='Refresh' Content='0; url=../../../account/banned/'>";    
        exit();
    }

    while ($row = mysqli_fetch_array($result))
    {
        $isbanned = $row['banned'];
        $app_key = $row['authtoken'];
    }

    if ($isbanned == 1)
    {
        echo '
            <script type=\'text/javascript\'>
                
            const notyf = new Notyf();
            notyf
            .error({
                message: \'This program is banned!\',
                duration: 3500,
                dismissible: true
            });                
                
        </script>
        ';

        echo "<meta http-equiv='Refresh' Content='3; url=../../app.php'>";    
        die();
    }

    if (mysqli_num_rows($result) < 1)
    {
        echo '
            <script type=\'text/javascript\'>
                
            const notyf = new Notyf();
            notyf
            .error({
                message: \'Invalid ID Program, redirecting(...)\',
                duration: 3500,
                dismissible: true
            });                
                
        </script>
        ';

        echo "<meta http-equiv='Refresh' Content='3; url=../../app.php'>";    
        die();
    }

    $user_info = mysqli_query($con, "SELECT * FROM `users` WHERE `id` = '$client' AND `programtoken` = '$app_key'") or die(mysqli_error($con));

    if (mysqli_num_rows($user_info) <= 0)
    {
        
        
        echo '
            <script type=\'text/javascript\'>
                
            const notyf = new Notyf();
            notyf
            .error({
                message: \'Invalid user!\',
                duration: 3500,
                dismissible: true
            });                
                
        </script>
        ';
        

        echo "<meta http-equiv='Refresh' Content='3; url=../../app.php'>";    
        die();
    }
    else
    {
        while ($row = mysqli_fetch_array($user_info))
        {
            $client_username = $row['username'];
            $client_email = $row['email'];
            $client_level = $row['level'];
            $client_expires = $row['expires'];
            $client_hwid = $row['hwid'];
        }
    }
?>


    <div class="header-container fixed-top">
        <header class="header navbar navbar-expand-sm">

            <ul class="navbar-item theme-brand flex-row  text-center">
                <li class="nav-item theme-logo">
                </li>
                <li class="nav-item theme-text">
                <a href="https://auth.samzy.dev/auth" class="nav-link"> SamzyAuth </a>
                </li>
            </ul>

            <ul class="navbar-item flex-row ml-md-auto">




                <li class="nav-item dropdown user-profile-dropdown">
                    <a href="javascript:void(0);" class="nav-link dropdown-toggle user" id="userProfileDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                        <img src="../../../assets/img/profile-16.jpg" alt="avatar">
                    </a>
                    <div class="dropdown-menu position-absolute" aria-labelledby="userProfileDropdown">
                        <div class="">
                            <div class="dropdown-item">
                                <a href="../../../profile/"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-user"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg> Profile</a>
                            </div>
                            <div class="dropdown-item">
                                <a href="../../../account/logout.php"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-log-out"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg> Sign Out</a>
                            </div>
                        </div>
                    </div>
                </li>

            </ul>
        </header>
    </div>

    
    <div class="sub-header-container">
        <header class="header navbar navbar-expand-sm">
            <a href="javascript:void(0);" class="sidebarCollapse" data-placement="bottom"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></a>

        </header>
    </div>

    
    <div class="main-container" id="container">

        <div class="overlay"></div>
        <div class="search-overlay"></div>


        <div class="sidebar-wrapper sidebar-theme">
            
    <nav id="sidebar">
        <div class="shadow-bottom"></div>
            <ul class="list-unstyled menu-categories" id="accordionExample">
                <br><br>
                
                <?php
                    $id = xss_clean(mysqli_real_escape_string($con, $_GET['id']));
            ?>

            <li class="menu">
                <a href="../users.php?id=<?php echo $id; ?>" aria-expanded="false" class="dropdown-toggle">
                    <div class="">                                
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-corner-down-left"><polyline points="9 10 4 15 9 20"></polyline><path d="M20 4v7a4 4 0 0 1-4 4H4"></path></svg>
                        <span>Leave</span>
                    </div>
                </a>                        
            </li>
        </br>

           

            <li class="menu">
                <center><h6 style="color: lightgray">General</h6></center></p>
                <a href="../settings.php?id=<?php echo $id; ?>" aria-expanded="false" class="dropdown-toggle">
                    <div class="">                                
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-settings"><circle cx="12" cy="12" r="3"></circle><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z"></path></svg>
                        <span>Settings</span>
                    </div>
                </a>                        
            </li>

            <li class="menu">
                <a href="../licenses.php?id=<?php echo $id; ?>" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-align-right"><line x1="21" y1="10" x2="7" y2="10"></line><line x1="21" y1="6" x2="3" y2="6"></line><line x1="21" y1="14" x2="3" y2="14"></line><line x1="21" y1="18" x2="7" y2="18"></line></svg>
                        <span>Licenses</span>
                    </div>
                </a>                        
            </li>

            <li class="menu">
                <a href="../users.php?id=<?php echo $id; ?>" data-active="true" aria-expanded="true" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                        <span>Users</span>
                    </div>
                </a>                        
            </li>
            
            <li class="menu">                    
                <br>
                <br>
                <center><h6 style="color: lightgray">Security</h6></center></p>
                <?php
                        $username1 = xss_clean(mysqli_real_escape_string($con, $_SESSION['username']));
                        $grabinfo1 = mysqli_query($con, "SELECT * FROM `owners` WHERE `username` = '$username1'") or die(mysqli_error($con));
                        while ($row1 = mysqli_fetch_array($grabinfo1))
                        {
                            $subscription1 = $row1['premium'];
                        }
                        if ($subscription1 == "1")
                        {
                            echo "
                            <a href=\"protect.php?id=$id\"  aria-expanded=\"false\" class=\"dropdown-toggle\">                            
                            <div class=\"\">
                                <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"24\" height=\"24\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\" stroke-linecap=\"round\" stroke-linejoin=\"round\" class=\"feather feather-folder\"><path d=\"M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z\"></path></svg>
                                <span>Client Protection</span>
                            </div>
                        </a>";
                        }
                        ?>                  
                <a href="../variables.php?id=<?php echo $id; ?>" aria-expanded="false" class="dropdown-toggle">                            
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-share-2"><circle cx="18" cy="5" r="3"></circle><circle cx="6" cy="12" r="3"></circle><circle cx="18" cy="19" r="3"></circle><line x1="8.59" y1="13.51" x2="15.42" y2="17.49"></line><line x1="15.41" y1="6.51" x2="8.59" y2="10.49"></line></svg>
                        <span>Variables</span>
                    </div>
                </a>                        
            </li>

            <!--<li class="menu">
                <br>
                <br>
                <center><h6 style="color: lightgray">Extra</h6></center></p>
                <a href="https://docs.Samzyaputh.xyz" target="_blank" aria-expanded="false" class="dropdown-toggle">
                    <div class="">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-book"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>
                        <span>Documentation</span>
                    </div>
                </a>
            </li>-->
            
        </ul>
        
    </nav>

</div>

        <?php

            if (isset($_POST['update_user']))
            {
                $user_client = xss_clean(mysqli_real_escape_string($con, $_POST['client_username']));
                $pass_client = xss_clean(mysqli_real_escape_string($con, $_POST['client_password']));
                $lice_client = xss_clean(mysqli_real_escape_string($con, $_POST['client_license']));
                $lvl_client = xss_clean(mysqli_real_escape_string($con, $_POST['client_level']));
                $expires_client = xss_clean(mysqli_real_escape_string($con, $_POST['client_expires']));
                $hwid_client = xss_clean(mysqli_real_escape_string($con, $_POST['client_hwid']));

                if (strlen($user_client) > 40)
                {
                    
                    echo '
                        <script type=\'text/javascript\'>
                            
                        const notyf = new Notyf();
                        notyf
                        .error({
                            message: \'Username exceeds max character limit (40)!\',
                            duration: 3500,
                            dismissible: true
                        });                
                            
                    </script>
                    ';
        
                }

                if (strlen($pass_client) > 50)
                {
                    
                    
                    echo '
                        <script type=\'text/javascript\'>
                            
                        const notyf = new Notyf();
                        notyf
                        .error({
                            message: \'Password exceeds max character limit (50)!\',
                            duration: 3500,
                            dismissible: true
                        });                
                            
                    </script>
                    ';
                }

                if ($lvl_client > 10 || $lvl_client <= 0)
                {
                    
                    
                    echo '
                        <script type=\'text/javascript\'>
                            
                        const notyf = new Notyf();
                        notyf
                        .error({
                            message: \'Level must be between 1-10!\',
                            duration: 3500,
                            dismissible: true
                        });                
                            
                    </script>
                    ';
                    
                    
                }

                if (DateTime::createFromFormat('Y-m-d H:i:s', $expires_client) == FALSE)
                {
            
            
            
                    echo '
                        <script type=\'text/javascript\'>
                            
                        const notyf = new Notyf();
                        notyf
                        .error({
                            message: \'Invalid expiry date!\',
                            duration: 3500,
                            dismissible: true
                        });                
                            
                    </script>
                    ';
                
                }

                if (!empty($pass_client))
                {
                    $pass_encrypted = password_hash($pass_client, PASSWORD_BCRYPT);

                    $result = false;
                    if ($hwid_client == "N/A")
                    {
                        $result = mysqli_query($con, "UPDATE `users` SET `username` = '$user_client', `password` = '$pass_encrypted', `email` = '$lice_client', `level` = '$lvl_client', `expires` = '$expires_client' WHERE `programtoken` = '$app_key' AND `id` = '$client'") or die(mysqli_error($con));
                    }
                    else
                    {
                        $result = mysqli_query($con, "UPDATE `users` SET `username` = '$user_client', `password` = '$pass_encrypted', `email` = '$lice_client', `level` = '$lvl_client', `expires` = '$expires_client', `hwid` = '$hwid_client' WHERE `programtoken` = '$app_key' AND `id` = '$client'") or die(mysqli_error($con));
                    }
                    

                    if ($result)
                    {
                        
                        echo '
                            <script type=\'text/javascript\'>
                                
                            const notyf = new Notyf();
                            notyf
                            .success({
                                message: \'Successfully updated password!\',
                                duration: 3500,
                                dismissible: true
                            });                
                                
                        </script>
                        ';
                    

                        echo "<meta http-equiv='Refresh' Content='2'; url='".$_SERVER."'>";

                    }
                    else
                    {
                        
                        
                        echo '
                            <script type=\'text/javascript\'>
                                
                            const notyf = new Notyf();
                            notyf
                            .error({
                                message: \'Error when updating user!\',
                                duration: 3500,
                                dismissible: true
                            });                
                                
                        </script>
                        ';
                        
                    }
                }
                else
                {
                    $result = false;
                    if ($hwid_client == "N/A")
                    {
                        if (empty($pass_client))
                        {
                            $result = mysqli_query($con, "UPDATE `users` SET `username` = '$user_client', `email` = '$lice_client', `level` = '$lvl_client', `expires` = '$expires_client' WHERE `programtoken` = '$app_key' AND `id` = '$client'") or die(mysqli_error($con));
                        }
                        else
                        {
                            $result = mysqli_query($con, "UPDATE `users` SET `username` = '$user_client', `password` = '$pass_encrypted', `email` = '$lice_client', `level` = '$lvl_client', `expires` = '$expires_client' WHERE `programtoken` = '$app_key' AND `id` = '$client'") or die(mysqli_error($con));
                        }
                    }
                    else
                    {
                        if (empty($pass_client))
                        {
                            $result = mysqli_query($con, "UPDATE `users` SET `username` = '$user_client', `email` = '$lice_client', `level` = '$lvl_client', `expires` = '$expires_client', `hwid` = '$hwid_client' WHERE `programtoken` = '$app_key' AND `id` = '$client'") or die(mysqli_error($con));
                        }
                        else
                        {
                            $result = mysqli_query($con, "UPDATE `users` SET `username` = '$user_client', `password` = '$pass_encrypted', `email` = '$lice_client', `level` = '$lvl_client', `expires` = '$expires_client', `hwid` = '$hwid_client' WHERE `programtoken` = '$app_key' AND `id` = '$client'") or die(mysqli_error($con));
                        }
                    }


                    if ($result)
                    {
                        echo '
                            <script type=\'text/javascript\'>
                                
                            const notyf = new Notyf();
                            notyf
                            .success({
                                message: \'Successfully updated user!\',
                                duration: 3500,
                                dismissible: true
                            });                
                                
                        </script>
                        ';
                        
                        
                    

                        echo "<meta http-equiv='Refresh' Content='2'; url='".$_SERVER."'>";

                    }
                    else
                    {
                        
                        echo '
                            <script type=\'text/javascript\'>
                                
                            const notyf = new Notyf();
                            notyf
                            .error({
                                message: \'Error when updating user!\',
                                duration: 3500,
                                dismissible: true
                            });                
                                
                        </script>
                        ';
                        
                    
                    }
                }

            }

        ?>


        <div id="content" class="main-content">
            <div class="layout-px-spacing">
                
                <div class="row layout-top-spacing">
                    <div class="col-xl-12 col-lg-12 col-sm-12  layout-spacing">
                        <div class="widget-content widget-content-area br-6">
                        <form method="POST" action="">
                            <div class="table-responsive mb-4 mt-4">         
                            <h4>Editing User: <?php echo $client_username; ?></h4>
                            <br>
                            <h5>Username</h5>
                            <input type="text" id="row-5-age" class="form-control" name="client_username" value="<?php echo $client_username; ?>">
                            <br>

                            <h5>New Password</h5>
                            <input type="text" id="row-5-age" class="form-control" name="client_password" placeholder="Enter new password">
                            <br>

                            <h5>License Key</h5>
                            <input type="text" id="row-5-age" class="form-control" name="client_license" value="<?php echo $client_email; ?>">
                            <br>             
                            
                            <h5>Level</h5>
                            <input type="text" id="row-5-age" class="form-control" name="client_level" value="<?php echo $client_level; ?>">
                            <br>   
                            
                            <h5>Expires</h5>
                            <input type="text" id="row-5-age" class="form-control" name="client_expires" value="<?php echo $client_expires; ?>">
                            <br>   

                            <h5>HWID</h5>
                            <input type="text" id="row-5-age" class="form-control" name="client_hwid" value="<?php echo ($client_hwid == "RESET" ? "N/A" : $client_hwid); ?>">
                            <br>   

                            <button name="update_user" class="btn btn-primary mb-2">Update user</button>

                        </div>
                        </form>
                    </div>

                </div>

            </div>
            <div class="footer-wrapper">
                <div class="footer-section f-section-1">
                <p class=""><a target="_blank" href="https://github.com/YungSamzy/SamzyAuth">GitHub</p>
                </div>
                <div class="footer-section f-section-2">
                <p class=""><a href="https://samzy.dev/donate" target="_blank">Donate</a></p>
                </div>
            </div>
        </div>
        <!--  END CONTENT AREA  -->
    </div>
    <!-- END MAIN CONTAINER -->
    
    
    
    <!-- BEGIN GLOBAL MANDATORY SCRIPTS -->
    <script src="../../../assets/js/libs/jquery-3.1.1.min.js"></script>
    <script src="../../../bootstrap/js/popper.min.js"></script>
    <script src="../../../bootstrap/js/bootstrap.min.js"></script>
    <script src="../../../plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="../../../assets/js/app.js"></script>
    
    <script>
        $(document).ready(function() {
            App.init();
        });
    </script>
    <script src="../../../assets/js/custom.js"></script>
    <!-- END GLOBAL MANDATORY SCRIPTS -->

    <!-- BEGIN PAGE LEVEL SCRIPTS -->
    <script src="../../../plugins/table/datatable/datatables.js"></script>
    <script>
        $('#zero-config').DataTable({
            "oLanguage": {
                "oPaginate": { "sPrevious": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-left"><line x1="19" y1="12" x2="5" y2="12"></line><polyline points="12 19 5 12 12 5"></polyline></svg>', "sNext": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-arrow-right"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>' },
                "sInfo": "Showing page _PAGE_ of _PAGES_",
                "sSearch": '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-search"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>',
                "sSearchPlaceholder": "Search...",
               "sLengthMenu": "Results :  _MENU_",
            },
            "stripeClasses": [],
            "lengthMenu": [5, 10, 20, 50],
            "pageLength": 5 
        });
    </script>

</body>

</html>