<!--sidebar-menu-->
<?php
$user = $_COOKIE['auth'];
list($admin_uid,$admin_type,$admin_name) = explode("\t", $user);
if ($admin_type == 1 || 1) {?>
    <div id="sidebar"><a href="#" class="visible-phone"><i class="icon icon-home"></i> 桌面</a>
    <ul>
        <li <?php if($this->mode == 'toIndex'){ ?>class="active"<?php } ?>><a href="index.php"><i class="icon icon-home"></i> <span>首&nbsp;页</span></a> </li>
        <?php
        global $menus;
        $memu_admin = $menus;
        if(!empty($memu_admin)){
            foreach ($memu_admin as $a_k => $a_v) {
                $url = "";
            if(in_array($a_k,$this->user_menu_list)){
                if ($a_k == 7 && $admin_type != 1 && $admin_type != 2&& $admin_type != 3) {
                    continue;
                }
                if($a_k){
                    ?>
                    <li class="<?php
                    $controller_class = "";
                    if(isset($a_v['son'])){
                        $controller_class = "submenu";
                        if($a_v['action'] == $this->actionPath){
                            $controller_class .= " open";
                        }
                    }else{
                        if($a_v['mode'] == $this->mode){
                            $controller_class = "active";
                        }
                        $url = "index.php";
                        if (isset($a_v['action'])) {
                            $url.= "?action={$a_v['action']}";
                        }
                        if (isset($a_v['mode'])){
                            $url .= "&mode={$s_v['mode']}";
                        }
                    }
                    echo $controller_class;
                    ?>">
                        <a href="<?php echo $url;?>">
                            <i class="icon icon-<?php echo $a_v['icon']?>"></i>
                            <span><?php echo $a_v['resource']?></span>
                            <?php if(isset($a_v['son'])){?>
                                <span class="label label-important"><?php echo count($a_v['son'])?></span>
                            <?php }?>
                        </a>
                        <?php if(isset($a_v['son'])){?>
                            <ul>
                                <?php
                                $parm_flag = false;
                                if(in_array($a_v['controller'], array('fragment','tag'))){
                                    $parm_flag = true;
                                }
                                foreach ($a_v['son'] as $s_k => $s_v) {
                                    $active_flag = false;

                                    if($a_v['action'].$s_v['mode'] == $this->actionPath.$this->mode){
                                        $active_flag = true;
                                    }
                                    $url = "index.php?action={$a_v['action']}&mode={$s_v['mode']}&n={$a_k}_{$s_k}";
                                    ?>
                                    <li class="<?php echo $active_flag?'active' : ' ' ?>">
                                        <a href="<?php echo $url?>"><?php echo $s_v['resource']?></a>
                                    </li>
                                <?php }?>
                            </ul>
                        <?php }?>
                    </li>
                <?php
                }}}}
        ?>
    </ul>
    <!--sidebar-menu-->
    </div><?php }?>
<script>

</script>