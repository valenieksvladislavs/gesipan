<?php
	$id_categories = (int)$_GET['categories'];
	$sort = clear_str($_GET['sort']);
	$id_town = (int)$_GET['town'];
	$id_section = (int)$_GET['section'];
	$search = clear_str($_GET['search']);

	if(empty($id_town)) {
		$id_town = "%%";
	}

	if(empty($id_section)) {
		$id_section = "%%";
	}

	if(empty($id_categories)) {
		$id_categories = "%%";
	}

	$page = (int)$_GET['page'];
	$num = 5;
	$result00 = mysql_query("SELECT COUNT(*)
							FROM ".PREF."post
							WHERE ".PREF."post.confirm = '1'
							AND ".PREF."post.is_actual = '1'
							AND ".PREF."post.id_categories like '$id_categories'
							AND ".PREF."post.id_town like '$id_town'
							AND ".PREF."post.id_section like '$id_section'
							AND ".PREF."post.title like '%$search%'");
	$temp = mysql_fetch_array($result00);
	$posts = $temp[0];
	$total = (($posts - 1) / $num) + 1;
	$total = (int)$total;
	if($page > $total) $page = $total;
	if(empty($page) or $page < 0) $page = 1;
	$start = $page * $num - $num;
	$current_page = "<span>".$page."</span>";

	$posts = get_posts($id_categories,$id_categories,$sort,$id_section,$id_town,$start,$num,$search);

	if($page != 1) $prevpage = "<a href='/?action=".$action."&id_categories=".$id_categories."&page=1&sort=".$sort."&town=".$id_town."&section=".$id_section."&search=".$search."' class='first-page'></a><a href='/?action=".$action."&id_categories=".$id_categories."&page=".($page - 1)."&sort=".$sort."&town=".$id_town."&section=".$id_section."&search=".$search."' class='prev-page'></a>";
	if($page != $total) $nextpage = "<a href='/?action=".$action."&id_categories=".$id_categories."&page=".($page + 1)."&sort=".$sort."&town=".$id_town."&section=".$id_section."&search=".$search."' class='next-page'></a><a href='/?action=".$action."&id_categories=".$id_categories."&page=".$total."&sort=".$sort."&town=".$id_town."&section=".$id_section."&search=".$search."' class='last-page'></a>";

	if($page - 5 > 0) $page5left = "<a href='/?action=".$action."&id_categories=".$id_categories."&page=".($page - 5)."&sort=".$sort."&town=".$id_town."&section=".$id_section."&search=".$search."'>".($page - 5)."</a>";
	if($page - 4 > 0) $page4left = "<a href='/?action=".$action."&id_categories=".$id_categories."&page=".($page - 4)."&sort=".$sort."&town=".$id_town."&section=".$id_section."&search=".$search."'>".($page - 4)."</a>";
	if($page - 3 > 0) $page3left = "<a href='/?action=".$action."&id_categories=".$id_categories."&page=".($page - 3)."&sort=".$sort."&town=".$id_town."&section=".$id_section."&search=".$search."'>".($page - 3)."</a>";
	if($page - 2 > 0) $page2left = "<a href='/?action=".$action."&id_categories=".$id_categories."&page=".($page - 2)."&sort=".$sort."&town=".$id_town."&section=".$id_section."&search=".$search."'>".($page - 2)."</a>";
	if($page - 1 > 0) $page1left = "<a href='/?action=".$action."&id_categories=".$id_categories."&page=".($page - 1)."&sort=".$sort."&town=".$id_town."&section=".$id_section."&search=".$search."'>".($page - 1)."</a>";

	if($page + 5 <= $total) $page5right = "<a href='/?action=".$action."&id_categories=".$id_categories."&page=".($page + 5)."&sort=".$sort."&town=".$id_town."&section=".$id_section."&search=".$search."'>".($page + 5)."</a>";
	if($page + 4 <= $total) $page4right = "<a href='/?action=".$action."&id_categories=".$id_categories."&page=".($page + 4)."&sort=".$sort."&town=".$id_town."&section=".$id_section."&search=".$search."'>".($page + 4)."</a>";
	if($page + 3 <= $total) $page3right = "<a href='/?action=".$action."&id_categories=".$id_categories."&page=".($page + 3)."&sort=".$sort."&town=".$id_town."&section=".$id_section."&search=".$search."'>".($page + 3)."</a>";
	if($page + 2 <= $total) $page2right = "<a href='/?action=".$action."&id_categories=".$id_categories."&page=".($page + 2)."&sort=".$sort."&town=".$id_town."&section=".$id_section."&search=".$search."'>".($page + 2)."</a>";
	if($page + 1 <= $total) $page1right = "<a href='/?action=".$action."&id_categories=".$id_categories."&page=".($page + 1)."&sort=".$sort."&town=".$id_town."&section=".$id_section."&search=".$search."'>".($page + 1)."</a>";

	$pagination = $prevpage.$page5left.$page4left.$page3left.$page2left.$page1left.$current_page.$page1right.$page2right.$page3right.$page4right.$page5right.$nextpage;
?>