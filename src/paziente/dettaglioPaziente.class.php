<?php

class dettaglioPaziente {
	
	private static $root;

	private static $idPaziente;
	private static $cognomeRicerca;
	private static $queryDettaglioPaziente = "/paziente/dettaglioPaziente.sql";
	private static $azione = "../paziente/dettaglioPazienteFacade.class.php?modo=start";

	function __construct() {
		
		self::$root = $_SERVER['DOCUMENT_ROOT'];
		$pathToInclude = self::$root . "/ellipse/src/paziente:" . self::$root . "/ellipse/src/utility";  
		set_include_path($pathToInclude);		
	}

	public function setIdPaziente($idPaziente) {
		self::$idPaziente = $idPaziente;
	}
	public function setCognomeRicerca($cognomeRicerca) {
		self::$cognomeRicerca = $cognomeRicerca;
	}

	public function getIdPaziente() {
		return self::$idPaziente;
	}
	public function getCognomeRicerca() {
		return self::$cognomeRicerca;
	}
	public function getAzione() {
		return self::$azione;
	}

	public function start() {
		
		require_once 'paziente.template.php';
		require_once 'database.class.php';
		require_once 'utility.class.php';

		// Template
		$utility = new utility();
		$array = $utility->getConfig();

		$testata = self::$root . $array['testataPagina'];
		$piede = self::$root . $array['piedePagina'];		

		// carica e ritaglia il comando sql da lanciare
		$replace = array('%idpaziente%' => self::$idPaziente);

		$sqlTemplate = self::$root . $array['query'] . self::$queryDettaglioPaziente;

		$sql = $utility->tailFile($utility->getTemplate($sqlTemplate), $replace);
		
		// Lettura del Paziente

		$db = new database();
		$result =	 $db->getData($sql);
		error_log($sql);

		$paziente = new paziente();

		if ($result) {

			$row = pg_fetch_array($result);
			
			$paziente = new paziente();
			$paziente->setIdPaziente(trim($row['idpaziente']));
			
			$paziente->setCognome(trim($row["cognome"]));
			$paziente->setCognomeDisable("readonly");
			$paziente->setCognomeStyle("color:#adadad; border:1px solid;");
			
			$paziente->setNome(trim($row["nome"]));
			$paziente->setNomeDisable("readonly");
			$paziente->setNomeStyle("color:#adadad; border:1px solid;");
			
			$paziente->setIndirizzo(trim($row["indirizzo"]));
			$paziente->setIndirizzoDisable("readonly");
			$paziente->setIndirizzoStyle("color:#adadad; border:1px solid;");
			
			$paziente->setCitta(trim($row["citta"]));
			$paziente->setCittaDisable("readonly");
			$paziente->setCittaStyle("color:#adadad; border:1px solid;");
			
			$paziente->setCap(trim($row["cap"]));
			$paziente->setCapDisable("readonly");
			$paziente->setCapStyle("color:#adadad; border:1px solid;");
			
			$paziente->setProvincia(trim($row["provincia"]));
			$paziente->setProvinciaDisable("readonly");
			$paziente->setProvinciaStyle("color:#adadad; border:1px solid;");
			
			$paziente->setEta(trim($row["eta"]));
			$paziente->setEtaDisable("readonly");
			$paziente->setEtaStyle("color:#adadad; border:1px solid;");
			
			$paziente->setSesso(trim($row["sesso"]));		
			$paziente->setSessoDisable("disabled");
			
			$paziente->setTipo(trim($row["tipo"]));		
			$paziente->setTipoDisable("disabled");
			
			$paziente->setLuogoNascita(trim($row["luogonascita"]));
			$paziente->setLuogoNascitaDisable("readonly");
			$paziente->setLuogoNascitaStyle("color:#adadad; border:1px solid;");
			
			$paziente->setDataNascita(trim($row["datanascita"]));
			$paziente->setDataNascitaDisable("readonly");
			$paziente->setDataNascitaStyle("color:#adadad; border:1px solid;");
			
			$paziente->setCodiceFiscale(trim($row["codicefiscale"]));
			$paziente->setCodiceFiscaleDisable("readonly");
			$paziente->setCodiceFiscaleStyle("color:#adadad; border:1px solid;");
			
			$paziente->setPartitaIva(trim($row["partitaiva"]));
			$paziente->setPartitaIvaDisable("readonly");
			$paziente->setPartitaIvaStyle("color:#adadad; border:1px solid;");
			
			$paziente->setTelefonoFisso(trim($row["telefonofisso"]));
			$paziente->setTelefonoFissoDisable("readonly");
			$paziente->setTelefonoFissoStyle("color:#adadad; border:1px solid;");
			
			$paziente->setTelefonoPortatile(trim($row["telefonoportatile"]));
			$paziente->setTelefonoPortatileDisable("readonly");
			$paziente->setTelefonoPortatileStyle("color:#adadad; border:1px solid;");
			
			$paziente->setEmail(trim($row["email"]));
			$paziente->setEmailDisable("readonly");
			$paziente->setEmailStyle("color:#adadad; border:1px solid;");
			
			$paziente->setDataInserimento(trim($row["datainserimento"]));			
			$paziente->setDataInserimentoStyle("color:#adadad; border:1px solid;");
			$paziente->setDataModifica(trim($row["datamodifica"]));
			$paziente->setDataModificaStyle("color:#adadad; border:1px solid;");
			
			$paziente->setListino(trim($row["idlistino"]));
			$paziente->setListinoDisable("disabled");
			
			$paziente->setMedico(trim($row["idmedico"]));
			$paziente->setMedicoDisable("disabled");
			
			$paziente->setLaboratorio(trim($row["idlaboratorio"]));
			$paziente->setLaboratorioDisable("disabled");			

			$paziente->setAzione($this->getAzione() . "&idPaziente=" . $this->getIdPaziente() . "&cognRic=" . $this->getCognomeRicerca());
			$paziente->setConfermaTip("%ml.rinfrescaDettaglioPaziente%");
			$paziente->setCognomeRicerca($this->getCognomeRicerca());
 			$paziente->setTitoloPagina("%ml.dettaglioPaziente%");
			
			// set dei totali prelevati ---------------------------------
			$paziente->setTotaleVisiteIncorso($row['numvisite_incorso']);
			$paziente->setTotaleVisitePreventivate($row['numvisite_preventivate']);
			$paziente->setTotalePreventiviProposti($row['numpreventivi_proposti']);
			$paziente->setTotalePreventiviAccettati($row['numpreventivi_accettati']);
			$paziente->setTotaleCartelleAttive($row['numcartellecliniche_attive']);
			$paziente->setTotaleCartelleIncorso($row['numcartellecliniche_incorso']);
			$paziente->setTotaleCartelleChiuse($row['numcartellecliniche_chiuse']);
			
			$paziente->setPaziente($paziente);		
			
			include($testata);
			$paziente->displayPagina();
			$paziente->displayTotali();
			include($piede);
		}
		else {
			$paziente->inizializzaPagina();	
			include($testata);
			$paziente->displayPagina();
			$replace = array('%messaggio%' => '%ml.readPazienteKo%');
			echo $utility->tailFile($utility->getTemplate($messaggioErrore), $replace);
			include($piede);			
		}
	}

	public function go() {
	}
}
?>
