<?php
class Pagination
{
	public $limit;
	public $page = 1;
	
	public function __construct(TMethod $get, $limit = 20){
		$this->limit = $limit;
		if(isset($get->p)){
			$this->page = $get->p->int();
		}
	}
	
	public function getLimit()
	{
		if($this->page == 1){
			return " LIMIT ".$this->limit;
		}else{
			$start = $this->page * $this->limit - $this->limit;
			$end = $this->page * $this->limit;
			return " LIMIT {$start},{$this->limit}";
		}
	}
	
	public function printPagination($counts)
	{
		$pages = ceil($counts / $this->limit);
			if ($pages>1){
			echo '<div class="pagination"><ul>';
			for($i=1;$i<=$pages;$i++){
				?>
				<li <?php if($i==$this->page){ ?>class="disabled" <?php } ?>><a href="<?php echo $this->getLink($i); ?>"><?php echo $i ?></a></li>
				<?php
			}
			echo '</ul></div>';
		}
	}
	
	protected function getLink($page)
	{
		
		$uri = $_SERVER['REQUEST_URI'];
		//проверяем есть ли GET переменные
		if( strstr($uri, "?")){
			if( strstr($uri, "?p=")){ // есть ли p переменная
				$uri = preg_replace("/(p=[\d]+)/", "p=".$page, $uri);
				return $uri;
			}else{
				return $uri . "&p=".$page;
			}
		}else{
			return $uri . "?p=".$page;
		}
	}
}
?>