<?php

class Publipiwik_Website extends Publipiwik_Abstract{
	
	protected static $__CLASS__ = __CLASS__; // Static attribute must be implemented for "Singleton Pattern"
	
	// - SitesManager.addSite (siteName, urls, ecommerce = '', siteSearch = '', searchKeywordParameters = '', searchCategoryParameters = '', excludedIps = '', excludedQueryParameters = '', timezone = '', currency = '', group = '', startDate = '', excludedUserAgents = '', keepURLFragments = '', type = '')
	public function addWebSite($siteName, $urls){
		return $this->client->call("SitesManager.addSite", array(
			"siteName" => $siteName,
			"urls" => $urls
		));
	}
	
	public function updateWebSite($id, $siteName, $urls){
		return $this->client->call("SitesManager.updateSite", array(
			"idSite" => $id,
			"siteName" => $siteName,
			"urls" => $urls
		));
	}

	public function getWebSite($url){
		return $this->client->call("SitesManager.getSitesIdFromSiteUrl", array(
			"url" => $url,
		));
	}

	public function checkWebSiteExist($url){
		$res = $this->getWebSite($url);
		if(!empty($res)){
			return $res[0]["idsite"];
		}
		else{
			return false;
		}
	}

	public function getWebAppIdSite($document_id){
		return $this->client->call("SitesManager.getSitesIdFromSiteUrl",array(
			"url" => "http://m.publispeak.com/".$document_id,
		));
	}

	public function getUsersFromSite($idSite){
		return $this->client->call("UsersManager.getUsersAccessFromSite",array(
			"idSite" => $idSite
		));
	}

	public function getSiteFromId($idSite){
		return $this->client->call("SitesManager.getSiteFromId",array(
			"idSite" => $idSite
		));
	}

	public function getSitesAll(){
		return $this->client->call("SitesManager.getAllSites");
	}
}