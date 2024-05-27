<!-- START CONTENT -->
<div class="m-lg">
    <div class="row p-t-lg">
        <div class="col s4 m4 l4">
            <img class="responsive-img" width="40%" height="50%" src="<?php echo base_url() . PATH_GEN_IMAGE?>club_logo.png">
        </div>
        <div class="col s8 m8 l8 right">
            <div class="row">
                <div class="col s12"> 
                    <div class="row">
                        <?php if($this->session->userdata('logged_in')) : ?>
                        <?php $username = ($this->session->userdata['logged_in']['name']); ?>
                        
                        <div class="input-field col s6 right">
                            <span class="m-r-md"><b><?php echo "Welcome! "."$username"; ?></b></span> <a class="" href="<?php echo base_url() ?>Login/logout">Logout</a>
                        </div>
                        
                        <?php else: ?>
                        <?php echo form_open('Verify_login'); ?>
                        
                        <div class="input-field col s5">
                            <i class="material-icons prefix">account_circle</i>
                            <input id="username" name="username" type="text" class="validate">
                            <label for="username">Username</label>
                        </div>
                        <div class="input-field col s5">
                            <i class="material-icons prefix">lock</i>
                            <input id="password" name="password" type="password" class="validate">
                            <label for="password">Password</label>
                        </div>
                        <div class="input-field col s2 p-t-sm ">
                            <button class="waves-effect waves-light btn z-depth-1  cyan darken-3" name="login" type="submit">Login</button>
                        </div>
                        <div class="col s12 m12 l12"><h6 class="center">Don't Have Account? Please Contact : <a href="https://mail.google.com/mail/u/0/#inbox?compose=new" target="_blank"style="color:#00838f">clubnutridense@gmail.com</a></h6></div>
                        
                        <?php endif; ?>
                        <?php echo form_close(); ?>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CONTENT DISHES -->
<?php echo form_open('Home/verify_order'); ?>

<!-- ANNOUNCEMENT ROW -->
<?php if($this->session->userdata('logged_in')) { ?>

<div class="row p-t-md">
    <div class="col s8 offset-s2">
        <pre><h6> <?php echo $Content_trivia ?></h6></pre>
    </div>
</div>
<div class="row section featured p-t-md">
    <div class="col s12">
        <h2 class="section-title"><span>How to Order</span></h2>
    </div>
</div>
<div class="row" style="padding-left:130px">
    <div class="col s12">
        <img src="<?php echo base_url().PATH_GEN_IMAGE?>order.gif"></img>
    </div>
</div>
<div class="row section featured p-t-lg">
    <div class="col s12">
        <h2 class="section-title"><span>This Week's Menu</span></h2>
    </div>
</div>
<div id="dishes" class="m-b-sm"></div>
<div class="row" id="account">

    <!-- END OF ANNOUNCEMENT ROW-->
    <?php $day = date('N'); ?>
    <?php if(($day == 5) || ($day == 4)) :?>
    <?php $disabled = ""; ?>
    <?php else: $disabled = "disabled"; ?>
    <?php endif; ?>

<div class="col s12 m12 l12">
<div class="row ">
    <?php if (!EMPTY($tbl_dish)) { ?>
    <?php     $counter = 1; ?>
    <?php foreach($tbl_dish as $dish) : ?>
<div class="col s3 m-r-n p-b-lg">
    <div class="card small"  style="height: 250px !important;">
        <div class="card-image waves-effect waves-block waves-light">
            <img class="activator" src="<?php echo base_url()?><?php  echo  $dish->img_dish ; ?>">
        </div>
        <div class="card-content">
            <div class="row">
                <div class="col s7 m7 l7 p-r-sm">
                    <h6 class="card-title activator grey-text text-darken-4"> <?php  echo  $dish->name_dish ; ?></h6>
                    <p style="color:green"><?php  echo  $dish->name_day ; ?></p>
                </div>

                <div class="input-field col s2 m2 l2 right">
                    <input   name="dish_<?php echo ($counter-1);?>" type="checkbox" class="validate" id="dish<?php echo $counter?>" value="<?php  echo $dish->name_dish ;?>" <?php echo $disabled; ?>/>
                    <label class="active" for="dish<?php echo $counter?>"></label>
                </div>
                <div class="input-field col s3 m3 l3">
                    <input   name="qty[]" type="text" class="validate" value = ''  id="quantity<?php echo $counter?>" <?php echo $disabled; ?>/>
                    <label class="active" for="quantity<?php echo $counter?>">Quantity</label>
                </div>
            </div>
        </div>
    </div>
</div>
<?php  if($counter % 5 == 0) { ?>
<!-- MESSAGE CONTENT-->
<br><br>
<div class="input-field col s8 offset-s2 ">
    <i class="material-icons prefix icon-blue">mode_edit</i>
    <textarea id="icon_prefix2" class="materialize-textarea brown lighten-5" name="message" value="" <?php echo $disabled; ?>></textarea>
    <label for="icon_prefix2">Please paste here your fund confirmation # / your building site / Contact #</label>
    <button type="submit" class="waves-effect waves-light btn-large cyan darken-3 right" name="order" value="order" <?php echo $disabled; ?>> Submit Order <i class="material-icons right">send</i></button>
</div>
<div class="row">
    <div class="col s6 offset-s3">
        <br><br><br>
        <center><h6>For inquiries and assistance please call: 996-01-05</h6></center>
    </div>
</div>
<!-- END OF MESSAGE CONTENT-->  
<?php } $counter ++; ?>
<?php endforeach; }?>   
</div>
<!-- END OF DISHES -->
<!-- END CONTENT -->
</div>
</div>
    <?php echo form_close(); ?>
</div>
<?php } else { ?>
<div class="row section featured topspace">
    <div class="col s12">
        <h2 class="section-title"><span>This Week's Menu</span></h2>
    </div>
</div>
<div class="row">
    <div class="col s8 offset-s3">
        <h5>New orders are only accepted every Thursday and Friday</h5>
    </div>
</div>
<div class="m-b-sm"></div>
<div class="row p-b-xl" id="services-menu">
    <?php if (!EMPTY($tbl_dish)) { ?>
    <?php     $counter = 1; ?>
    <?php foreach($tbl_dish as $dish) : ?>
    <div class="col s3">
        <div class="card small" style="height: 260px !important;">
            <div class="card-image waves-effect waves-block waves-light">
                <img class="activator" src="<?php echo base_url()?><?php  echo  $dish->img_dish ; ?>">
                <div class="card-content">
                    <span class="card-title activator grey-text text-darken-4"> <?php  echo  $dish->name_dish ; ?></span>
                    <p style="color:green"><?php  echo  $dish->name_day ; ?></p>
                </div>
            </div>
        </div>
        <?php  if($counter % 5 == 0) { ?>
        <div class="col s2">
            <div class="row"></div>
        </div>
        <?php } $counter ++; ?>
        <?php endforeach; }?>   
    </div>
    <!-- END OF DISHES -->
    <!-- END CONTENT -->
</div>
<?php } ?>
