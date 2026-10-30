<?php
// Tests pour la classe Bibliotheque

$bib = new Bibliotheque();

// Test : bibliotheque vide
verifier($bib->compter() === 0, 'Bibliotheque vide : compter() retourne 0');
verifier($bib->tous() === [], 'Bibliotheque vide : tous() retourne un tableau vide');

// Test : ajouter un livre
$l1 = new Livre('9782100545261', 'Algorithmique', 'Cormen');
$bib->ajouter($l1);
verifier($bib->compter() === 1, 'Apres ajout : compter() retourne 1');

// Test : ajouter un deuxieme livre
$l2 = new Livre('9780132350884', 'Clean Code', 'Robert Martin');
$bib->ajouter($l2);
verifier($bib->compter() === 2, 'Apres 2 ajouts : compter() retourne 2');

// Test : ajouter un troisieme livre
$l3 = new Livre('9780201633610', 'Design Patterns', 'Gamma');
$bib->ajouter($l3);
verifier($bib->compter() === 3, 'Apres 3 ajouts : compter() retourne 3');

// Test : ajout avec ISBN duplique
$exceptionDup = false;
try {
    $bib->ajouter(new Livre('9782100545261', 'Duplicate', 'Test'));
} catch (Exception $e) {
    $exceptionDup = true;
}
verifier($exceptionDup, 'Ajouter un ISBN deja present leve une exception');

// Test : trouver par ISBN existant
$trouve = $bib->trouver('9782100545261');
verifier($trouve !== null && $trouve->getTitre() === 'Algorithmique', 'trouver() retourne le bon livre');

// Test : trouver par ISBN inexistant
verifier($bib->trouver('0000000000') === null, 'trouver() retourne null si ISBN absent');

// Test : tous() retourne tous les livres
verifier(count($bib->tous()) === 3, 'tous() retourne les 3 livres');

// Test : rechercher par titre (insensible a la casse)
$res = $bib->rechercher('algo');
verifier(count($res) === 1 && $res[0]->getTitre() === 'Algorithmique', 'rechercher("algo") trouve Algorithmique');

// Test : rechercher par auteur
$res2 = $bib->rechercher('martin');
verifier(count($res2) === 1 && $res2[0]->getTitre() === 'Clean Code', 'rechercher("martin") trouve Clean Code');

// Test : rechercher sans resultat
$res3 = $bib->rechercher('python');
verifier(count($res3) === 0, 'rechercher("python") ne trouve rien');

// Test : rechercher qui retourne plusieurs resultats
$l4 = new Livre('9780596007126', 'Algo Avance', 'Cormen');
$bib->ajouter($l4);
$res4 = $bib->rechercher('algo');
verifier(count($res4) === 2, 'rechercher("algo") trouve 2 livres');

// Test : statistiques du catalogue (4 livres, tous disponibles par defaut)
$stats = $bib->statistiques();
verifier($stats['total'] === 4,             'statistiques() : total = 4');
verifier($stats['disponibles'] === 4,       'statistiques() : 4 disponibles');
verifier($stats['empruntes'] === 0,         'statistiques() : 0 emprunte');
verifier($stats['tauxDisponibilite'] === 100.0, 'statistiques() : taux = 100%');

// Test : statistiques avec un livre emprunte
$l4->setDisponible(false); // si le setter existe
$stats2 = $bib->statistiques();
verifier($stats2['empruntes'] === 1,            'statistiques() : 1 emprunte apres mise a jour');
verifier($stats2['tauxDisponibilite'] === 75.0, 'statistiques() : taux = 75%');
$l4->setDisponible(true); // remise en etat pour les tests suivants