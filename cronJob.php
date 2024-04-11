<?
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/lib.php";
	include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.alrimTalk.php";
    include_once $_SERVER["DOCUMENT_ROOT"]."/_config/Func.mail.php";

    $SQL = "select
                a.o_name as name
                ,a.o_phone as phone
                ,b.o_email1 as email1
                ,b.o_email2 as email2
                ,b.pr_name as pr_name
                ,b.ins_name as ins_name
                ,b.plan_name as plan_name
                ,b.s_date as s_date
                ,b.e_date as e_date
                from tbl_order_list_join a
                left join tbl_order_list b on (a.orderno = b.orderno)
            where b.order_step ='2' and b.ins_period >= '60' and date(now()) = date(subdate(b.e_date, INTERVAL 7 DAY))
            UNION all            
            select
                a.o_name as name
                ,a.o_phone as phone
                ,b.o_email1 as email1
                ,b.o_email2 as email2
                ,b.pr_name as pr_name
                ,b.ins_name as ins_name
                ,b.plan_name as plan_name
                ,b.s_date as s_date
                ,b.e_date as e_date
                from tbl_order_list_join a
                left join tbl_order_list b on (a.orderno = b.orderno)
            where b.order_step ='2' 
                and b.chk_p ='Y' and b.ins_period >= '2'
                and date(now()) = date(subdate(b.e_date, INTERVAL 7 DAY))";

	$result = $dbcon -> query($SQL);
    $row= $dbcon -> fetch_array($result);
    

    $name =  all_seed_dec($row["name"]);
    $phone = all_seed_dec($row["phone"]);
    $email = all_seed_dec($row["email1"])."@".all_seed_dec($row["email2"]);
    $prName = $row["pr_name"];
    //$prName = $row["pr_name"]." ".$row["ins_name"]." ".$row["plan_name"];
    $sDate = $row["s_date"];
    $eDate = $row["e_date"];

    echo $name.", ".$phone.", ".$email.", "
    .$row["pr_name"].", ".$row["ins_name"].", ".$row["plan_name"].", "
    .$sDate.", ".$eDate;

    $param = array();
    $param["name"] = $name;
    $param["phone"] = $phone;
    $param["email"] = $email;
    $param["prName"] = $prName;
    $param["sDate"] = $sDate;
    $param["eDate"] = $eDate;
	
    kakaoSendReInsReg($param,$phone);
    echo "알림톡 전송 완료";
    mailReJoinSend($param,$email);
    echo "메일 전송 완료";
?>