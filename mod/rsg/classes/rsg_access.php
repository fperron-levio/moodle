<?php
/**
 * Created by PhpStorm.
 * User: nmoller
 * Date: 14-06-26
 * Time: 08:57
 */

/* Todo: optmisation optionnelle: fusionner getCoursAccessList et getCapsuleVisits. Pas beaucoup de records, pas critique... */

require_once (__DIR__.'/../../../auth/rsg/classes/RSGUser.php');

/**
 * Class rsg_access
 * C'est la classe responsable des méthodes limitant l'accès d'un utilisateurs aux capsules selon l'état
 * de son abonnment.
 */
class rsg_access {
    private $user;

    public function __construct(auth\rsg\RSGUser $user){
        $this->user= $user;
    }

    public function getUser(){
        return $this->user;
    }

    public function getInitData(){
        $capsules = $this->getUserCapsules($this->getUser()->getId());
        
        $data=array('acl'=>null, 'visits'=>null); /* defaults */
        
        if ($this->user->isRSG()){
            $data['visits']=$this->getCapsuleVisits($capsules);
        }
        
        return $data;
    }

    public function getUserCapsules($userId){
        global $DB;
        
        $capsules=$DB->get_records('rsg_track',array('userid'=> $userId),'','rsgid, timeadduec, visits');
        
        return $capsules;
    }

    public function getCapsuleVisits($capsules){
        $ret=array();
        
        foreach($capsules as $cap){
            $ret[$cap->rsgid]=$cap->visits;
        }

        return $ret;
    }
}
