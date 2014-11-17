<?php

/**
 * Assets pour cette classe : 
 * https://code.google.com/p/wkhtmltopdf/downloads/list
**/

class Publipiwik_Data extends Publipiwik_Abstract{

	protected static $__CLASS__ = __CLASS__; // Static attribute must be implemented for "Singleton Pattern"

	public static  $_customVarPageViewWebApp = "Page document";
	public static  $_customVarPageViewPersonna = "personna";

	public function getMapUrl($idSite,$period="day",$date="yesterday"){
		return self::$_url."/index.php?module=Widgetize&action=iframe&widget=1&moduleToWidgetize=UserCountryMap&actionToWidgetize=visitorMap&idSite=".$idSite."&period=".$period."&date=".$date."&disableLink=1&widget=1&token_auth=b7a10c60ddff395acfc1aba2b4063db5";
	}


	public function getDataForPeriod($idSite, $start,$stop){
		return $this->getGlobalData($idSite,"range",$start.",".$stop);
	}

	public function getSiteFromId($idSite){
		return $this->client->call("SitesManager.getSiteFromId",array(
			"idSite" => $idSite
		));
	}

	public function getWebAppCustomVar($idSite, $period="day",$date="yesterday"){
		return $this->client->call("CustomVariables.getCustomVariables",array(
			"idSite" => $idSite,
			"period" => $period,
			"date"	 => $date
		));
	}

	public function getPageData($idSite, $period="day",$date="yesterday"){
		return $this->client->call("Actions.get",array(
			"idSite" => $idSite,
			"period" => $period,
			"date"	 => $date
		));
	}

	public function getWebAppDocumentPageView($idSite, $period="day",$date="yesterday"){
		$idsubdatatable = null;
		$res = $this->getWebAppCustomVar($idSite,$period,$date); // On Récupere les customVar disponible.

		foreach ($res as $customVar) {
			if($customVar["label"] !== self::$_customVarPageViewWebApp)
				continue;
			$idsubdatatable = $customVar["idsubdatatable"];
			break;
		}
		if(is_null($idsubdatatable)){ // On vérifie que la variable correspondant au view sur la vue document est présente.
			return false;
		}
		else{
			$res = $this->client->call("CustomVariables.getCustomVariablesValuesFromNameId", array(
				"idSite" 	 => $idSite,
				"period" 	 => $period,
				"date"	 	 => $date,
				"idSubtable" => $idsubdatatable
			));
			return $this->prepareDataForCustomViewWebapp($res);
		}
	}

	public function getPersonnaview($idSite, $period="day",$date="yesterday"){
		$idsubdatatable = null;
		$res = $this->getWebAppCustomVar($idSite,$period,$date); // On Récupere les customVar disponible.

		foreach ($res as $customVar) {
			if($customVar["label"] !== self::$_customVarPageViewPersonna)
				continue;
			$idsubdatatable = $customVar["idsubdatatable"];
			break;
		}
		if(is_null($idsubdatatable)){ // On vérifie que la variable correspondant au view sur la vue document est présente.
			return false;
		}
		else{
			$res = $this->client->call("CustomVariables.getCustomVariablesValuesFromNameId", array(
				"idSite" 	 => $idSite,
				"period" 	 => $period,
				"date"	 	 => $date,
				"idSubtable" => $idsubdatatable
			));
			return $this->prepareDataForCustomViewWebapp($res);
		}
	}

	protected function prepareDataForCustomViewWebapp($data){
		$r = array();
		foreach ($data as $page) {
			$r[$page["label"]] = $page["nb_visits"];
		}
		ksort($r);
		return $r;
	}

	public function getGlobalData($idSite, $period="day",$date="yesterday"){
		return $this->client->call("VisitsSummary.get", array(
			"idSite" 	 => $idSite,
			"period" 	 => $period,
			"date"	 	 => $date,
		));
	}

	public function getDataForGraph($idSite, $cstart, $cstop){
		$r = $this->extractData($cstart->diffForHumans($cstop));

		$metric = array();
		if(($r["unit"] == "month" and $r["metric"] <= 1) or $r["unit"] == "weeks" or $r["unit"] == "week"){
			// echo 'stat en jour';
			$diffInDay = $cstart->diffInDays($cstop);
			for ($i=0; $i < $diffInDay; $i++) { 
				$tmp = $cstart->copy();
				$tmp->addDays($i);
				$metric[$i]["date"] = $tmp->toDateString();
				$metric[$i]["info"] = $this->getDataForPeriod($idSite,$tmp->toDateString(), $tmp->addDay()->toDateString());
			}
			// echo "<pre>";
			// var_dump($metric);
			// echo "</pre>";
		}

		else if(($r["unit"] == "month" or $r["unit"] == "months") and $r["metric"] >= 2){
			// echo 'stat en semaine';
			$diffInDay = $cstart->diffInDays($cstop);
			$diffInWeeks = $diffInDay / 7; // en semaine.

			for ($i=0; $i < $diffInWeeks; $i++) { 
				$tmp = $cstart->copy();
				$tmp->addDays($i * 7); // on ajoute une semaine complète.
				$metric[$i]["date"] = $tmp->toDateString();
				$metric[$i]["info"] = $this->getDataForPeriod($idSite,$tmp->toDateString(), $tmp->addDays(7)->toDateString());
			}
			// echo "<pre>";
			// var_dump($metric);
			// echo "</pre>";

		}
		else if($r["unit"] == "year" and $r["metric"] <= 1){
			// echo "stat en mois";
			$diffInMonths = $cstart->diffInMonths($cstop);
			for ($i=0; $i < $diffInMonths; $i++) { 
				$tmp = $cstart->copy();
				$tmp->addMonths($i);
				$metric[$i]["date"] = $tmp->toDateString();
				$metric[$i]["info"] = $this->getDataForPeriod($idSite,$tmp->toDateString(), $tmp->addMonth()->toDateString());
			}
			// echo "<pre>";
			// var_dump($metric);
			// echo "</pre>";

		}
		else{
			// echo "stat en année";
			$diffInYears = $cstart->diffInYears($cstop);
			for ($i=0; $i < $diffInYears; $i++) { 
				$tmp = $cstart->copy();
				$tmp->addYears($i);
				$metric[$i]["date"] = $tmp->toDateString();
				$metric[$i]["info"] = $this->getDataForPeriod($idSite,$tmp->toDateString(), $tmp->addYear()->toDateString());
			}
			// echo "<pre>";
			// var_dump($metric);
			// echo "</pre>";
		}
		return $metric;
	}

	protected function extractData($string){
		$tmp = explode(" ", $string);
		return array("metric" => $tmp[0], "unit" => $tmp[1]);
	}

	public static function prepareMetric($metric){
		$prepareMetric = array();

		foreach ($metric as $key => $m) {
			$prepareMetric[$key]["date"] = date("d-m-Y", strtotime($m["date"]));
			$prepareMetric[$key]["value"] = $m["info"]["nb_visits"];
		}
		return $prepareMetric;
	}

}