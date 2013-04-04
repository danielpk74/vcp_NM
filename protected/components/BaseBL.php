<?php
/**
 * Description of BaseBL
 **/
abstract class BaseBL extends TPage
{
    protected function getPDO()
    {
        try 
        {
            $newPDO = new PDO(self::getConnectionString(), self::getUserName(), self::getPassword());
            $newPDO->query("SET NAMES 'utf8'"); 
            return $newPDO;			
        } catch (Exception $e) 
        {
            echo ($e);
        }
    }	
	
    protected function  getConnectionString()
    {
        return Yii::app()->request->db->connectionString;
    }	
	
    protected function getUserName()
    {
        
        return Yii::app()->request->db->username;
    }

    protected function getPassword()
    {
        return Yii::app()->request->db->password;
    }	
}
?>
