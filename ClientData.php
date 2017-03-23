<?php

class ClientData
{
	private $name;
	private $ipAddress;
	private $operatingSystemName;
	private $softwareVersion;
	private $freeHardDiskSpace;

	public function getName()
	{
		$name = php_uname('n');		
		return $name;
	}

	public function getIp()
	{
		if (getenv('HTTP_CLIENT_IP'))
			$ipAddress = getenv('HTTP_CLIENT_IP');
		else if(getenv('HTTP_X_FORWARDED_FOR'))
		    	$ipAddress = getenv('HTTP_X_FORWARDED_FOR');
		else if(getenv('HTTP_X_FORWARDED'))
		    	$ipAddress = getenv('HTTP_X_FORWARDED');
		else if(getenv('HTTP_FORWARDED_FOR'))
		    	$ipAddress = getenv('HTTP_FORWARDED_FOR');
		else if(getenv('HTTP_FORWARDED'))
		   	$ipAddress = getenv('HTTP_FORWARDED');
		else if(getenv('REMOTE_ADDR'))
		    	$ipAddress = getenv('REMOTE_ADDR');
		else
		    	$ipAddress = 'UNKNOWN';
		
		return $ipAddress;
	}

	public function getOperatingSystemName()
	{
		$operatingSystemName = php_uname('s');
		return $operatingSystemName;
	}

	public function getSoftwareVersion()
	{
		$softwareVersion = php_uname('v');
		return $softwareVersion;		
	}

	public function getFreeHDSpace()
	{
		$freeHardDiskSpace = disk_free_space(".");
		$freeHardDiskSpace = $freeHardDiskSpace / '1e+9';
		return $freeHardDiskSpace;
	}

	public function getAllInfo()
	{
		$cd = new ClientData();
		$cd->name = $cd->getName();
		$cd->ipAddress = $cd->getIp();
		$cd->operatingSystemName = $cd->getOperatingSystemName();
		$cd->softwareVersion = $cd->getSoftwareVersion();
		$cd->freeHardDiskSpace = $cd->getFreeHDSpace();

		// save all data to database
		$cd->saveAllInfoToDB($cd);

		return $cd;
	}

	public function saveAllInfoToDB($cd)
	{
		try
		{
			$pdo = new PDO('mysql:host=localhost;dbname=client_data', 'root', 'SecretPass!');
			$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    			$sql = "INSERT INTO client_data (name, ip_address, operating_system_name, software_version, free_hard_disk_space) VALUES (:name, :ipAddress, :operatingSystemName, :softwareVersion, :freeHardDiskSpace)";
    			$statement = $pdo->prepare($sql);
    			$statement->bindValue(':name', $cd->name);
    			$statement->bindValue(':ipAddress', $cd->ipAddress);
    			$statement->bindValue(':operatingSystemName', $cd->operatingSystemName);
    			$statement->bindValue(':softwareVersion', $cd->softwareVersion);
    			$statement->bindValue(':freeHardDiskSpace', $cd->freeHardDiskSpace);    			
			$statement->execute();
		} catch (PDOException $e)
		{
		    if ($e->getCode() == 1062)
		    {
		        // Take some action if there is a key constraint violation, i.e. duplicate name
		    } else
		    {
		        throw $e;
		    }
		}
		$pdo = null;
	}
}


?>