<h1>Sitemap</h1>
<?php
class Klasse2 {
    public $klasse1;
    public $unset1;
    protected $unset2;
    private $unset3;
    
    public function __construct($k) {
        $this->klasse1 = $k;
    }
}

class Klasse1 {
    public $offentlig1 = 1;
    public $offentlig2 = array('stuff' => true, 0 => 'things', 1 => null);
    public $offentlig3= "";
    protected $klasse2;
    private $privat1;
    private $privat2;
    protected $beskyttet1 = false;
    
    public function __construct() {
        $this->klasse2 = new Klasse2($this);
        $this->privat1 = 'nuls';
        $this->privat2 = 123.23;
        $this->beskyttet1 = true;
    }
}
$k = new Klasse1();    
$r = new \core\Dispatch();
$arr = array(true,array(array(true,'null' => null),1.734, 'sub' => array('one', 2 , 'four', null, true),3));
dd($k,$r, $arr);
