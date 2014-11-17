<?php 

class Helper_String{
	public static function sanitarize($texte, $charset='utf-8') {
	    $texte = htmlentities($texte, ENT_NOQUOTES, $charset);
	    $texte = trim($texte);
	    $texte = preg_replace('#&([A-za-z])(?:acute|cedil|circ|grave|orn|ring|slash|th|tilde|uml);#', '\1', $texte); // Enlève les accents
	    $texte = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $texte); // pour les ligatures (le e dans le o)
	    $texte = preg_replace('#&[^;]+;#', '', $texte); // supprime les autres caractères
	    $texte = preg_replace( "/[^A-Za-z0-9]+/", "-", $texte ); // On remplace les caracteres non-alphanumériques par le tiret
	    $texte = strtolower( $texte ); // On convertit le tout en minuscules
	    $texte = trim($texte, '-'); // Supprime les tirets en début ou en fin de chaine
	    return $texte;
	}

	public static function hightlight($txt, $keywords, $balise = "strong"){
		$texte = $txt;
		if(is_string($keywords)){
			return str_replace($keywords, "<".$balise.">".$keywords."</".$balise.">", $texte);
		}
		elseif (is_array($keywords)) {
			foreach ($keywords as $keyword) {
				$texte = str_replace($keyword, "<".$balise.">".$keyword."</".$balise.">", $texte);
			}
		}
		return $texte;
	}

	public static function nomPropre($nom){
		$tab = explode(" ", $nom);
		$uppedName = "";
		foreach ($tab as $val) {
			$uppedName .= ucfirst($val)." ";
		}
		
		return $uppedName;
	}
}