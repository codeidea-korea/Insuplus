<?
class listManager {
	var $link_top, $link_pre, $link_next, $link_end, $limit, $total;
	var $setpg, $setln, $nowpg, $pgtop, $pgend;

	function listManager($nowpg, $setpg, $setln) {
		if($nowpg < 1) $nowpg = 1;
		$this->setpg = $setpg;
		$this->setln = $setln;
		$this->nowpg = $nowpg;
	}

	function setTotal($query) {
        $res = mysql_query($query);
        $z = mysql_num_rows($res);
        if($z == 1) {
            $row = mysql_fetch_row($res);
		    $this->total = $row[0];
        }
        else {
            $this->total = $z;
        }
		$this->setPage();
	}

	function getTotal() {
		return $this->total;
	}

	function setPage() {
		# 데이타 처리
		$allpg = ceil($this->total/$this->setln);
		$nowstep = ceil($this->nowpg/$this->setpg);
		$this->pgtop = 1 + ($nowstep-1)*$this->setpg;
		$this->pgend = $this->pgtop + $this->setpg - 1;
		if($this->pgend > $allpg) { $this->pgend = $allpg; }

		# 링크 만들기 ( 처음, 이전, 다음, 끝 )
		$this->link_top = 1;
		if($nowstep > 1) { $this->link_pre = $this->pgtop - $this->setpg; }
		if($this->total > $this->pgtop*$this->setpg*$this->setln) { $this->link_next = $this->pgend + 1; }
		$this->link_end = $allpg;

		# 현재 limit 포인터
		$this->limit = ($this->nowpg-1) * $this->setln;
	}

	function getPage() {
		return Array($this->pgtop, $this->pgend, $this->link_top, $this->link_pre, $this->link_next, $this->link_end);
	}

	function getList($query) {
		$i = 0;
		$res = mysql_query($query . " limit $this->limit, $this->setln");
		while($row = mysql_fetch_assoc($res)) {
			$row["_number"] = $this->total - ($this->limit + 1) - ($i - 1);
			$rows[$i] = $row;
			$i++;
		}

		return $rows;
	}
}

function listTitleSort($title, $self_key, $self_order = "ASC", $path="") {
	$_GET[kc] = strtoupper($_GET[kc]);
	$self_order = strtoupper($self_order);
	if($_GET[ko] == $self_key) {
		if($_GET[kc] == "ASC") {
			$_GET[kc] = "DESC";
			$url = url_clear("{$_SERVER[PHP_SELF]}?{$_SERVER[QUERY_STRING]}&ko={$self_key}&kc={$_GET[kc]}&nowpg=");
			$title = "<a href='{$url}' title='내림차순으로 정렬하기'>{$title}<img src='{$path}/img/asc_order.png' width='7' height='7' border='0'></a>";
		}
		else {
			$_GET[kc] = "ASC";
			$url = url_clear("{$_SERVER[PHP_SELF]}?{$_SERVER[QUERY_STRING]}&ko={$self_key}&kc={$_GET[kc]}&nowpg=");
			$title = "<a href='{$url}' title='오름차순으로 정렬하기'>{$title}<img src='{$path}/img/desc_order.png' width='7' height='7' border='0'></a>";
		}
	}
	else {
		if($self_order == "ASC") {
			$url = url_clear("{$_SERVER[PHP_SELF]}?{$_SERVER[QUERY_STRING]}&ko={$self_key}&kc={$self_order}&nowpg=");
			$title = "<a href='{$url}' title='오름차순으로 정렬하기'>{$title}</a>";
		}
		else {
			$url = url_clear("{$_SERVER[PHP_SELF]}?{$_SERVER[QUERY_STRING]}&ko={$self_key}&kc={$self_order}&nowpg=");
			$title = "<a href='{$url}' title='내림차순으로 정렬하기'>{$title}</a>";
		}
	}
	return $title;
}
?>
