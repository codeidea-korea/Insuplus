<?
  #### 신규 카테고리 설정값(pc_nu, pc_sort) 세팅
  function setNewCategoryInfo($pre_pc_num) {
    $dbcon = $GLOBALS["dbcon"];

    if (getLen($pre_pc_num) > 0 ) {
      // 상위 카테고리가 있다면...
      /*
      $SQL = "
        select
          max(CAST(SUBSTRING(pc_num, ".(getLen($pre_pc_num) + 1).") AS UNSIGNED)) as new_pc_num
          , max(pc_sort) as new_pc_sort
        from tbl_product_category
        where
          1=1
          and length(pc_num) = ".(getLen($pre_pc_num) + 2)."
          and SUBSTRING(pc_num, 1, ".(getLen($pre_pc_num)).") = '".$pre_pc_num."'
      ";
      */
      $query_where = " and SUBSTRING(pc_num, 1, ".(getLen($pre_pc_num)).") = '".$pre_pc_num."' ";
    }
    else {
      // 신규 카테고리
      /*
      $SQL = "
        select
          max(CAST(SUBSTRING(pc_num, ".(getLen($pre_pc_num) + 1).") AS UNSIGNED)) as new_pc_num
          , max(pc_sort) as new_pc_sort
        from tbl_product_category
        where
          1=1
          and length(pc_num) = ".(getLen($pre_pc_num) + 2)."
      ";
      */
    }

    $SQL = "
      select
        max(CAST(SUBSTRING(pc_num, ".(getLen($pre_pc_num) + 1).") AS UNSIGNED)) as new_pc_num
        , max(pc_sort) as new_pc_sort
      from tbl_product_category
      where
        1=1
        and length(pc_num) = ".(getLen($pre_pc_num) + 2)."
        ".$query_where."
    ";


    if ($row = $dbcon -> fetch_array( $dbcon -> query($SQL) )) {
      $new_pc_num = (int)$row["new_pc_num"]+1;
      $new_pc_sort = (int)$row["new_pc_sort"]+1;
    }
    else {
      $new_pc_num = 1;
      $new_pc_sort = 1;
    }

    if ($new_pc_num < 10) $new_pc_num = "0".$new_pc_num;

    $new_pc_num = "".$pre_pc_num.$new_pc_num;

    $returnValue = Array($new_pc_num, $new_pc_sort);

//    echo $row[new_pc_num]."<BR>";
//    echo $new_pc_num."<BR>";
//    exit;
    return $returnValue;
  }


  #### Option 설정
  function setCategoryOption($pc_option) {
    $new_pc_option = "";
    for ( $i = 0; $i < count($pc_option) ; $i ++ ) {
      $new_pc_option .= REQSTR($pc_option[$i]);
      if ($i < count($pc_option) - 1) $new_pc_option .= "|";
    }
    return $new_pc_option;
  }


  #### 카테고리명 네비게이션 (상위 카테고리만 추출)
  function getPreCategoryName($pc_num, $type) {
    $dbcon = $GLOBALS["dbcon"];
    $query_where = "";

    // pc_num = pc_num (자기번호로 넘겼을때..., 수정시 사용)
    if ( $type == 1 ) {
      $query_where = " and pc_num <> '".$pc_num."' ";
    }
    // pc_num = pre_pc_num (상위번호로 넘겼을때..., 하위 카테고리 입력시 사용)
    elseif ( $type == 2 ) {
    }

    $query_where .= " and ( 1 <> 1 ";
    for ($i = 0 ; $i < getLen($pc_num) ; $i = $i + 2) {

      $query_where .= " or pc_num = SUBSTRING('".$pc_num."', 1, ".($i + 2).") ";
    }
    $query_where .= " ) ";

    $SQL = "
      select
        pc_num, pc_name
      from
        tbl_product_category
      where
        1=1
        ".$query_where."
      order by pc_num asc
    ";
    $RS = $dbcon -> query($SQL);
    return $RS;

  }

  function getCategoryName($pc_num) {
    $dbcon = $GLOBALS["dbcon"];
    $query_where = "";
    $SQL = "
      select
        pc_name
      from
        tbl_product_category
      where
        1=1
        and pc_num = '".$pc_num."'
    ";
    $RS = $dbcon -> getCount($SQL);
    return $RS;
  }


  #### 카테고리 전체목록 셀렉트 출력
  function getCategorySelect($pc_num) {
    $dbcon = $GLOBALS["dbcon"];

    $return_value = "";
    $SQL = "
      select pc_num, pc_name
      from tbl_product_category
      where
        1=1
        and pc_use = 'Y'
      order by pc_num
    ";
    $RS = $dbcon -> query($SQL);
    $return_value .= "<select name='pc_num' onchange=\"cateChange(this.value);\">";
    $return_value .= "<option>제품카테고리선택.</option>";
    while( $row = $dbcon->fetch_array($RS)) {

      $print_pc_name = "";

      $print_pc_name .= "├";
      for ($i = 0 ; $i < (getLen($row["pc_num"])/2); $i++) {
        $print_pc_name .= "─";
      }

      $print_pc_name .= (getLen($row["pc_num"])/2).". ".$row["pc_name"];
      $return_value .= "<option value=\"".$row["pc_num"]."\">".$print_pc_name."</option>";
    }
    $return_value .= "/<select>";
    return $return_value;
  }


  // 제품 리스트 하위 카테고리 네비게이션

  function getNextCategory($pc_num) {
    global $dbcon;

    $SQLcnt = "
      select count(*) from
        tbl_product_category
      where
        1=1
        and length(pc_num) = ".(getLen($pc_num)+2)."
        and pc_num like '".$pc_num."%'
    ";
    $cnt = $dbcon -> getCount($SQLcnt);

    // 하위 카테고리 가져오기
    $SQL = "
      select
        pc_num, pc_name
        , ( select count(pc_num) from tbl_product_category where length(pc_num) = length(A.pc_num) + 2 and SUBSTRING(pc_num, 1, length(A.pc_num) ) = A.pc_num ) as next_pc_num_cnt
      from
        tbl_product_category A
      where
        1=1
        and length(pc_num) = ".(getLen($pc_num)+2)."
        and pc_num like '".$pc_num."%'
      order by
        pc_sort asc
    ";
    $rs = $dbcon -> query($SQL) ;
    $no = 0;
    $printNavi = "";

    while ($row = $dbcon -> fetch_array($rs) ) {
      $no++;
      $printNavi .= "<a href=\"/product/product.php?pc_num=".$row["pc_num"]."\" class=\"hrb\">".$row["pc_name"]."</a>";
      if($no < $cnt){
        $printNavi .= "&nbsp;&nbsp;|&nbsp;&nbsp;";
      }
    }
    return $printNavi;
  }
  // 제품 리스트 하위 카테고리 네비게이션
  /*
  function getNextCategory($pc_num) {
    global $dbcon;
    // 하위 카테고리 가져오기
    $SQL = "
      select
        pc_num, pc_name
        , ( select count(pc_num) from tbl_product_category where length(pc_num) = length(A.pc_num) + 2 and SUBSTRING(pc_num, 1, length(A.pc_num) ) = A.pc_num ) as next_pc_num_cnt
      from
        tbl_product_category A
      where
        1=1
        and length(pc_num) = ".(getLen($pc_num)+2)."
        and pc_num like '".$pc_num."%'
      order by
        pc_sort asc
    ";
    $rs = $dbcon -> query($SQL) ;
    $no = 0;

    $printNavi = "";
    while ($row = $dbcon -> fetch_array($rs) ) {
      $printNavi .= "<li ";
      if ($no == 0) $printNavi .= " class=\"first\" ";
      $printNavi .= " >";
      $printNavi .= "<a href=\"/product/product.php?pc_num=".$row[pc_num]."\" class=\"hrb\">".$row[pc_name]."</a>";
      $printNavi .= "</li>";

      $no++;
    }
    $firstprintNavi = "";
    $firstprintNavi .= "<div class=\"sub_navi\"><ul>";
    $lastprintNavi = "</ul>";
    $lastprintNavi .= "</div><!--.sub_navi-->";
    if ($no > 0) {
      $printNavi = $firstprintNavi.$printNavi.$lastprintNavi;
    }

    return $printNavi;
  }
  */

  // 2023-06-20 Kyle
  // Returns product category info.
  function getProductCatetories($options = null){
    $dbcon = $GLOBALS["dbcon"];

    $sql = "
      select 
        category_code
        , category_name
        , use_yn
        , parent_code
        , region
        , depth
      from tbl_board_category
    ";
    $where = " where use_yn = 'Y' ";
    $order = " order by region, depth ";

    if (isset($options) && is_array($options)){
      if(isset($options['region'])) {
        $where .= " and region = ".$options['region']." ";
      }
      if(isset($options['parent_code'])) {
        $where .= " and parent_code = '".$options['parent_code']."' ";
      }
      if(isset($options['depth'])) {
        $where .= " and depth = ".$options['depth']." ";
      }
    }

    $sql = $sql.$where.$order;
    $rs = $dbcon -> query($sql);

    while ($row = $dbcon -> fetch_array($rs)){
      $rows[count($rows)] = $row;
    }

    return $rows;
  }

  function getProductCatetoryMapping($seq){
    $dbcon = $GLOBALS["dbcon"];

    if (!isset($seq) || $seq == ""){
      return array();
    }

    $sql = "
    select 
        a.product_seq
        , a.category_code
        , b.category_name
        , b.parent_code
        , b.region
        , b.depth
        , b.use_yn
      from tbl_board_product_category a
        inner join tbl_board_category b on (a.category_code = b.category_code)
    ";
    $where = " where a.product_seq = ".$seq." ";
    $order = " order by b.depth ";

    $sql = $sql.$where.$order;
    $rs = $dbcon -> query($sql);

    while ($row = $dbcon -> fetch_array($rs)){
      $rows[count($rows)] = $row;
    }

    return $rows;
  }

  function getPlanGuarantee($pr_cd, $plan_seq){
    $dbcon = $GLOBALS["dbcon"];
    $rows = array();

    $SQL1 = "select * from tbl_board_product_category where product_seq = ".$pr_cd;    
    $rs1 = $dbcon -> query($SQL1);
    while ($row = $dbcon -> fetch_array($rs1)){
      $rows[count($rows)] = $row;
    }

    if(count($rows) > 0) {
      // 리뉴얼 오픈(2023-10-09) 이후 생성된 상품
      $sql = "
        select 
          z.seq
          , z.seq pr_cd
          , z.guarantee_seq
          , z.guarantee_name
          , z.guarantee_opt_seq
          , z.group_mn
          , z.group_mn_en
          , z.service_name
          , z.service_name_en
          , z.service_content
          , z.chk_service
          , z.ord
          , x.plan_seq
          , x.plan_cd
          , x.g_seq
          , x.g_name
          , x.g_amount
          , x.g_amount_certificate
          , x.ins_plan_name
          , if(ord = 1, x.guarantee1_ins_seq, x.guarantee2_ins_seq) guarantee_ins_seq
        from (
          select 
            a.seq
            , b.seq guarantee_seq
            , b.subject guarantee_name
            , c.idx guarantee_opt_seq
            , c.group_mn
            , c.group_mn_en
            , c.service_name
            , c.service_name_en
            , c.service_content
            , c.chk_service
            , 1 ord
          from (
              select seq, ext4, ext10 from tbl_board_product where seq = $pr_cd
            ) a
            inner join tbl_board_guarantee b on (a.ext4 = b.seq)
            inner join tbl_board_guarantee_opt c on (b.seq = c.list_seq)
          union all
          select 
            a.seq
            , b.seq guarantee_seq
            , b.subject guarantee_name
            , c.idx guarantee_opt_seq
            , c.group_mn
            , c.group_mn_en
            , c.service_name
            , c.service_name_en
            , c.service_content
            , c.chk_service
            , 2 ord
          from (
              select seq, ext4, ext10 from tbl_board_product where seq = $pr_cd
            ) a
            inner join tbl_board_guarantee b on (a.ext10 = b.seq)
            inner join tbl_board_guarantee_opt c on (b.seq = c.list_seq)
        ) z
          left join (
            select 
              a.seq plan_seq
              , a.pr_cd
              , a.guarantee1_ins_seq
              , a.guarantee2_ins_seq
              , a.plan_cd
              , b.g_seq
              , b.g_name
              , b.g_amount
              , b.g_amount_certificate
              , a.ins_plan_name
            from tbl_board_plan a
              inner join tbl_board_plan_guarantee b on (a.seq = b.plan_cd)
          ) x on (x.plan_seq = $plan_seq and x.pr_cd = z.seq and x.g_seq = z.guarantee_opt_seq)
        order by ord, group_mn_en, guarantee_opt_seq
      ";
    } else {
      // 리뉴얼 오픈(2023-10-09) 이전 생성된 상품
      $sql = "select 
            a.pr_cd seq
            , a.pr_cd
            , c.ext4 guarantee_seq
            , '' guarantee_name
            , '' guarantee_opt_seq
            , '' group_mn
            , '' group_mn_en
            , b.g_name service_name
            , b.g_name service_name_en
            , '' service_content
            , '' chk_service
            , 1 ord
            , a.seq plan_seq
            , a.pr_cd
            , a.plan_cd
            , b.g_seq
            , b.g_name
            , b.g_amount
            , b.g_amount_certificate
            , a.ins_plan_name
            , a.guarantee1_ins_seq guarantee_ins_seq
          from tbl_board_plan a
            inner join tbl_board_plan_guarantee b on (a.seq = b.plan_cd)
            inner join tbl_board_product c on (a.pr_cd = c.seq)
          where a.seq = ".$plan_seq." ";
    }

    $rows = array();
    $rs = $dbcon -> query($sql);

    while ($row = $dbcon -> fetch_array($rs)){
      $rows[count($rows)] = $row;
    }

    return $rows;
  }

  function getPlanService($pr_cd, $plan_seq){
    $dbcon = $GLOBALS["dbcon"];
    $rows = array();

    $SQL1 = "select * from tbl_board_product_category where product_seq = ".$pr_cd;    
    $rs1 = $dbcon -> query($SQL1);
    while ($row = $dbcon -> fetch_array($rs1)){
      $rows[count($rows)] = $row;
    }

    if(count($rows) > 0) {
      // 리뉴얼 오픈(2023-10-09) 이후 생성된 상품
      $sql = "
        select 
          a.seq
          , b.seq insplus_seq
          , b.subject insplus_name
          , c.idx insplus_opt_idx
          , c.service_group_name
          , c.service_name
          , c.service_name_en
          , d.plan_seq
          , d.plan_insplus_opt_seq
          , d.k_name
          , d.k_amount
          , d.e_name
          , d.e_amount
          , d.exposure_order
          , d.insuplus_opt_idx
        from tbl_board_product a
          inner join tbl_board_insuplus b on (a.ext5 = b.seq)
          inner join tbl_board_insuplus_opt c on (b.seq = c.list_seq)
          left join (
            select
              t1.seq plan_seq
              , t1.pr_cd
              , t2.s_seq plan_insplus_opt_seq
              , t2.k_name
              , t2.k_amount
              , t2.e_name
              , t2.e_amount
              , t2.exposure_order
              , t2.insuplus_opt_idx
            from tbl_board_plan t1
              inner join tbl_board_plan_insuplus t2 on (t1.seq = t2.plan_seq)
          ) d
      ";

      $sql = $sql." on (d.plan_seq = ".($plan_seq == "" ? 0:$plan_seq)." and d.pr_cd = a.seq and d.insuplus_opt_idx = c.idx) ";
      $where = "where a.seq = ".$pr_cd." ";
      $orderBy = "order by c.idx, d.exposure_order";
      $sql = $sql.$where.$orderBy;

    } else {
      // 리뉴얼 오픈(2023-10-09) 이전 생성된 상품
      $sql = "
        select
          t1.pr_cd seq
          , t3.ext5 insplus_seq
          , '' insplus_name
          , '' insplus_opt_idx
          , '' service_group_name
          , t2.k_name service_name
          , t2.e_name service_name_en
          , t1.seq plan_seq
          , t2.s_seq plan_insplus_opt_seq
          , t2.k_name
          , t2.k_amount
          , t2.e_name
          , t2.e_amount
          , t2.exposure_order
          , t2.insuplus_opt_idx
        from tbl_board_plan t1
          inner join tbl_board_plan_insuplus t2 on (t1.seq = t2.plan_seq)
          inner join tbl_board_product t3 on (t1.pr_cd = t3.seq)
        where t1.seq = ".$plan_seq." ";
    }

    $rs = $dbcon -> query($sql);

    $rows = array();
    while ($row = $dbcon -> fetch_array($rs)){
      $rows[count($rows)] = $row;
    }

    return $rows;
  }

  function getPlanInsurance($plan_seq){
    $dbcon = $GLOBALS["dbcon"];
    $rows = array();

    $sql = "
      select 
        a.seq plan_cd
        , b.subject
        , b.ins_name_en
        , b.imgfile2 eng_img
        , c.file_realname kor_img
      from (
          select seq, guarantee1_ins_seq, guarantee2_ins_seq from tbl_board_plan where seq = $plan_seq
        ) a 
        inner join tbl_board_ins_list b on (b.seq = a.guarantee1_ins_seq)
        inner join tbl_file c on (c.bc_id = 'ins_list' and c.seq = b.seq)
      union all
      select 
        a.seq plan_cd
        , b.subject
        , b.ins_name_en
        , b.imgfile2 eng_img
        , c.file_realname kor_img
      from (
          select seq, guarantee1_ins_seq, guarantee2_ins_seq from tbl_board_plan where seq = $plan_seq
        ) a 
        inner join tbl_board_ins_list b on (b.seq = a.guarantee2_ins_seq)
        inner join tbl_file c on (c.bc_id = 'ins_list' and c.seq = b.seq)
    ";

    $rs = $dbcon -> query($sql);

    $rows = array();
    while ($row = $dbcon -> fetch_array($rs)){
      $rows[count($rows)] = $row;
    }

    return $rows;
  }

  function getPlanAmountTable($plan_seq, $plan_type = 'G'){
    $dbcon = $GLOBALS["dbcon"];
    $rows = array();

    $sql = "
    SELECT 
        a.plan_cd, a.plan_txt, gender, age, a.plan_type 
        , a.period1, a.period2, a.period3, a.period4, a.period5
        , a.period6, a.period7, a.period8, a.period9, a.period10
        , a.period11, a.period12, a.period13, a.period14, a.period15
        , a.period16, b.chk_period 
        , case b.chk_period when 'Y' then '단기' when 'N' then '장기' else '' end chk_period_name
      FROM tbl_board_plan_amount1 a
        INNER JOIN tbl_board_plan b on (a.plan_cd = b.seq)
      WHERE a.plan_cd = $plan_seq and a.plan_type = '$plan_type'
    ";

    $rs = $dbcon -> query($sql);

    $rows = array();
    while ($row = $dbcon -> fetch_array($rs)){
      $rows[count($rows)] = $row;
    }

    return $rows;
  }

  function getPlanNameWithCategory($categories){
    $dbcon = $GLOBALS["dbcon"];
    $rows = array();
    $depth0 = isset($categories['depth0']) ? $categories['depth0']:'';
    $depth1 = isset($categories['depth1']) ? $categories['depth1']:'';
    $depth2 = isset($categories['depth2']) ? $categories['depth2']:'';
    $depth3 = isset($categories['depth3']) ? $categories['depth3']:'';

    $sql = "
      select
        pr_cd
        , plan_cd
        , plan_name
        , guide
      from (
        select 
          c.seq plan_seq
          , c.content
          , if (c.ext1 != 'N', 'Y', c.ext1) ext1
          , if (c.ext2 != 'N', 'Y', c.ext2) ext2
          , if (c.ext3 != 'N', 'Y', c.ext3) ext3
          , b.ext7 as purpose
          , c.pr_cd
          , c.plan_cd
          , d.plan_name
          , c.common_amount
          , c.s_date
          , c.s_date_time
          , c.e_date
          , c.e_date_time
          , b.ext6 guide
        from (
            select t1.depth0, t1.depth1, t1.depth2, t1.depth3, sub_a.product_seq
            from (
              select p.product_seq, replace(group_concat(p.category_code order by c.depth asc), ',', '') as category_codes
              from tbl_board_product_category p
              inner join tbl_board_category c on p.category_code = c.category_code
              group by p.product_seq
            ) sub_a  
            inner join (
              select '$depth0' depth0, '$depth1' depth1, '$depth2' depth2, '$depth3' depth3
            ) t1 on (sub_a.category_codes = concat(t1.depth0, t1.depth1, t1.depth2, t1.depth3))
          ) a 
          left join tbl_board_product b on (a.product_seq = b.seq AND b.secret = 'Y')
          left join tbl_board_plan c on (
                                          b.seq = c.pr_cd 
                                          AND STR_TO_DATE(CONCAT(s_date, ' ', LPAD(s_date_time, 2, '0')), '%Y-%m-%d %H') <= NOW() 
                                          AND STR_TO_DATE(CONCAT(e_date, ' ', LPAD(e_date_time, 2, '0')), '%Y-%m-%d %H') >= NOW()
                                        )
          left join (
            select 1 plan_cd, 'Lv1' plan_name union all
            select 2 plan_cd, 'Lv2' plan_name union all
            select 3 plan_cd, 'Lv3' plan_name union all
            select 4 plan_cd, 'Lv4' plan_name union all
            select 5 plan_cd, 'Lv5' plan_name
          ) d on (c.plan_cd = d.plan_cd)
      ) z
      where pr_cd is not null 
      group by pr_cd, plan_cd, plan_name
    ";

    $rs = $dbcon -> query($sql);

    $rows = array();
    while ($row = $dbcon -> fetch_array($rs)){
      $rows[count($rows)] = $row;
    }

    return $rows;
  }

  /**
   * 단순 함수명 중복으로 "_new" 붙임 기존 함수와 전혀 관련 없음
   * api list
   * getPlans - 선택한 상품을 이용해 등록된 플랜 목록
   * getGuarantee - 선택한 상품에 등록된 모든 보장내역 목록
   * getService - 선택한 상품에 등록된 모든 서비스 목록
   */
  function getPlanInfo_new($options){
    $dbcon = $GLOBALS["dbcon"];
    $rows = array();
    $sql = '';
    $rs = '';

    $apiCode = isset($options['api']) ? $options['api']:'';
    $planSeq = isset($options['plan_seq']) ? $options['plan_seq']:''; // plan uniq id
    $planCode = isset($options['plan_cd']) ? $options['plan_cd']:'';  // plan_cd column of plan table
    $productCode = isset($options['pr_cd']) ? $options['pr_cd']:'';
    $keyword = isset($options['keyword']) ? $options['keyword']:'';

    if ($apiCode == 'getPlans'){
      $sql = "
        select 
          a.seq plan_seq
          , a.content
          , a.pr_cd
          , a.plan_cd
          , b.ext5  service_seq
          , b.ext4  guarantee_seq1
          , b.ext10 guarantee_seq2
          , if(a.ext1 = 'N','N', 'Y') ext1
          , if(a.ext2 = 'N','N', if (b.ext10 is null or b.ext10 = '', 'N', 'Y')) ext2
          , if(a.ext3 = 'N','N', 'Y') ext3
          , b.ext3 as notice
          , b.ext7 as purpose
          , c.file_name ins_term1_name
          , c.file_realname ins_term1_realname
          , d.file_name ins_term2_name
          , d.file_realname ins_term2_realname
          , e.file_name service_term_name
          , e.file_realname service_term_realname
          , b.is_notification_visible as is_notification_visible
          , a.ins_plan_name
          , a.s_date
          , a.s_date_time
          , a.e_date
          , a.e_date_time
          , a.plan_status   
          , a.service_amount_per_day
        from tbl_board_plan a
          inner join tbl_board_product b on (a.pr_cd = b.seq)
          left join tbl_file c on (a.ins_term1_seq = c.seq and c.bc_id = 'ins_agree')
          left join tbl_file d on (a.ins_term2_seq = d.seq and d.bc_id = 'ins_agree')
          left join tbl_file e on (a.service_cd = e.seq and e.bc_id = 'service_agree')
        where a.pr_cd = $productCode 
      ";

      if ($planCode != ''){
        $sql .= " and a.plan_cd = " . $planCode ;
      }

    } else if ($apiCode == 'getGuarantee'){
      $rows = getPlanGuarantee($productCode, $planSeq);
      return $rows;
    } else if ($apiCode == 'getAnotherGuarantees'){
      $planSeqs = explode("|", $planSeq);
      $planSeqs = array_values(array_filter($planSeqs));
      $length = count($planSeqs);
      
      for ($i = 0; $i < $length; $i++) {
          $rows[$i] = getPlanGuarantee($productCode, $planSeqs[$i]);
      }

      return $rows;
    } else if ($apiCode == 'getServiceGroup'){
      return $Arr_option_group_value_of_insplus;
    } else if ($apiCode == 'getService'){
      $sql = "
        select 
          a.pr_cd
          , a.seq plan_seq
          , a.plan_cd
          , c.insuplus_opt_idx
          , f.service_group_name
          , f.service_name
          , f.service_name_en
          , c.k_amount
          , c.e_amount
          , f.chk_service
          , a.ins_plan_name
        from tbl_board_plan a
          inner join (
            select $productCode pr_cd, $planCode plan_cd
          ) b on (a.pr_cd = b.pr_cd and a.plan_cd = b.plan_cd)
          inner join tbl_board_plan_insuplus c on (a.seq = c.plan_seq)
          inner join tbl_board_product d on (a.pr_cd = d.seq)
          inner join tbl_board_insuplus e on (d.ext5 = e.seq)
          inner join tbl_board_insuplus_opt f on (e.seq = f.list_seq and c.insuplus_opt_idx = f.idx)
        order by c.insuplus_opt_idx, f.service_group_name
      ";
    } else if ($apiCode == 'getPlanPrice'){
      $sql = "
        select
          a.plan_cd plan_seq
          , b.pr_cd
          , b.plan_cd
          , a.plan_txt
          , a.gender
          , a.age
          , a.period1
          , a.period2
          , a.period3
          , a.period4
          , a.period5
          , a.period6
          , a.period7
          , a.period8
          , a.period9
          , a.period10
          , a.period11
          , a.period12
          , a.period13
          , a.period14
          , a.period15
          , a.period16
          , a.plan_type
          , b.service_amount_per_day
        from tbl_board_plan_amount1 a
          inner join tbl_board_plan b on (a.plan_cd = b.seq)
        where a.plan_cd = $planSeq
      ";
    } else if ($apiCode == 'getProductCountry'){
      $sql = "
        select idx, pr_seq, c_code, c_area, c_name, trip_yn, order_number
        from tbl_board_product_country
        where pr_seq = $productCode
        order by order_number IS NULL, order_number ASC, c_name ASC
      ";
    } else if ($apiCode == 'getNotice'){
      $sql = "
        select 
          idx
          , pr_seq
          , pr_notice
        from tbl_board_product_notice 
        where pr_seq = $productCode
      ";
    } else if ($apiCode == 'getPartnership'){
      $sql = "
        select 
          seq partnership_seq
          , ext1  partnership_category_code
          , partnership_name
          , partnership_code
          , tracking_url
          , partnership_charge
          , date_format(start_partner_period, '%Y-%m-%d') start_partnership_period
          , date_format(end_partner_period, '%Y-%m-%d') end_partnership_period
        from tbl_board_partner
        where tracking_url like concat('%', '$keyword')
      ";
    } else if ($apiCode == 'getAge') {
      return fn_ins_age($keyword);
    }

    $rs = $dbcon -> query($sql);

    while ($row = $dbcon -> fetch_array($rs)){
      $rows[count($rows)] = $row;
    }

    return $rows;
  }

  //인슈플러스 가입자정보 테이블 출력    
  function makeInsJoinInfo($info, $chk_lang) {
    $join_table = "";
if($chk_lang === "K") {    //국문
  $join_table .= '<tr>
      <th width="60" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">계약자명</th>
      <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["CONTRACTOR_KR"].'</td>
      <th width="60" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">가입기간</th>
      <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["INS_PERIOD"].'</td>
    </tr>
    <tr>
      <th width="60" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">가입자</th>
      <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["USER_NAME"].'</td>
      <th width="60" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">생년월일</th>
      <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["USER_BIRTH"].'</td>
    </tr>
    <tr>
      <th width="60" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">연락처</th>
      <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["USER_PHONE"].'</td>
      <th width="60" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">이메일</th>
      <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-left: 1px solid #d6d6d6;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["USER_EMAIL"].'</td>
    </tr>
    <tr>
      <th width="60" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">상품플랜</th>
  <td colspan="1" style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["PLAN_NAME"].'</td>
      <th width="60" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">결제금액</th>
      <td colspan="1" style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.number_format($info["AMOUNT"]).'원</td>
        </tr>
    <tr>
      <th width="60" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">가입번호</th>
      <td colspan="3" style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["ORDERNO"].'</td>
    </tr>';
} else if($chk_lang === "E") {    //영문
  $join_table .='<tr>
        <th width="145px" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">Name</th>
        <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["USER_ENAME"].'</td>
        <th width="115px" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">Registration No.</th>
        <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["USER_BIRTH"].'</td>
      </tr>
      <tr>
        <th width="145px" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">Phone</th>
        <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["USER_PHONE"].'</td>
        <th width="115px" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">Email</th>
        <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["USER_EMAIL"].'</td>
      </tr>
      <tr>
        <th width="145px" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">Policy Number</th>
        <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["STOCK_NO"].'</td>
            <th width="115px" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:11px;">Country of Departure</th>
        <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">Worldwide</td>

      </tr>
                <tr>
        <th width="145px" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">Plan</th>
        <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["ENG_PLAN_NAME"].'</td>
        <th width="115px" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:11px;">Premium</th>
        <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">(KRW) '.number_format($info["AMOUNT"]).'</td>
     
        </tr>
<tr>
    <th width="115px" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">PolicyPeriod</th>
        <td colspan="3" style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["INS_PERIOD"].'</td>
  
</tr>

      <tr>
        <th width="145px" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">Membership No.</th>
        <td colspan="3" style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["ORDERNO"].'</td>
      </tr>';
}    
    return $join_table;
}

  //플라잉닥터스 가입자정보 테이블 출력
    function makeFlyingJoinInfo($info, $chk_lang) {
        $join_table = "";
    if($chk_lang === "K") {    //국문
      $join_table .= '<tr>
          <th width="100" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">계약자명</th>
          <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["USER_NAME"].'</td>
          <th width="100" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">증권번호</th>
          <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["STOCK_NO"].'</td>
        </tr>
        <tr>
          <th width="60" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">가입자명</th>
          <td colspan="3" style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["USER_NAME"].'</td>
        </tr>
        <tr>
          <th width="60" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">생년월일</th>
          <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["USER_BIRTH"].'</td>
          <th width="60" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">가입기간</th>
          <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px;font-size:14px;">'.$info["INS_PERIOD"].'</td>
        </tr>
        <tr>
          <th width="60" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">가입상품</th>
          <td colspan="3" style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["INS_NAME"].' '.$info["PLAN_NAME"].'</td>
        </tr>
        <tr>
          <th width="60" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">출국국가</th>
          <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["NATION_NAME"].'</td>
          <th width="60" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">가입목적</th>
          <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["PURPOSE"].'</td>
        </tr>';
    } else {    //영문
      $join_table .='<tr>
          <th width="140px" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">Name of Policy Holder</th>
          <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["CONTRACTOR_EN"].'</td>
          <th width="140px" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">Policy Number</th>
          <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["STOCK_NO"].'</td>
        </tr>
        <tr>
          <th width="140px" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">Name</th>
          <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["USER_ENAME"].'</td>
          <th width="140px" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">Registration No.</th>
          <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["USER_BIRTH"].'</td>
        </tr>
        <tr>
          <th width="140px" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">Phone</th>
          <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["USER_PHONE"].'</td>
          <th width="140px" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">Email</th>
          <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["USER_EMAIL"].'</td>
        </tr>
        <tr>
          <th width="140px" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">Policy Number</th>
          <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["STOCK_NO"].'</td>
          <th width="140px" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">PolicyPeriod</th>
          <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["INS_PERIOD"].'</td>
        </tr>
        <tr>
          <th width="140px" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">Plan</th>
          <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["ENG_PLAN_NAME"].'</td>
          <th width="140px" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">Country of Departure</th>
          <td style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">Worldwide</td>
        </tr>
        <tr>
          <th width="140px" style="background-color: #f2f7ff;font-weight: 400;text-align: center;border-bottom: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">Membership No.</th>
          <td colspan="3" style="background-color: #ffffff;font-weight: 400;text-align: left;border-bottom: 1px solid #d6d6d6;border-left: 1px solid #d6d6d6;padding: 5px 15px;font-size:14px;">'.$info["ORDERNO"].'</td>
        </tr>';
    }    
        return $join_table;
    }

  //인슈플러스 배너
  function makeInsCertHeader($lang) {
    $header = "";
    if($lang === "E"){
      $header = '<div style="width:100%; margin:0 0 15px 0; border-bottom:2px solid #dc3347">
              <img src="[DROOT]/html/images/join_certification_title2_220208.png" width="100%" />
            </div>';
    } else {
      $header = '<div style="width:100%; margin:0 0 15px 0; border-bottom:2px solid #dc3347">      
              <img src="[DROOT]/html/images/join_certification_title1_190909.png" width="100%" />
            </div>';
    }
    return $header;
  }
   //인슈플러스 배너 20250401
  function makeInsCertHeaderNew($lang) {
    $header = "";
    if($lang === "E"){
      $header = '<div style="width:100%; margin:0 0 15px 0; border-bottom:2px solid #dc3347">
              <img src="[DROOT]/html/images/join_certification_title2_250401.png" width="100%" />
            </div>';
    } else {
      $header = '<div style="width:100%; margin:0 0 15px 0; border-bottom:2px solid #dc3347">      
              <img src="[DROOT]/html/images/join_certification_title1_250401.png" width="100%" />
            </div>';
    }
    return $header;
  }

  //플라잉닥터스 배너
  function makeFlyingCertHeader($lang) {
    $header = "";
    if($lang === "E"){
      $header = '<div style="width:100%; margin:0 0 15px 0; border-bottom:2px solid #dc3347">
              <img src="[DROOT]/html/images/join_certification_flying_title_en.png" width="100%" />
            </div>';
    } else {
      $header = '<div style="width:100%; margin:0 0 10px 0; border-bottom:2px solid #dc3347">
              <img src="[DROOT]/html/images/join_certification_flying_title1_20221114.png" width="100%" />
            </div>';
    }
    return $header;
  }

  

  //인슈 BODY
  function makeInsCertBody($chk_lang, $JOIN_INFO_TABLE, $CHK_SERVICE, $G_TABLE) {
    $BODY = '';
    if($chk_lang === 'K') {
      $BODY = '<div style="padding:0 18px;">
            <h3 style="font-size:15px;font-weight: 400;margin: 0px 0px 18px 0;">플라잉닥터스는 <br>해외에서 발생하는 개인의 질병/상해 사고를 24시간 알람센터에서 대응하여, 안전하게 귀국할 수 있도록 도와드립니다.</h3>
            <h4 style="font-size: 14px;font-weight:400;background-color: #C21E2E;color: #fff;padding: 6px 10px;border-radius: 8px;margin:0px 0 5px 0;">가입정보</h4>
            <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-top: solid 2px #595959;border-bottom: solid 2px #595959; margin:0 0 14px 0;">
              '.$JOIN_INFO_TABLE.'
            </table>
            '.$CHK_SERVICE.'
            '.$G_TABLE.'
          </div>';
    } else {
      $BODY = '<div style="padding:0 25px;">
            <h3 style="font-size:15px;font-weight: 400;margin: 0px 0px 18px 0;">Flying Doctors helps you return home safely with medical assistance services 24/7 alarm center.</h3>
            <h4 style="font-size: 14px;font-weight:400;background-color: #C21E2E;color: #fff;padding: 6px 10px;border-radius: 8px;margin:20px 0 5px 0;">Subscription Information</h4>
            <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-top: solid 2px #595959;border-bottom: solid 2px #595959; margin:0 0 14px 0;">
              <tbody>
                '.$JOIN_INFO_TABLE.'
              </tbody>
            </table>
            '.$CHK_SERVICE.'
            '.$G_TABLE.'
          </div>';
    }
    return $BODY;
  }

  //인슈 BODY 20250401
  function makeInsCertBodyNew($chk_lang, $JOIN_INFO_TABLE, $CHK_SERVICE, $G_TABLE) {
    $BODY = '';
    if($chk_lang === 'K') {
      $BODY = '<div style="padding:0 18px;">
            <h3 style="font-size:15px;font-weight: 400;margin: 0px 0px 18px 0;">비즈인사이트는 <br>해외에서 발생하는 개인의 질병/상해 사고를 24시간 알람센터에서 대응하여, 안전하게 귀국할 수 있도록 도와드립니다.</h3>
            <h4 style="font-size: 14px;font-weight:400;background-color: #C21E2E;color: #fff;padding: 6px 10px;border-radius: 8px;margin:0px 0 5px 0;">가입정보</h4>
            <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-top: solid 2px #595959;border-bottom: solid 2px #595959; margin:0 0 14px 0;">
              '.$JOIN_INFO_TABLE.'
            </table>
            '.$CHK_SERVICE.'
            '.$G_TABLE.'
          </div>';
    } else {
      $BODY = '<div style="padding:0 25px;">
            <h3 style="font-size:15px;font-weight: 400;margin: 0px 0px 18px 0;">Biz Insight helps you return home safely with medical assistance services 24/7 alarm center.</h3>
            <h4 style="font-size: 14px;font-weight:400;background-color: #C21E2E;color: #fff;padding: 6px 10px;border-radius: 8px;margin:20px 0 5px 0;">Subscription Information</h4>
            <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-top: solid 2px #595959;border-bottom: solid 2px #595959; margin:0 0 14px 0;">
              <tbody>
                '.$JOIN_INFO_TABLE.'
              </tbody>
            </table>
            '.$CHK_SERVICE.'
            '.$G_TABLE.'
          </div>';
    }
    return $BODY;
  }




  //플라잉닥터스 BODY
  function makeFlyingCertBody($chk_lang, $chk_fly_type_name, $JOIN_INFO_TABLE, $CHK_SERVICE, $chk_kor_service, $G_TABLE) {
    $BODY = '';
    if($chk_lang === 'K') {
      $BODY = '<div style="padding:0 18px;">
            <h3 style="font-size:15px;font-weight: 400;margin: 0px 0px 10px 0;">'.$chk_fly_type_name.'는 해외에서 발생하는 개인의 질병/상해 사고를 24시간 알람센터에서 대응하여, 안전하게 귀국할 수 있도록 도와 드립니다.</h3>
            <h4 style="font-size: 14px;font-weight:400;background-color: #C21E2E;color: #fff;padding: 6px 32px;border-radius: 8px;margin:10px 0 5px 0;">가입정보</h4>
            <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-top: solid 2px #595959;border-bottom: solid 2px #595959; margin:0 0 14px 0;">
              <tbody>
              '.$JOIN_INFO_TABLE.'
              </tbody>
            </table>
            '.$CHK_SERVICE.'
            '.$chk_kor_service.'
            '.$G_TABLE.'
          </div>';
    } else {
      $BODY = '<div style="padding:0 25px;">
            <h3 style="font-size:15px;font-weight: 400;margin: 0px 0px 18px 0;">Korea Assistance helps you return home safely with medical assistance services 24/7 alarm center.</h3>
            <h4 style="font-size: 14px;font-weight:400;background-color: #C21E2E;color: #fff;padding: 6px 10px;border-radius: 8px;margin:20px 0 5px 0;">Subscription Information</h4>
            <table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-top: solid 2px #595959;border-bottom: solid 2px #595959; margin:0 0 14px 0;">
              <tbody>
              '.$JOIN_INFO_TABLE.'
              </tbody>
            </table>
            '.$CHK_SERVICE.'
            '.$G_TABLE.'
          </div>';
    }
    return $BODY;
  }

  //인슈 국문 footer
  function makeInsCertFooter() {
    return '<table width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#29354c; padding:20px;">
          <tr valign="middle">
            <td style="text-align:left;">
              <img src="[DROOT]/html/images/footer-logo1.png" align="absmiddle">
            </td>
            <td style="text-align:right;color:#fff;">
              <strong style="font-size:14px;">Tel: +82 2 360 2545</strong><br>B1, 7,Chungjeong-ro, Seodaemun-gu, Seoul, Korea
            </td>
          </tr>
        </table>';
  }

  //인슈 국문 footer
  function makeInsCertFooterNew() {
    return '<table width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#29354c; padding:20px;">
          <tr valign="middle">
            <td style="text-align:left;">
              <img src="[DROOT]/html/images/footer-logo1_20250401.png" align="absmiddle">
            </td>
            <td style="text-align:right;color:#fff;">
              <strong style="font-size:14px;">Tel: +82 2 360 2545</strong><br>F8, 7,Chungjeong-ro, Seodaemun-gu, Seoul, Korea
            </td>
          </tr>
        </table>';
  }

  //플라잉닥터스 footer
  function makeFlyingCertFooter($chk_type) {
    $FOOTER = "";
    if ($chk_type == "A") {
      $FOOTER = "<table width='100%' cellspacing='0' cellpadding='0' border='0' style='background:#29354c; padding:20px;'>";
      $FOOTER .= "<tr valign='middle'><td style='text-align:left;'><img src='[DROOT]/html/images/footer-logo1.png' align='absmiddle'></td>";
      $FOOTER .= "<td style='text-align:right;color:#fff;'>";
      $FOOTER .= "<strong style='font-size:14px;'>Tel: +82 2 360 2545</strong><br>B1, 7,Chungjeong-ro, Seodaemun-gu, Seoul, Korea</td></tr></table>";
    } else {
      $FOOTER = "<table width='100%' cellspacing='0' cellpadding='0' border='0' style='background:#29354c; padding:10px 20px;'>";
      $FOOTER .= "<tr valign='middle'><td style='text-align:left;'><img src='[DROOT]/html/images/footer-logo2.png?v=1' align='absmiddle'></td>";
      $FOOTER .= "<td style='text-align:right;color:#fff;'>";
      $FOOTER .= "<strong style='font-size:14px;'>Tel: +82 2 360 2525</strong><br>F8, 7,Chungjeong-ro, Seodaemun-gu, Seoul, Korea</td></tr></table>";
    }
    return $FOOTER;
  }
?>