<?php
  /**
   * Script de suivi d'informations de la freebox HD
   */
   
  $pdo = new PDO('sqlite:'.dirname(__FILE__).'/mafreebox.db'); 
  $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $pageMaFreebox = file_get_contents('http://mafreebox.free.fr/pub/fbx_info.txt');
  
  $etatFreebox = new stdClass();
  $etatFreebox->informations = new stdClass();
  $etatFreebox->adsl = new stdClass();
  $etatFreebox->journalConnexion = new stdClass();
  
  preg_match('/[ ]+Version du firmware[ ]+([0-9.]+)/', $pageMaFreebox, $matches);
  $etatFreebox->informations->versionFirmware = $matches[1];
  $etatFreebox->informations->dateReleve = date('Y-m-d H:i:s');
  
  preg_match('/[ ]+Protocole[ ]+([A-Za-z0-9+]+)/', $pageMaFreebox, $matches);
  $etatFreebox->adsl->protocole = $matches[1];
  
  preg_match('/[ ]+ATM[ ]+([0-9]+) kb\/s[ ]+([0-9]+)/', $pageMaFreebox, $matches);
  $etatFreebox->journalConnexion->debitDescendant = $matches[1];
  $etatFreebox->journalConnexion->debitMontant = $matches[2];
  
  $stmt1 = $pdo->prepare("INSERT INTO releves (rel_dt, rel_version_firmware, rel_protocole, rel_debit_montant, rel_debit_descendant) VALUES (:rel_dt, :rel_version_firmware, :rel_protocole, :rel_debit_montant, :rel_debit_descendant)");
  $stmt1->bindParam(':rel_dt', $etatFreebox->informations->dateReleve, PDO::PARAM_STR);
  $stmt1->bindParam(':rel_version_firmware', $etatFreebox->informations->versionFirmware, PDO::PARAM_STR, 255);
  $stmt1->bindParam(':rel_protocole', $etatFreebox->adsl->protocole, PDO::PARAM_STR, 255);
  $stmt1->bindParam(':rel_debit_montant', $etatFreebox->journalConnexion->debitMontant, PDO::PARAM_INT);
  $stmt1->bindParam(':rel_debit_descendant', $etatFreebox->journalConnexion->debitDescendant, PDO::PARAM_INT);
  $stmt1->execute();
?>
