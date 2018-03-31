<?php
class GmFilter{

    private $_worldId; //int
    private $_guildFilter; //int guildId
    private $_gmId;// array
    private $_levelMini;// int
    private $_levelMaxi;// int
    private $_dateMaj;// int (date) <= a dateNow
    private $_gmProgressMin;// string
    private $_gmProgressMax;
    private $_limit;
    private $_intervalLimit;

    public function __construct(array $data)
    {
        if($data==null)throw new Exception('GmFilter: data est null');
        $this->setWorldId($data['worldId']);
        if(!isset($data['gmId']))$data['gmId']=array();
        $this->setGmId($data['gmId']);
        $this->setLevelMini($data['levelMini']);
        $this->setLevelMaxi($data['levelMaxi']);
        $this->setDateMaj($data['dateMaj']);
        $this->setGmProgressMin($data['gmProgressMin']);
        $this->setGmProgressMax($data['gmProgressMax']);
        if(isset($data['guildFilter']))$this->setGuildFilter($data['guildFilter']);
        $this->setLimit($data['limit'],$data['intervalLimit']);// limit c'est le nb page et interv le nombre par page
        $this->setIntervalLimit($data['intervalLimit']);

    }

    private function setWorldId($v)
    {
        $this->_worldId= (int) $v;
    }
    private function setGuildFilter($v)
    {
        if(!is_numeric($v))   throw new Exception('setGuildChecked to guildId');
        $this->_guildFilter=(int)$v;
    }
    private function setGmId(array $gm)
    {
        foreach($gm as $value)
        {
            if(!is_numeric($value))throw new Exception('setGmId: les gm doivent être int.');
        }
        $this->_gmId=$gm;
    }

    private function setLevelMini($c){
        if($c!=null && !is_numeric($c))throw new Exception('setLevelMini: doit être numéric.');
        if($c<0)throw new Exception('setLevelMini: doit être supérieur ou égale à 0.');
        $this->_levelMini=(int)$c;
    }
    private function setLevelMaxi($c){
        if($c!=null && !is_numeric($c))throw new Exception('setLevelMaxi: doit être numéric.');
        if($c!=0) {
            if ($c < $this->_levelMini) throw new Exception('setLevelMaxi: doit être > ou = à mini.');
        }
        $this->_levelMaxi=(int)$c;
    }

    private function setDateMaj($d)//nb de jours
    {
        if(!is_numeric($d) && $d!=null)throw new Exception('setDateMaj: doit être numéric.');
        if($d!=null && $d!=0){
            $DateMaj=new DateTime('now'.-$d.'days');
            $dateMaj=$DateMaj->format('U');
        }
       else{$dateMaj=null;}
        $this->_dateMaj=(int)$dateMaj;
    }

    private function setGmProgressMin($v){
        if($v<0 || $v>100){
            throw new Exception('setGmProgressMin doit etre 0 a 100');
        }

        $this->_gmProgressMin=(int)$v;
    }
    private function setGmProgressMax($v){
        if($v<0 || $v>100 || $v< $this->_gmProgressMin){
            throw new Exception('setGmProgressMax doit etre 0 a 100 et sup a min');
        }

        $this->_gmProgressMax=(int)$v;
    }
    private function setLimit($v,$m)
    {
        if($v==null) throw new Exception('limit ne peut pas etre null');
        $this->_limit=$v*$m;
    }
    private function setIntervalLimit($v)
    {
        if($v==null) throw new Exception('limit ne peut pas etre null');
        $this->_intervalLimit=$v;
    }

       public function getGuildFilter(){
        return $this->_guildFilter;
    }
    public function getGmId(){
        return $this->_gmId;
    }
    public function getLevelMini(){
        return $this->_levelMini;
    }
    public function getLevelMaxi(){
        return $this->_levelMaxi;
    }
    public function getDateNow(){
        return $this->_dateNow;
    }
    public function getDateMaj(){
        return $this->_dateMaj;
    }
    public function getGmProgressMin(){
        return $this->_gmProgressMin;
    }
    public function getGmProgressMax(){
        return $this->_gmProgressMax;
    }
    public function getWorldId(){
        return $this->_worldId;
    }
    public function getLimit(){
        return $this->_limit;
    }
    public function getIntervalLimit(){
        return $this->_intervalLimit;
    }




}